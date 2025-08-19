<!-- Page de conseils sécurité - WCAG 2.1 AA compliant -->
<main role="main" aria-labelledby="safety-heading">
    <!-- Page header -->
    <header class="safety-header">
        <h1 id="safety-heading" class="safety-title">
            Conseils sécurité sur Internet
        </h1>
        <p class="safety-intro">
            Ci-dessous les 7 meilleurs conseils pour savoir comment naviguer sur Internet en toute sécurité !
        </p>
        
        <!-- Navigation pour accès rapide -->
        <nav aria-label="Navigation rapide des conseils" class="safety-nav">
            <h2 class="visually-hidden">Accès rapide aux conseils</h2>
            <ol class="safety-nav-list" role="list">
                <li role="listitem"><a href="#tip-1" class="safety-nav-link">Protège tes informations personnelles</a></li>
                <li role="listitem"><a href="#tip-2" class="safety-nav-link">Protège ta vie privée</a></li>
                <li role="listitem"><a href="#tip-3" class="safety-nav-link">Ne cède pas à la pression</a></li>
                <li role="listitem"><a href="#tip-4" class="safety-nav-link">Garde tes copains en pixels</a></li>
                <li role="listitem"><a href="#tip-5" class="safety-nav-link">N'aies pas peur de dire les choses</a></li>
                <li role="listitem"><a href="#tip-6" class="safety-nav-link">Laisse tomber les images</a></li>
                <li role="listitem"><a href="#tip-7" class="safety-nav-link">Sois un surfeur intelligent</a></li>
            </ol>
        </nav>
    </header>
    
    <!-- Liste des conseils avec structure sémantique -->
    <section class="safety-tips" aria-label="Liste des conseils de sécurité">
        <!-- Conseil 1 -->
        <article id="tip-1" class="safety-tip" tabindex="0">
            <header class="safety-tip-header">
                <div class="safety-tip-image">
                    <img src="{{ $chocolatey->hotelUrl }}habbo-web/assets/web-images/safetytips1_n.png" 
                         alt="Illustration : Protéger ses informations personnelles" 
                         class="safety-tip-img"
                         loading="lazy"
                         decoding="async">
                </div>
                <h2 class="safety-tip-title">
                    <span class="tip-number" aria-label="Conseil numéro 1">1.</span>
                    Protège tes informations personnelles
                </h2>
            </header>
            <div class="safety-tip-content">
                <p class="safety-tip-text">
                    Tu ne sais jamais avec qui tu es vraiment en train de parler en ligne, donc ne donne jamais ton vrai nom, adresse, 
                    numéro de téléphone, photos ou nom de ton école. Partager ces informations personnelles peut te 
                    conduire à être victime d'une arnaque, d'intimidation ou de te mettre en danger.
                </p>
                
                <!-- Points clés en liste pour meilleure accessibilité -->
                <div class="safety-tip-details">
                    <h3 class="safety-tip-subtitle">Informations à ne jamais partager :</h3>
                    <ul class="safety-list" role="list">
                        <li role="listitem">Ton vrai nom complet</li>
                        <li role="listitem">Ton adresse postale</li>
                        <li role="listitem">Ton numéro de téléphone</li>
                        <li role="listitem">Des photos personnelles</li>
                        <li role="listitem">Le nom de ton école</li>
                    </ul>
                </div>
            </div>
        </article>
        <!-- Conseil 2 -->
        <article id="tip-2" class="safety-tip" tabindex="0">
            <header class="safety-tip-header">
                <div class="safety-tip-image">
                    <img src="{{ $chocolatey->hotelUrl }}habbo-web/assets/web-images/safetytips2_n2.png" 
                         alt="Illustration : Protéger sa vie privée sur les réseaux sociaux" 
                         class="safety-tip-img"
                         loading="lazy"
                         decoding="async">
                </div>
                <h2 class="safety-tip-title">
                    <span class="tip-number" aria-label="Conseil numéro 2">2.</span>
                    Protège ta vie privée
                </h2>
            </header>
            <div class="safety-tip-content">
                <p class="safety-tip-text">
                    Garde les coordonnées de ton Facebook, Twitter, Skype, Instagram ou Snapchat pour toi. Tu ne sais jamais où 
                    cela peut te conduire.
                </p>
                
                <div class="safety-tip-details">
                    <h3 class="safety-tip-subtitle">Réseaux sociaux à protéger :</h3>
                    <ul class="safety-list" role="list">
                        <li role="listitem">Facebook</li>
                        <li role="listitem">Twitter</li>
                        <li role="listitem">Skype</li>
                        <li role="listitem">Instagram</li>
                        <li role="listitem">Snapchat</li>
                    </ul>
                </div>
            </div>
        </article>
        <!-- Conseil 3 -->
        <article id="tip-3" class="safety-tip" tabindex="0">
            <header class="safety-tip-header">
                <div class="safety-tip-image">
                    <img src="{{ $chocolatey->hotelUrl }}habbo-web/assets/web-images/safetytips3_n.png" 
                         alt="Illustration : Résister à la pression sociale" 
                         class="safety-tip-img"
                         loading="lazy"
                         decoding="async">
                </div>
                <h2 class="safety-tip-title">
                    <span class="tip-number" aria-label="Conseil numéro 3">3.</span>
                    Ne cède pas à la pression des autres
                </h2>
            </header>
            <div class="safety-tip-content">
                <p class="safety-tip-text">
                    Que tout le monde fasse quelque chose n'est pas une raison pour toi de le faire si tu n'es pas à l'aise 
                    avec cette idée.
                </p>
                
                <div class="safety-tip-details">
                    <h3 class="safety-tip-subtitle">Rappel important :</h3>
                    <blockquote class="safety-quote">
                        <p>Tu as toujours le droit de dire "non" si quelque chose te met mal à l'aise.</p>
                    </blockquote>
                </div>
            </div>
        </article>
        <!-- Conseil 4 -->
        <article id="tip-4" class="safety-tip" tabindex="0">
            <header class="safety-tip-header">
                <div class="safety-tip-image">
                    <img src="{{ $chocolatey->hotelUrl }}habbo-web/assets/web-images/safetytips4_n.png" 
                         alt="Illustration : Garder les amitiés virtuelles" 
                         class="safety-tip-img"
                         loading="lazy"
                         decoding="async">
                </div>
                <h2 class="safety-tip-title">
                    <span class="tip-number" aria-label="Conseil numéro 4">4.</span>
                    Garde tes copains en pixels !
                </h2>
            </header>
            <div class="safety-tip-content">
                <p class="safety-tip-text">
                    Ne jamais rencontrer des personnes que tu connais uniquement via internet, les gens ne sont pas toujours ceux qu'ils 
                    prétendent être ! Si quelqu'un te demande de le/la rencontrer dans la vraie vie, il vaut mieux dire 
                    "Non merci !" et prévenir un modérateur, tes parents ou un autre adulte de confiance.
                </p>
                
                <div class="safety-tip-details">
                    <h3 class="safety-tip-subtitle">Que faire si quelqu'un veut te rencontrer :</h3>
                    <ol class="safety-steps" role="list">
                        <li role="listitem">Dis poliment "Non merci"</li>
                        <li role="listitem">Préviens un modérateur immédiatement</li>
                        <li role="listitem">Parles-en à tes parents ou un adulte de confiance</li>
                        <li role="listitem">Ne donne aucune information personnelle</li>
                    </ol>
                </div>
            </div>
        </article>
        <!-- Conseil 5 -->
        <article id="tip-5" class="safety-tip" tabindex="0">
            <header class="safety-tip-header">
                <div class="safety-tip-image">
                    <img src="{{ $chocolatey->hotelUrl }}habbo-web/assets/web-images/safetytips5_n.png" 
                         alt="Illustration : Signaler les comportements inappropriés" 
                         class="safety-tip-img"
                         loading="lazy"
                         decoding="async">
                </div>
                <h2 class="safety-tip-title">
                    <span class="tip-number" aria-label="Conseil numéro 5">5.</span>
                    N'aies pas peur de dire les choses !
                </h2>
            </header>
            <div class="safety-tip-content">
                <p class="safety-tip-text">
                    Si quelqu'un te met mal à l'aise ou te fait peur avec des menaces dans Habbo, signale-le immédiatement 
                    à un modérateur en utilisant le bouton d'alerte.
                </p>
                
                <div class="safety-tip-details">
                    <h3 class="safety-tip-subtitle">Comment signaler :</h3>
                    <div class="safety-action" role="region" aria-label="Instructions de signalement">
                        <p><strong>Utilise le bouton d'alerte</strong> disponible dans toutes les conversations pour signaler immédiatement tout comportement inapproprié.</p>
                        <p class="safety-reminder">Signaler n'est pas de la délation - c'est protéger ta sécurité et celle des autres !</p>
                    </div>
                </div>
            </div>
        </article>
        <!-- Conseil 6 -->
        <article id="tip-6" class="safety-tip" tabindex="0">
            <header class="safety-tip-header">
                <div class="safety-tip-image">
                    <img src="{{ $chocolatey->hotelUrl }}habbo-web/assets/web-images/safetytips6_n.png" 
                         alt="Illustration : Attention aux photos partagées" 
                         class="safety-tip-img"
                         loading="lazy"
                         decoding="async">
                </div>
                <h2 class="safety-tip-title">
                    <span class="tip-number" aria-label="Conseil numéro 6">6.</span>
                    Laisse tomber les images
                </h2>
            </header>
            <div class="safety-tip-content">
                <p class="safety-tip-text">
                    Tu n'as aucun contrôle sur tes photos et images webcam une fois que tu les as partagées sur Internet, 
                    tu ne peux plus les récupérer. Elles peuvent être partagées avec n'importe qui, n'importe 
                    où et être utilisées pour t'intimider, te faire du chantage ou te menacer.
                </p>
                
                <div class="safety-tip-details">
                    <h3 class="safety-tip-subtitle">Avant de partager une photo, demande-toi :</h3>
                    <ul class="safety-checklist" role="list">
                        <li role="listitem">Suis-je à l'aise que des inconnus voient cette photo ?</li>
                        <li role="listitem">Cette photo pourrait-elle être utilisée contre moi ?</li>
                        <li role="listitem">Ai-je vraiment besoin de la partager ?</li>
                        <li role="listitem">Que diraient mes parents s'ils la voyaient ?</li>
                    </ul>
                </div>
            </div>
        </article>
        <!-- Conseil 7 -->
        <article id="tip-7" class="safety-tip" tabindex="0">
            <header class="safety-tip-header">
                <div class="safety-tip-image">
                    <img src="{{ $chocolatey->hotelUrl }}habbo-web/assets/web-images/safetytips7_n.png" 
                         alt="Illustration : Naviguer intelligemment sur Internet" 
                         class="safety-tip-img"
                         loading="lazy"
                         decoding="async">
                </div>
                <h2 class="safety-tip-title">
                    <span class="tip-number" aria-label="Conseil numéro 7">7.</span>
                    Sois un surfeur intelligent
                </h2>
            </header>
            <div class="safety-tip-content">
                <p class="safety-tip-text">
                    Les sites Web qui t'offrent des crédits gratuits, des mobis, ou qui font semblant d'être de 
                    nouveaux sites Habbo Hôtel ou des pages du personnel Habbo sont tous des escroqueries dans le but de voler ton 
                    mot de passe. Ne leur donne pas tes coordonnées et ne télécharge jamais des fichiers depuis ces 
                    sites, car ils pourraient être des logiciels espions ou des virus !
                </p>
                
                <div class="safety-tip-details">
                    <h3 class="safety-tip-subtitle">Sites frauduleux à éviter :</h3>
                    <ul class="safety-warning-list" role="list">
                        <li role="listitem">Sites promettant des crédits gratuits</li>
                        <li role="listitem">Faux sites Habbo</li>
                        <li role="listitem">Pages se faisant passer pour le personnel</li>
                        <li role="listitem">Sites demandant tes identifiants</li>
                    </ul>
                    
                    <div class="safety-warning" role="alert">
                        <h4 class="safety-warning-title">⚠️ Attention !</h4>
                        <p>Ne télécharge jamais de fichiers depuis ces sites - ils peuvent contenir des virus !</p>
                    </div>
                </div>
            </div>
        </article>
    </section>
    
    <!-- Conclusion et ressources additionnelles -->
    <footer class="safety-footer">
        <section class="safety-help" aria-labelledby="help-heading">
            <h2 id="help-heading">Besoin d'aide ?</h2>
            <p>Si tu as des questions sur la sécurité en ligne ou si tu rencontres un problème, n'hésite pas à :</p>
            <ul class="help-options" role="list">
                <li role="listitem">Contacter un modérateur dans le jeu</li>
                <li role="listitem">Parler à tes parents ou un adulte de confiance</li>
                <li role="listitem">Consulter notre centre d'aide</li>
            </ul>
        </section>
        
        <!-- Retour vers d'autres pages -->
        <nav class="safety-navigation" aria-label="Navigation vers d'autres pages d'aide">
            <a href="/help" class="btn btn-primary">Centre d'aide</a>
            <a href="/community" class="btn btn-secondary">Retour à la communauté</a>
        </nav>
    </footer>
</main>

<!-- CSS pour l'accessibilité -->
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

.safety-tip {
    margin-bottom: 2rem;
    padding: 1.5rem;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background: #f9f9f9;
}

.safety-tip:focus {
    outline: 2px solid #1e7cf7;
    outline-offset: 2px;
}

.tip-number {
    display: inline-block;
    width: 2rem;
    height: 2rem;
    background: #1e7cf7;
    color: white;
    border-radius: 50%;
    text-align: center;
    line-height: 2rem;
    margin-right: 0.5rem;
    font-weight: bold;
}

.safety-warning {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 4px;
    padding: 1rem;
    margin-top: 1rem;
}

.safety-quote {
    background: #e3f2fd;
    border-left: 4px solid #1e7cf7;
    margin: 1rem 0;
    padding: 1rem;
    font-style: italic;
}

/* Focus improvements for all interactive elements */
a:focus, button:focus, [tabindex]:focus {
    outline: 2px solid #1e7cf7;
    outline-offset: 2px;
}
</style>
