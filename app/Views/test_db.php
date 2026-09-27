<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test BDD SmartDevis</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        h1   { color: #333; }
        h2   { color: #e44d26; margin-top: 30px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 10px; background: white; }
        th   { background: #e44d26; color: white; padding: 8px 12px; text-align: left; }
        td   { padding: 8px 12px; border-bottom: 1px solid #ddd; }
        tr:hover td { background: #fff3f0; }
        .empty { color: #999; font-style: italic; }
    </style>
</head>
<body>

<h1>🗄️ Test Base de Données — SmartDevis</h1>

<?php
$sections = [
    'comptes'  => '👤 t_compte_cpt',
    'devis'    => '📄 t_devis_dev',
    'details'  => '🔧 t_detail_det',
    'messages' => '✉️ t_message_msg',
];

foreach ($sections as $var => $titre):
    echo "<h2>$titre</h2>";
    if (empty($$var)):
        echo "<p class='empty'>Aucune donnée dans cette table.</p>";
    else:
?>
<table>
    <tr>
        <?php foreach (array_keys($$var[0]) as $col): ?>
            <th><?= $col ?></th>
        <?php endforeach; ?>
    </tr>
    <?php foreach ($$var as $row): ?>
    <tr>
        <?php foreach ($row as $val): ?>
            <td><?= htmlspecialchars((string)$val) ?></td>
        <?php endforeach; ?>
    </tr>
    <?php endforeach; ?>
</table>
<?php
    endif;
endforeach;
?>

</body>
</html>