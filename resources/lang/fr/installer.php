<?php

return [
    'title' => 'Installateur NovaRadio',
    'steps' => [
        'license' => 'Licence',
        'requirements' => 'Prérequis',
        'database' => 'Base de données',
        'admin' => 'Administrateur',
        'streaming' => 'Streaming',
    ],

    'license_title' => 'Accord de licence',
    'license_subtitle' => 'Veuillez lire et accepter l\'accord de licence pour continuer.',
    'accept_license' => 'J\'accepte l\'accord de licence',
    'must_accept' => 'Vous devez accepter l\'accord de licence pour continuer.',

    'requirements_title' => 'Configuration requise',
    'requirements_subtitle' => 'Assurez-vous que votre serveur répond à toutes les exigences.',
    'requirements_met' => 'Toutes les exigences sont satisfaites !',
    'requirements_not_met' => 'Certaines exigences ne sont pas satisfaites. Corrigez-les avant de continuer.',
    'installed' => 'Installé',
    'missing' => 'Manquant',

    'database_title' => 'Configuration de la base de données',
    'database_subtitle' => 'Entrez les informations de connexion à la base de données.',
    'db_host' => 'Hôte de la base de données',
    'db_port' => 'Port',
    'db_name' => 'Nom de la base de données',
    'db_username' => 'Nom d\'utilisateur',
    'db_password' => 'Mot de passe',

    'admin_title' => 'Compte administrateur',
    'admin_subtitle' => 'Créez le compte administrateur initial.',
    'admin_name' => 'Nom complet',
    'admin_email' => 'Adresse email',
    'admin_password' => 'Mot de passe',
    'admin_password_confirm' => 'Confirmer le mot de passe',
    'password_min' => 'Minimum 8 caractères',
    'passwords_must_match' => 'Les mots de passe doivent correspondre',

    'streaming_title' => 'Configuration du streaming',
    'streaming_subtitle' => 'Configurez l\'intégration AzuraCast (optionnel).',
    'azuracast_url' => 'URL d\'AzuraCast',
    'azuracast_url_placeholder' => 'https://votre-instance-azuracast.com',
    'azuracast_api_key' => 'Clé API AzuraCast',
    'stream_url' => 'URL du flux direct (secours)',
    'stream_url_placeholder' => 'https://votre-flux.com/radio.mp3',
    'stream_url_hint' => 'Utilisé si AzuraCast n\'est pas disponible',
    'skip_streaming' => 'Vous pouvez configurer le streaming plus tard dans les paramètres.',

    'next' => 'Suivant',
    'previous' => 'Précédent',
    'install' => 'Installer NovaRadio',
    'installing' => 'Installation...',

    'install_success' => 'Installation terminée ! Connectez-vous avec vos identifiants administrateur.',
    'install_failed' => 'L\'installation a échoué : :error',
];
