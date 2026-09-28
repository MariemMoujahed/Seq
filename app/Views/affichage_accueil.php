<main>
<!-- ═══ HERO ═════════════════════════════════════════════ -->

<section class="hero">

  <div class="hero-tag">
    Sécurité électronique & solutions connectées
  </div>

  <h1>
    Protégez vos espaces avec des
    <em>solutions intelligentes</em>
  </h1>

  <p>
    Nous sommes spécialisés dans l'installation de solutions de sécurité
    électronique et de systèmes connectés pour les particuliers,
    professionnels et entreprises.
  </p>

  <div class="hero-cta">

    <a href="#contact" class="btn-primary">
      Demander un devis
    </a>

    <a href="#services" class="btn-ghost">
      Découvrir nos solutions
    </a>

  </div>

</section>


<!-- ═══ SERVICES ═════════════════════════════════════════ -->

<section id="services">

  <div class="reveal">

    <div class="section-tag">
      Nos solutions
    </div>

    <h2 class="section-title">
      Des solutions de sécurité adaptées à vos besoins
    </h2>

    <p class="section-lead">
      De la vidéosurveillance à la domotique, nous proposons des solutions
      modernes et fiables pour sécuriser, contrôler et connecter vos espaces.
    </p>

  </div>


  <div class="services-grid reveal" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;">

    <!-- 01 — VIDÉOSURVEILLANCE -->
    <article class="service-card" style="position: relative; background: var(--c-card); border: 1px solid var(--c-border); border-radius: var(--radius); overflow: hidden; transition: all .3s ease;">
        <div class="service-card-image" style="aspect-ratio: 4/3; background: linear-gradient(135deg, var(--c-accent-light), var(--c-surface)); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
            <svg class="service-icon-large" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width: 64px; height: 64px; color: var(--c-accent); opacity: 0.15; transition: all .3s ease;" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-2.36A1 1 0 0122 9.03v5.94a1 1 0 01-1.53.85l-4.72-2.36M4.5 19.5h9a2.25 2.25 0 002.25-2.25v-9A2.25 2.25 0 0013.5 6h-9a2.25 2.25 0 00-2.25 2.25v9A2.25 2.25 0 004.5 19.5z" />
            </svg>
            <span class="service-category-tag" style="position: absolute; top: .75rem; left: .75rem; background: var(--c-accent); color: white; padding: .25rem .6rem; border-radius: 999px; font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em;">Sécurité</span>
        </div>
        <div class="service-card-content" style="padding: 1.25rem;">
            <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.1rem; font-weight: 400; color: var(--c-ink); margin-bottom: .5rem;">Caméras de surveillance</h3>
            <p style="color: var(--c-muted); line-height: 1.55; margin: 0 0 1rem; font-size: .9rem;">Installation de systèmes de vidéosurveillance adaptés à vos locaux afin de surveiller efficacement vos espaces et renforcer leur sécurité.</p>
            <a href="<?= base_url('catalogue/categorie/Vid%C3%A9osurveillance') ?>" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: .4rem; padding: .45rem .9rem; font-size: .825rem;">En savoir plus <i class="fas fa-arrow-right" aria-hidden="true" style="font-size: .75rem;"></i></a>
        </div>
    </article>

    <!-- 02 — INTERPHONES -->
    <article class="service-card" style="position: relative; background: var(--c-card); border: 1px solid var(--c-border); border-radius: var(--radius); overflow: hidden; transition: all .3s ease;">
        <div class="service-card-image" style="aspect-ratio: 4/3; background: linear-gradient(135deg, #e8f0e8, var(--c-surface)); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
            <svg class="service-icon-large" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width: 64px; height: 64px; color: var(--c-accent); opacity: 0.15; transition: all .3s ease;" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75h.008v.008h-.008V6.75zM18 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h.008v.008H7.5V8.25zM7.5 12h.008v.008H7.5V12zM7.5 15.75h.008v.008H7.5v-.008z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 8.25h5.25M11.25 12h5.25M11.25 15.75h5.25" />
            </svg>
            <span class="service-category-tag" style="position: absolute; top: .75rem; left: .75rem; background: var(--c-accent); color: white; padding: .25rem .6rem; border-radius: 999px; font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em;">Contrôle d'accès</span>
        </div>
        <div class="service-card-content" style="padding: 1.25rem;">
            <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.1rem; font-weight: 400; color: var(--c-ink); margin-bottom: .5rem;">Interphones & vidéophones</h3>
            <p style="color: var(--c-muted); line-height: 1.55; margin: 0 0 1rem; font-size: .9rem;">Installation d'interphones et de vidéophones permettant d'identifier les visiteurs et de contrôler les accès à votre bâtiment.</p>
            <a href="<?= base_url('catalogue/categorie/Interphone') ?>" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: .4rem; padding: .45rem .9rem; font-size: .825rem;">En savoir plus <i class="fas fa-arrow-right" aria-hidden="true" style="font-size: .75rem;"></i></a>
        </div>
    </article>

    <!-- 03 — ALARMES -->
    <article class="service-card" style="position: relative; background: var(--c-card); border: 1px solid var(--c-border); border-radius: var(--radius); overflow: hidden; transition: all .3s ease;">
        <div class="service-card-image" style="aspect-ratio: 4/3; background: linear-gradient(135deg, #fef3e8, var(--c-surface)); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
            <svg class="service-icon-large" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width: 64px; height: 64px; color: var(--c-pending); opacity: 0.15; transition: all .3s ease;" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2.25m0 3.75h.008v.008H12V15z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 3.94L2.89 17.19A1.5 1.5 0 004.2 19.5h15.6a1.5 1.5 0 001.31-2.31L13.66 3.94a1.9 1.9 0 00-3.32 0z" />
            </svg>
            <span class="service-category-tag" style="position: absolute; top: .75rem; left: .75rem; background: var(--c-pending); color: white; padding: .25rem .6rem; border-radius: 999px; font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em;">Alarme</span>
        </div>
        <div class="service-card-content" style="padding: 1.25rem;">
            <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.1rem; font-weight: 400; color: var(--c-ink); margin-bottom: .5rem;">Alarmes intrusion & incendie</h3>
            <p style="color: var(--c-muted); line-height: 1.55; margin: 0 0 1rem; font-size: .9rem;">Mise en place de systèmes d'alarme pour détecter les tentatives d'intrusion et prévenir les risques liés aux incendies.</p>
            <a href="<?= base_url('catalogue/categorie/Alarme') ?>" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: .4rem; padding: .45rem .9rem; font-size: .825rem;">En savoir plus <i class="fas fa-arrow-right" aria-hidden="true" style="font-size: .75rem;"></i></a>
        </div>
    </article>

<!-- 04 — CONTRÔLE D'ACCÈS -->
    <article class="service-card" style="position: relative; background: var(--c-card); border: 1px solid var(--c-border); border-radius: var(--radius); overflow: hidden; transition: all .3s ease;">
        <div class="service-card-image" style="aspect-ratio: 4/3; background: linear-gradient(135deg, #e8eef7, var(--c-surface)); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
            <svg class="service-icon-large" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width: 64px; height: 64px; color: #36b9cc; opacity: 0.15; transition: all .3s ease;" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 20.25a7.5 7.5 0 0115 0" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25l1.5 1.5 3-3" />
            </svg>
            <span class="service-category-tag" style="position: absolute; top: .75rem; left: .75rem; background: #36b9cc; color: white; padding: .25rem .6rem; border-radius: 999px; font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em;">Accès</span>
        </div>
        <div class="service-card-content" style="padding: 1.25rem;">
            <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.1rem; font-weight: 400; color: var(--c-ink); margin-bottom: .5rem;">Contrôle d'accès</h3>
            <p style="color: var(--c-muted); line-height: 1.55; margin: 0 0 1rem; font-size: .9rem;">Sécurisation et gestion des accès grâce à des solutions adaptées aux entreprises, commerces, immeubles et bâtiments professionnels.</p>
            <a href="<?= base_url('catalogue/categorie/Contr%C3%B4le') ?>" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: .4rem; padding: .45rem .9rem; font-size: .825rem;">En savoir plus <i class="fas fa-arrow-right" aria-hidden="true" style="font-size: .75rem;"></i></a>
        </div>
    </article>

<!-- 05 — POINTAGE -->
    <article class="service-card" style="position: relative; background: var(--c-card); border: 1px solid var(--c-border); border-radius: var(--radius); overflow: hidden; transition: all .3s ease;">
        <div class="service-card-image" style="aspect-ratio: 4/3; background: linear-gradient(135deg, #f5e8f7, var(--c-surface)); display: flex; align-items: center; justify_content: center; position: relative; overflow: hidden;">
            <svg class="service-icon-large" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width: 64px; height: 64px; color: #9f7aea; opacity: 0.15; transition: all .3s ease;" aria-hidden="true">
                <circle cx="12" cy="12" r="8.25" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5v4.5l3 1.75" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75v1.5M20.25 12h-1.5M12 20.25v-1.5M3.75 12h1.5" />
            </svg>
            <span class="service-category-tag" style="position: absolute; top: .75rem; left: .75rem; background: #9f7aea; color: white; padding: .25rem .6rem; border-radius: 999px; font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em;">RH</span>
        </div>
        <div class="service-card-content" style="padding: 1.25rem;">
            <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.1rem; font-weight: 400; color: var(--c-ink); margin-bottom: .5rem;">Systèmes de pointage</h3>
            <p style="color: var(--c-muted); line-height: 1.55; margin: 0 0 1rem; font-size: .9rem;">Installation de solutions de pointage permettant de gérer et suivre efficacement les entrées et sorties du personnel.</p>
            <a href="<?= base_url('catalogue/categorie/Pointage') ?>" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: .4rem; padding: .45rem .9rem; font-size: .825rem;">En savoir plus <i class="fas fa-arrow-right" aria-hidden="true" style="font-size: .75rem;"></i></a>
        </div>
    </article>

<!-- 06 — PORTES AUTOMATIQUES -->
    <article class="service-card" style="position: relative; background: var(--c-card); border: 1px solid var(--c-border); border-radius: var(--radius); overflow: hidden; transition: all .3s ease;">
        <div class="service-card-image" style="aspect-ratio: 4/3; background: linear-gradient(135deg, #e8f7f5, var(--c-surface)); display: flex; align-items: center; justify_content: center; position: relative; overflow: hidden;">
            <svg class="service-icon-large" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width: 64px; height: 64px; color: #2dd4bf; opacity: 0.15; transition: all .3s ease;" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 20.25h13.5" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 20.25V5.5A1.75 1.75 0 018.5 3.75h7A1.75 1.75 0 0117.25 5.5v14.75" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75v16.5" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 12h.008v.008H9.75V12z" />
            </svg>
            <span class="service-category-tag" style="position: absolute; top: .75rem; left: .75rem; background: #2dd4bf; color: white; padding: .25rem .6rem; border-radius: 999px; font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em;">Automatisme</span>
        </div>
        <div class="service-card-content" style="padding: 1.25rem;">
            <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.1rem; font-weight: 400; color: var(--c-ink); margin-bottom: .5rem;">Portes automatiques</h3>
            <p style="color: var(--c-muted); line-height: 1.55; margin: 0 0 1rem; font-size: .9rem;">Installation de solutions d'ouverture automatique pour améliorer l'accessibilité, le confort et la sécurité de vos espaces.</p>
            <a href="<?= base_url('catalogue/categorie/Portes') ?>" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: .4rem; padding: .45rem .9rem; font-size: .825rem;">En savoir plus <i class="fas fa-arrow-right" aria-hidden="true" style="font-size: .75rem;"></i></a>
        </div>
    </article>

    <!-- 07 — DOMOTIQUE -->
    <article class="service-card" style="position: relative; background: var(--c-card); border: 1px solid var(--c-border); border-radius: var(--radius); overflow: hidden; transition: all .3s ease;">
        <div class="service-card-image" style="aspect-ratio: 4/3; background: linear-gradient(135deg, #fff3e0, var(--c-surface)); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
            <svg class="service-icon-large" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width: 64px; height: 64px; color: #f6c23e; opacity: 0.15; transition: all .3s ease;" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 10.5L12 3l8.25 7.5" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 9.75v9.75a1.5 1.5 0 001.5 1.5h10.5a1.5 1.5 0 001.5-1.5V9.75" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20.25v-6h6v6" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75h4.5" />
            </svg>
            <span class="service-category-tag" style="position: absolute; top: .75rem; left: .75rem; background: #f6c23e; color: white; padding: .25rem .6rem; border-radius: 999px; font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em;">Connecté</span>
        </div>
        <div class="service-card-content" style="padding: 1.25rem;">
            <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.1rem; font-weight: 400; color: var(--c-ink); margin-bottom: .5rem;">Solutions de domotique</h3>
            <p style="color: var(--c-muted); line-height: 1.55; margin: 0 0 1rem; font-size: .9rem;">Des solutions connectées pour automatiser, contrôler et améliorer le confort, la sécurité et la gestion de vos espaces.</p>
            <a href="<?= base_url('catalogue/categorie/Domotique') ?>" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: .4rem; padding: .45rem .9rem; font-size: .825rem;">En savoir plus <i class="fas fa-arrow-right" aria-hidden="true" style="font-size: .75rem;"></i></a>
        </div>
    </article>
</div>

<style>
.service-card {
    transition: all .3s ease;
}
.service-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 32px rgba(0,0,0,.1);
    border-color: var(--c-accent);
}
.service-card:hover .service-icon-large {
    opacity: 0.25;
    transform: scale(1.05);
}
.service-card:hover .service-category-tag {
    transform: scale(1.05);
}
.service-card-image {
    position: relative;
}
.service-card-image::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 50%, rgba(0,0,0,.05) 100%);
    pointer-events: none;
}
@media (max-width: 768px) {
    .services-grid {
        grid-template-columns: 1fr;
    }
}
</style>

</section>


<!-- ═══ RÉALISATIONS ═════════════════════════════════════ -->

<section id="realisations">

  <div class="reveal">

    <div class="section-tag">
      Nos réalisations
    </div>

    <h2 class="section-title">
      Des installations pensées pour votre sécurité
    </h2>

    <p class="section-lead">
      Chaque installation est étudiée afin de répondre aux contraintes
      du bâtiment, aux besoins de sécurité et aux usages de nos clients.
    </p>

  </div>


  <div class="portfolio-grid reveal">


    <div class="portfolio-card">

      <div class="portfolio-thumb">
        📹
      </div>

      <div class="portfolio-info">

        <span class="portfolio-badge">
          Vidéosurveillance
        </span>

        <h3>
          Surveillance des locaux
        </h3>

        <p>
          Installation d'un système de caméras adapté à la configuration
          des espaces et aux besoins de surveillance.
        </p>

      </div>

    </div>


    <div class="portfolio-card">

      <div class="portfolio-thumb">
        🔐
      </div>

      <div class="portfolio-info">

        <span class="portfolio-badge">
          Contrôle d'accès
        </span>

        <h3>
          Sécurisation des accès
        </h3>

        <p>
          Mise en place d'une solution permettant de contrôler les accès
          aux différentes zones d'un bâtiment.
        </p>

      </div>

    </div>


    <div class="portfolio-card">

      <div class="portfolio-thumb">
        🚨
      </div>

      <div class="portfolio-info">

        <span class="portfolio-badge">
          Alarme
        </span>

        <h3>
          Protection contre les intrusions
        </h3>

        <p>
          Installation d'un système d'alarme destiné à renforcer
          la protection des locaux.
        </p>

      </div>

    </div>


    <div class="portfolio-card">

      <div class="portfolio-thumb">
        🏠
      </div>

      <div class="portfolio-info">

        <span class="portfolio-badge">
          Domotique
        </span>

        <h3>
          Habitat connecté
        </h3>

        <p>
          Intégration de solutions connectées pour améliorer le confort,
          la gestion et la sécurité du bâtiment.
        </p>

      </div>

    </div>


  </div>

</section>


<!-- ═══ ACCOMPAGNEMENT ══════════════════════════════════ -->

<section id="accompagnement">

  <div class="missions-wrap">

    <div class="reveal">

      <div class="section-tag">
        Notre accompagnement
      </div>

      <h2 class="section-title">
        De l'étude à l'installation, un accompagnement complet
      </h2>

      <p class="section-lead">
        Nous vous accompagnons à chaque étape afin de proposer une
        installation fiable, adaptée à votre environnement et à vos besoins.
      </p>

    </div>


    <div class="missions-list reveal">


      <!-- ÉTAPE 01 -->

      <div class="mission-item">

        <div class="mission-num">
          01
        </div>

        <div>

          <h4>
            Étude de vos besoins
          </h4>

          <p>
            Analyse de votre environnement, de vos contraintes et de vos
            objectifs afin d'identifier les solutions les plus adaptées.
          </p>

        </div>

      </div>


      <!-- ÉTAPE 02 -->

      <div class="mission-item">

        <div class="mission-num">
          02
        </div>

        <div>

          <h4>
            Installation & configuration
          </h4>

          <p>
            Installation des équipements, raccordement et configuration
            des systèmes pour garantir leur bon fonctionnement.
          </p>

        </div>

      </div>


      <!-- ÉTAPE 03 -->

      <div class="mission-item">

        <div class="mission-num">
          03
        </div>

        <div>

          <h4>
            Mise en service
          </h4>

          <p>
            Vérification complète de l'installation et accompagnement
            dans la prise en main des différents équipements.
          </p>

        </div>

      </div>


      <!-- ÉTAPE 04 -->

      <div class="mission-item">

        <div class="mission-num">
          04
        </div>

        <div>

          <h4>
            Maintenance & suivi
          </h4>

          <p>
            Suivi de vos installations et interventions afin de maintenir
            vos équipements performants et opérationnels dans le temps.
          </p>

        </div>

      </div>


    </div>

  </div>

</section>


<!-- ═══ CONTACT ══════════════════════════════════════════ -->

<section id="contact">

  <div class="reveal">

    <div class="section-tag">
      Contact
    </div>

    <h2 class="section-title">
      Parlons de votre projet
    </h2>

    <p class="section-lead">
      Vous avez un projet de vidéosurveillance, d'alarme, de contrôle
      d'accès ou de domotique ? Contactez-nous pour échanger sur vos besoins
      et obtenir une étude adaptée.
    </p>

  </div>


  <div class="contact-wrap reveal">


    <!-- INFORMATIONS -->

    <div class="contact-info">

      <h3>
        Votre sécurité, notre priorité
      </h3>

      <p>
        Notre équipe vous accompagne dans la mise en place de solutions
        de sécurité électronique et de systèmes connectés adaptés
        à votre environnement.
      </p>


      <div class="contact-detail">


        <!-- LOCALISATION -->

        <div class="contact-detail-item">

          <svg
            fill="none"
            stroke="currentColor"
            stroke-width="1.5"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"
            />

            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"
            />
          </svg>

          <span>
            Votre adresse<br>
            Votre ville
          </span>

        </div>


        <!-- EMAIL -->

        <div class="contact-detail-item">

          <svg
            fill="none"
            stroke="currentColor"
            stroke-width="1.5"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"
            />
          </svg>

          <span>
            contact@votre-entreprise.fr
          </span>

        </div>


        <!-- TÉLÉPHONE -->

        <div class="contact-detail-item">

          <svg
            fill="none"
            stroke="currentColor"
            stroke-width="1.5"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"
            />
          </svg>

          <span>
            00 00 00 00 00
          </span>

        </div>


      </div>

    </div>


    <!-- FORMULAIRE -->

    <form
      class="contact-form"
      id="contact-form"
    >

      <div class="form-row">


        <div class="form-group">

          <label for="name">
            Nom
          </label>

          <input
            type="text"
            id="name"
            name="name"
            placeholder="Votre nom"
          />

        </div>


        <div class="form-group">

          <label for="email">
            Adresse e-mail
          </label>

          <input
            type="email"
            id="email"
            name="email"
            placeholder="votre@email.com"
          />

        </div>


      </div>


      <!-- TYPE DE BESOIN -->

      <div class="form-group">

        <label for="service">
          Votre besoin
        </label>

        <select
          id="service"
          name="service"
        >

          <option value="">
            Sélectionnez une solution
          </option>

          <option value="videosurveillance">
            Caméras de surveillance
          </option>

          <option value="interphone">
            Interphone / Vidéophone
          </option>

          <option value="alarme">
            Alarme intrusion / incendie
          </option>

          <option value="controle-acces">
            Contrôle d'accès
          </option>

          <option value="pointage">
            Système de pointage
          </option>

          <option value="portes-automatiques">
            Portes automatiques
          </option>

          <option value="domotique">
            Domotique
          </option>

          <option value="autre">
            Autre demande
          </option>

        </select>

      </div>


      <!-- MESSAGE -->

      <div class="form-group">

        <label for="message">
          Votre projet
        </label>

        <textarea
          id="message"
          name="message"
          placeholder="Décrivez-nous votre projet ou votre besoin…"
        ></textarea>

      </div>


      <!-- CAPTCHA -->

      <div class="form-group">

        <label>
          Vérification
        </label>

        <div class="captcha-row">

          <span id="captcha-q">
            9 + 4 =
          </span>

          <input
            type="text"
            id="captcha-a"
            placeholder="?"
          />

        </div>

      </div>


      <button
        type="submit"
        class="btn-submit"
      >
        Demander un devis
      </button>


      <p
        id="form-msg"
        style="font-size:.85rem; color: var(--orange); display:none;"
      ></p>


    </form>

  </div>

</section>

  <!-- ═══ LOGIN CTA ═════════════════════════════════════════════ -->
  
  <section id="espace-client" class="login-cta-section">
  
    <div class="login-cta-card reveal">
  
      <div class="login-cta-visual">
        <svg class="login-cta-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        <div class="login-cta-pulse"></div>
      </div>
  
      <div class="login-cta-content">
        <span class="login-cta-badge">
          <i class="fas fa-shield-alt" aria-hidden="true"></i>
          Espace Client Sécurisé
        </span>
  
        <h2 class="login-cta-title">
          Accédez à votre tableau de bord
        </h2>
  
        <p class="login-cta-desc">
          Gérez vos installations, suivez vos devis, consultez l'historique de vos interventions et configurez vos systèmes de sécurité en temps réel.
        </p>
  
        <ul class="login-cta-features">
          <li>
            <i class="fas fa-check" aria-hidden="true"></i>
            <span>Suivi de vos devis et commandes</span>
          </li>
          <li>
            <i class="fas fa-check" aria-hidden="true"></i>
            <span>Historique des interventions</span>
          </li>
          <li>
            <i class="fas fa-check" aria-hidden="true"></i>
            <span>Configuration de vos équipements</span>
          </li>
          <li>
            <i class="fas fa-check" aria-hidden="true"></i>
            <span>Support technique prioritaire</span>
          </li>
        </ul>
  
        <div class="login-cta-actions">
          <a href="<?= base_url('index.php/compte/connecter') ?>" class="btn-login-primary">
            <span>Se connecter</span>
            <i class="fas fa-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="<?= base_url('index.php/compte/creer') ?>" class="btn-login-secondary">
            Créer un compte
          </a>
        </div>
  
        <p class="login-cta-note">
          <i class="fas fa-lock" aria-hidden="true"></i>
          Connexion sécurisée • Données chiffrées • Accès 24/7
        </p>
      </div>
  
    </div>
  
  </section>

</main>