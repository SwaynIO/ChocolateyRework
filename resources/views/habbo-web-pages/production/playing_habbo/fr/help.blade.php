<!-- Page d'aide - WCAG 2.1 AA compliant -->
<main role="main" aria-labelledby="help-heading">
    <!-- En-tête de la page -->
    <header class="help-header">
        <h1 id="help-heading" class="help-title">
            Comment faire face à un problème sur Habbo ?
        </h1>
        <p class="help-intro">
            Tu pourras de temps en temps avoir à faire à de mauvaises conduites sur Habbo. Mais ne crains rien, l'aide 
            est à portée de main ! Sur cette page, nous allons t'expliquer quels sont les outils qui fonctionnent 
            le mieux pour faire face à des situations délicates.
        </p>
        
        <!-- Navigation rapide -->
        <nav aria-label="Navigation rapide dans l'aide" class="help-nav">
            <h2 class="visually-hidden">Accès rapide aux sections d'aide</h2>
            <ul class="help-nav-list" role="list">
                <li role="listitem"><a href="#room-help" class="help-nav-link">Dans un appart</a></li>
                <li role="listitem"><a href="#private-message-help" class="help-nav-link">Dans un message privé</a></li>
                <li role="listitem"><a href="#forum-help" class="help-nav-link">Dans un forum de groupe</a></li>
                <li role="listitem"><a href="#website-help" class="help-nav-link">Sur la page web</a></li>
                <li role="listitem"><a href="#additional-resources" class="help-nav-link">Ressources supplémentaires</a></li>
            </ul>
        </nav>
    </header>
    <!-- Section : Aide dans un appart -->
    <section id="room-help" class="help-section" aria-labelledby="room-help-heading">
        <h2 id="room-help-heading" class="help-section-title">
            Dans un appart
        </h2>
        <p class="help-section-intro">
            Quand tu es dans un appart et qu'un autre Habbo apparaît comme hors-ligne, clique sur son avatar pour faire 
            apparaître un menu déroulant. Tu pourras ainsi l'ignorer, modérer ou même rapporter ce 
            perturbateur.
        </p>
        <!-- Sous-section : Ignorer un Habbo -->
        <article class="help-subsection" id="ignore-user">
            <h3 class="help-subsection-title">
                Ignorer un Habbo
            </h3>
            <p class="help-subsection-intro">
                Si <strong>un Habbo dit des choses qui peuvent te mettre mal à l'aise</strong>, tu peux les ignorer. C'est 
                la solution idéale contre les taquineries, le spam, ou tout simplement quand tu veux lui dire "Hasta la 
                vista" mais tu ne sais pas comment.
            </p>
            
            <figure class="help-image" role="img">
                <img src="{{ $chocolatey->hotelUrl }}habbo-web/assets/web-images/report_FR.png" 
                     alt="Capture d'écran montrant le menu contextuel avec les options ignorer, modérer et rapporter" 
                     class="help-screenshot"
                     loading="lazy"
                     decoding="async">
                <figcaption class="help-image-caption">
                    Menu contextuel d'un avatar avec les options de modération
                </figcaption>
            </figure>
            
            <div class="help-steps">
                <h4 class="help-steps-title">Comment ignorer un utilisateur :</h4>
                <ol class="help-steps-list" role="list">
                    <li role="listitem">
                        <strong>Étape 1 :</strong> Cliquer sur un avatar. Un menu apparaîtra.
                    </li>
                    <li role="listitem">
                        <strong>Étape 2 :</strong> Choisir l'option <em>Ignorer</em>.
                    </li>
                    <li role="listitem">
                        <strong>Étape 3 :</strong> Il te sera impossible de voir ce que ce Habbo dit. Tu as changé d'avis et tu ne souhaites plus l'ignorer ? 
                        Clique à nouveau sur l'avatar et choisis l'option <em>Écouter</em>.
                    </li>
                </ol>
            </div>
        </article>
        <!-- Sous-section : Modérer un Habbo -->
        <article class="help-subsection" id="moderate-user">
            <h3 class="help-subsection-title">
                Modérer un Habbo
            </h3>
            <p class="help-subsection-intro">
                <strong>Si tu es dans ton appart, ou dans un appart dans lequel tu as les droits</strong>, tu pourras décider 
                qui peut visiter l'appart, et tu auras le pouvoir de mettre sous silence, expulser ou bloquer l'accès 
                à d'autres utilisateurs. Cela te permet de jouer un rôle actif dans la modération générale 
                de Habbo et de contribuer à créer une communauté plus saine et plus agréable.
            </p>
            
            <div class="help-resources">
                <h4 class="help-resources-title">Ressources supplémentaires :</h4>
                <ul class="help-links" role="list">
                    <li role="listitem">
                        <a href="https://help.habbo.fr/entries/22589238-Les-nouveaux-outils-de-mod%C3%A9ration-" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="Guide sur les outils de modération (s'ouvre dans un nouvel onglet)">
                            Les outils de modération
                            <span class="external-link-icon" aria-hidden="true">↗</span>
                        </a>
                    </li>
                    <li role="listitem">
                        <a href="https://help.habbo.fr/entries/38351866-Quels-outils-pour-visiter-l-H%C3%B4tel-en-toute-s%C3%A9curit%C3%A9-" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="Guide sur les paramètres des apparts (s'ouvre dans un nouvel onglet)">
                            Les paramètres des apparts
                            <span class="external-link-icon" aria-hidden="true">↗</span>
                        </a>
                    </li>
                </ul>
            </div>
        </article>
        <!-- Sous-section : Rapporter un Habbo -->
        <article class="help-subsection" id="report-user">
            <h3 class="help-subsection-title">
                Rapporter un Habbo
            </h3>
            <div class="help-warning" role="alert">
                <h4 class="help-warning-title">⚠️ Situations graves nécessitant un signalement</h4>
                <p class="help-warning-text">
                    Si <strong>les choses tournent au vinaigre</strong> : des Habbos parlent de se rencontrer dans la vraie vie, veulent s'appeler 
                    par cam, échangent des données personnelles ou si quelqu'un est victime de harcèlement… 
                    Tu peux envisager de signaler ces personnes.
                </p>
                <p class="help-warning-note">
                    <strong>Attention :</strong> utilise cet outil uniquement lorsque c'est nécessaire, 
                    lorsqu'une personne fait intentionnellement du mal aux autres ou à eux-mêmes.
                </p>
            </div>
            
            <div class="help-steps">
                <h4 class="help-steps-title">Comment signaler un utilisateur :</h4>
                <ol class="help-steps-list" role="list">
                    <li role="listitem">
                        <strong>Étape 1 :</strong> Clique sur l'avatar fauteur de troubles pour faire apparaître le menu.
                    </li>
                    <li role="listitem">
                        <strong>Étape 2 :</strong> Clique sur <em>Rapporter</em>.
                    </li>
                    <li role="listitem">
                        <strong>Étape 3 :</strong> Sélectionne les paroles à rapporter.
                    </li>
                    <li role="listitem">
                        <strong>Étape 4 :</strong> Choisis une catégorie pour le problème.
                    </li>
                    <li role="listitem">
                        <strong>Étape 5 :</strong> Explique au modérateur ce qui se passe.
                    </li>
                    <li role="listitem">
                        <strong>Étape 6 :</strong> Envoie ta demande et un modérateur essaiera de résoudre le problème. Si tu choisis <em>Harcèlement</em> 
                        un gardien pourra intervenir.
                    </li>
                </ol>
            </div>
        </article>
        <!-- Méthode alternative de signalement -->
        <article class="help-subsection" id="alternative-report">
            <h3 class="help-subsection-title">
                Méthode alternative de signalement
            </h3>
            
            <figure class="help-image" role="img">
                <img src="{{ $chocolatey->hotelUrl }}habbo-web/assets/web-images/help_button_fr.png" 
                     alt="Bouton d'aide situé en haut à droite de l'interface" 
                     class="help-screenshot"
                     loading="lazy"
                     decoding="async">
                <figcaption class="help-image-caption">
                    Bouton d'aide accessible depuis l'interface principale
                </figcaption>
            </figure>
            
            <div class="help-steps">
                <h4 class="help-steps-title">Signalement via le bouton d'aide :</h4>
                <ol class="help-steps-list" role="list">
                    <li role="listitem">
                        <strong>Étape 1 :</strong> Clique sur <em>Aide</em> en haut à droite.
                    </li>
                    <li role="listitem">
                        <strong>Étape 2 :</strong> Choisis <em>Quelqu'un se comporte mal</em>. Tu verras alors la liste des Habbos dans la salle.
                    </li>
                    <li role="listitem">
                        <strong>Étape 3 :</strong> Choisis l'utilisateur en question.
                    </li>
                    <li role="listitem">
                        <strong>Étape 4 :</strong> Sélectionne le tchat à rapporter aux modérateurs.
                    </li>
                    <li role="listitem">
                        <strong>Étape 5 :</strong> Choisis la meilleure description pour la situation.
                    </li>
                    <li role="listitem">
                        <strong>Étape 6 :</strong> Écris une courte description pour expliquer ce qui se passe.
                    </li>
                    <li role="listitem">
                        <strong>Étape 7 :</strong> Finalement clique sur <em>Envoyer la demande</em>.
                    </li>
                </ol>
            </div>
        </article>
    </section>
    <!-- Section : Aide dans les messages privés -->
    <section id="private-message-help" class="help-section" aria-labelledby="private-message-heading">
        <h2 id="private-message-heading" class="help-section-title">
            Dans un message privé
        </h2>
        <p class="help-section-intro">
            Si <strong>tu parles à quelqu'un en message privé et qu'il te met mal à l'aise</strong> :
        </p>
        
        <div class="help-steps">
            <h3 class="help-steps-title">Comment signaler un message privé :</h3>
            <ol class="help-steps-list" role="list">
                <li role="listitem">
                    <strong>Étape 1 :</strong> Clique sur <em>Signaler</em> se trouvant sous la tête du Habbo de la barre de Tchat.
                </li>
                <li role="listitem">
                    <strong>Étape 2 :</strong> On te demandera plus d'information sur ce qu'il s'est passé.
                </li>
                <li role="listitem">
                    <strong>Étape 3 :</strong> Un modérateur prendra la sanction adéquate.
                </li>
            </ol>
        </div>
        
        <figure class="help-image" role="img">
            <img src="{{ $chocolatey->hotelUrl }}habbo-web/assets/web-images/report_im_fr.png" 
                 alt="Interface de signalement dans la console de messages privés" 
                 class="help-screenshot"
                 loading="lazy"
                 decoding="async">
            <figcaption class="help-image-caption">
                Interface de signalement depuis la console de messages
            </figcaption>
        </figure>
    </section>
    <!-- Section : Aide dans les forums de groupe -->
    <section id="forum-help" class="help-section" aria-labelledby="forum-heading">
        <h2 id="forum-heading" class="help-section-title">
            Dans un forum de groupe
        </h2>
        
        <p class="help-section-intro">
            Tu peux <strong>signaler un sujet ou un commentaire inapproprié dans les forums de groupe</strong>
        </p>
        
        <figure class="help-image" role="img">
            <img src="{{ $chocolatey->hotelUrl }}habbo-web/assets/web-images/flag_3.png" 
                 alt="Icône de drapeau orange pour signaler un commentaire inapproprié" 
                 class="help-screenshot"
                 loading="lazy"
                 decoding="async">
            <figcaption class="help-image-caption">
                Drapeau orange de signalement dans les forums
            </figcaption>
        </figure>
        
        <div class="help-steps">
            <h3 class="help-steps-title">Comment signaler dans un forum :</h3>
            <ol class="help-steps-list" role="list">
                <li role="listitem">
                    <strong>Étape 1 :</strong> Clique sur le drapeau orange.
                </li>
                <li role="listitem">
                    <strong>Étape 2 :</strong> On te demandera plus d'information sur ce qu'il s'est passé.
                </li>
                <li role="listitem">
                    <strong>Étape 3 :</strong> Un modérateur prendra la sanction adéquate.
                </li>
            </ol>
        </div>
    </section>
    <!-- Section : Aide sur la page web -->
    <section id="website-help" class="help-section" aria-labelledby="website-heading">
        <h2 id="website-heading" class="help-section-title">
            Sur la page web
        </h2>
        
        <p class="help-section-intro">
            Tu peux <strong>rapporter une photo inappropriée, un appart ou la homepage d'un appart</strong> :
        </p>
        
        <figure class="help-image" role="img">
            <img src="{{ $chocolatey->hotelUrl }}habbo-web/assets/web-images/reportroom.png" 
                 alt="Icône de drapeau blanc pour signaler des contenus sur les pages d'appart ou photos" 
                 class="help-screenshot"
                 loading="lazy"
                 decoding="async">
            <figcaption class="help-image-caption">
                Drapeau blanc de signalement sur les pages web
            </figcaption>
        </figure>
        
        <div class="help-steps">
            <h3 class="help-steps-title">Comment signaler sur la page web :</h3>
            <ol class="help-steps-list" role="list">
                <li role="listitem">
                    <strong>Étape 1 :</strong> Clique sur le drapeau blanc.
                </li>
                <li role="listitem">
                    <strong>Étape 2 :</strong> Choisis une catégorie.
                </li>
                <li role="listitem">
                    <strong>Étape 3 :</strong> Explique-nous ce qui ne va pas.
                </li>
                <li role="listitem">
                    <strong>Étape 4 :</strong> Un modérateur prendra la sanction adéquate.
                </li>
            </ol>
        </div>
    </section>
    <!-- Section : Ressources supplémentaires -->
    <section id="additional-resources" class="help-section" aria-labelledby="resources-heading">
        <h2 id="resources-heading" class="help-section-title">
            Ressources supplémentaires
        </h2>
        
        <!-- Conseils de sécurité -->
        <article class="help-resource" id="safety-tips">
            <h3 class="help-resource-title">
                Conseils de Sécurité
            </h3>
            <p class="help-resource-description">
                Notre page sur les <a href="/playing-habbo/safety" aria-label="Accéder aux conseils de sécurité">Conseils de Sécurité</a> te permettra de trouver 
                <strong>toutes les informations pour s'amuser sans se mettre en danger</strong>. Regarde-la, elle est pleine d'informations 
                intéressantes !
            </p>
        </article>
        
        <!-- Habbo Attitude -->
        <article class="help-resource" id="habbo-way">
            <h3 class="help-resource-title">
                Habbo Attitude
            </h3>
            <p class="help-resource-description">
                Tu n'as pas encore lu la <a href="/playing-habbo/habbo-way" aria-label="Accéder aux règles Habbo Attitude">Habbo Attitude</a> ? Fais-le au plus vite ! C'est 
                vraiment important car il s'agit des <strong>règles</strong> à suivre pour que notre Hôtel reste 
                un endroit agréable où se détendre.
            </p>
        </article>
        
        <!-- Comment jouer -->
        <article class="help-resource" id="how-to-play">
            <h3 class="help-resource-title">
                Comment jouer ?
            </h3>
            <p class="help-resource-description">
                Tu cherches des <strong>idées pour savoir quoi faire sur Habbo</strong> ? Lis notre <a href="/playing-habbo/how-to-play" aria-label="Accéder au guide comment jouer">guide sur comment jouer</a> !
            </p>
            <p class="help-resource-note">
                Si tu as besoin d'instructions <strong>sur comment utiliser un mobi, un effet, ou tout autre outil de l'Hôtel</strong>, 
                clique sur le bouton <strong>Aide</strong> dans le coin en haut à droite, et clique sur <em>Demande d'instructions</em> 
                pour qu'un Guide vienne à ton aide.
            </p>
        </article>
        
        <!-- Centre d'aide -->
        <article class="help-resource" id="help-center">
            <h3 class="help-resource-title">
                Centre d'Aide Habbo
            </h3>
            <p class="help-resource-description">
                Si tu as un <strong>problème avec ton compte Habbo, une erreur lors d'un achat de crédits ou alors des 
                questions par rapport à une exclusion</strong>, tu trouveras toute l'aide nécessaire dans notre 
                <a href="https://help.habbo.fr" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   aria-label="Accéder au Centre d'Aide officiel (s'ouvre dans un nouvel onglet)">
                    Centre d'Aide
                    <span class="external-link-icon" aria-hidden="true">↗</span>
                </a>
            </p>
        </article>
    </section>
</main>

<!-- CSS pour l'accessibilité et la mise en forme -->
<style>
.visually-hidden {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
    white-space: nowrap !important;
    border: 0 !important;
}

.help-section {
    margin-bottom: 3rem;
    padding: 2rem;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background: #f9f9f9;
}

.help-subsection {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: white;
    border-radius: 6px;
    border-left: 4px solid #1e7cf7;
}

.help-warning {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 4px;
    padding: 1rem;
    margin: 1rem 0;
}

.help-steps-list {
    counter-reset: step-counter;
    list-style: none;
    padding: 0;
}

.help-steps-list li {
    counter-increment: step-counter;
    margin-bottom: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 4px;
    position: relative;
    padding-left: 3rem;
}

.help-steps-list li::before {
    content: counter(step-counter);
    position: absolute;
    left: 1rem;
    top: 1rem;
    background: #1e7cf7;
    color: white;
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.875rem;
}

.help-image {
    margin: 1.5rem 0;
    text-align: center;
}

.help-screenshot {
    max-width: 100%;
    height: auto;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.external-link-icon {
    margin-left: 0.25rem;
    font-size: 0.875em;
}

/* Focus improvements */
a:focus, button:focus, [tabindex]:focus {
    outline: 2px solid #1e7cf7;
    outline-offset: 2px;
}

/* Navigation améliorée */
.help-nav-list {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    list-style: none;
    padding: 0;
    margin: 1rem 0;
}

.help-nav-link {
    padding: 0.5rem 1rem;
    background: #1e7cf7;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.help-nav-link:hover, .help-nav-link:focus {
    background: #1565c0;
}
</style>
