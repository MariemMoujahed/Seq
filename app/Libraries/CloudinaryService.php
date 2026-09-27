<?php

namespace App\Libraries;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Exception;
use finfo;

/**
 * Wrapper minimal autour du SDK Cloudinary.
 *
 * La configuration est lue depuis CLOUDINARY_URL (fichier .env, ignoré par git)
 * plutôt que codée en dur, afin que le secret API ne soit jamais versionné.
 */
class CloudinaryService
{
    /** Formats autorisés, en.extensions et types MIME réels. */
    private const ALLOWED_EXT = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    private const ALLOWED_MIME = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
    ];

    /** Taille maximale : 5 Mo. */
    private const MAX_BYTES = 5 * 1024 * 1024;

    private static ?Cloudinary $client = null;

    /**
     * Instancie le client à partir de CLOUDINARY_URL.
     */
    private static function client(): Cloudinary
    {
        if (self::$client instanceof Cloudinary) {
            return self::$client;
        }

        $url = env('CLOUDINARY_URL');

        if (empty($url)) {
            throw new Exception('CLOUDINARY_URL non défini dans .env');
        }

        $config = Configuration::fromCloudinaryUrl($url);

        return self::$client = new Cloudinary($config);
    }

    /**
     * Vérifie qu'un fichier téléversé est bien une image autorisée.
     *
     * On contrôle l'extension ET le type MIME réel du fichier : ne pas utiliser
     * UploadedFile::is_image(), contournable (voir CVE-2026-63223).
     *
     * @return string Message d'erreur, ou chaîne vide si le fichier est valide.
     */
    public static function validateImage(?string $tempPath, ?string $clientName): string
    {
        if ($tempPath === null || ! is_file($tempPath)) {
            return 'Aucun fichier reçu.';
        }

        $filesize = filesize($tempPath);
        if ($filesize === false || $filesize === 0) {
            return 'Le fichier est vide.';
        }
        if ($filesize > self::MAX_BYTES) {
            return 'Image trop lourde (5 Mo maximum).';
        }

        $ext = strtolower((string) pathinfo((string) $clientName, PATHINFO_EXTENSION));
        if (! in_array($ext, self::ALLOWED_EXT, true)) {
            return 'Format non autorisé (jpg, png, webp ou gif).';
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = (string) $finfo->file($tempPath);

        if (! in_array($mime, self::ALLOWED_MIME, true)) {
            return 'Le contenu du fichier n’est pas une image valide.';
        }

        return '';
    }

    /**
     * Envoie un fichier local vers Cloudinary.
     *
     * @param string $publicId Remplacement : identifiant existant à écraser.
     * @param string $slug     Nouvel asset : base du nom public, derives du
     *                         nom du produit. Si déjà pris, Cloudinary suffixe
     *                         automatiquement (overwrite désactivé).
     *
     * @return array{url: string, public_id: string}
     */
    public static function upload(string $tempPath, string $publicId = '', string $folder = 'produits', string $slug = ''): array
    {
        $options = [
            'use_filename'    => false,
            'unique_filename' => true,
        ];

        if ($publicId !== '') {
            // Remplacement : $publicId est déjà complet (dossier inclus),
            // on ne remet donc pas 'folder', sinon Cloudinary le préfixe
            // une seconde fois et obtient produits/produits/....
            $options['public_id']  = $publicId;
            $options['overwrite']  = true;
            $options['invalidate'] = true;
        } else {
            $options['folder'] = $folder;

            if ($slug !== '') {
                // Nouvel asset : ne pas écraser un produit homonyme.
                $options['public_id'] = $slug;
                $options['overwrite'] = false;
            }
        }

        $result = self::client()->uploadApi()->upload($tempPath, $options);

        if (! isset($result['secure_url'], $result['public_id'])) {
            throw new Exception('Réponse Cloudinary inattendue.');
        }

        return [
            'url'       => (string) $result['secure_url'],
            'public_id' => (string) $result['public_id'],
        ];
    }

    /**
     * Supprime un asset Cloudinary. Ne lève pas d'exception si l'asset
     * n'existe déjà, pour ne pas bloquer la suppression d'un produit.
     */
    public static function destroy(?string $publicId): void
    {
        if ($publicId === null || $publicId === '') {
            return;
        }

        try {
            self::client()->uploadApi()->destroy($publicId);
        } catch (\Throwable $e) {
            log_message('error', 'Cloudinary destroy failed for {id}: {msg}', [
                'id'  => $publicId,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    /**
     * URL de miniature optimisée (utile pour les listes).
     *
     * Cloudinary attend la transformation juste après « /image/upload/ » :
     * .../image/upload/<transformation>/<version>/<public_id>.<ext>
     */
    public static function thumbnail(string $url, int $width = 120, int $height = 120): string
    {
        if (stripos($url, 'res.cloudinary.com') === false) {
            return $url;
        }

        $transform = sprintf('g_auto,w_%d,h_%d,c_fill', $width, $height);

        $result = preg_replace(
            '#^(https?://res\.cloudinary\.com/[^/]+/image/upload/)#i',
            '$1' . $transform . '/',
            $url,
            1
        );

        return $result ?? $url;
    }
}
