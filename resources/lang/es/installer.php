<?php

return [
    'title' => 'Instalador de NovaRadio',
    'steps' => [
        'license' => 'Licencia',
        'requirements' => 'Requisitos',
        'database' => 'Base de datos',
        'admin' => 'Administrador',
        'streaming' => 'Streaming',
    ],

    'license_title' => 'Acuerdo de licencia',
    'license_subtitle' => 'Lee y acepta el acuerdo de licencia para continuar.',
    'accept_license' => 'Acepto el acuerdo de licencia',
    'must_accept' => 'Debes aceptar el acuerdo de licencia para continuar.',

    'requirements_title' => 'Requisitos del sistema',
    'requirements_subtitle' => 'Asegúrate de que tu servidor cumple todos los requisitos.',
    'requirements_met' => '¡Todos los requisitos cumplidos!',
    'requirements_not_met' => 'Algunos requisitos no se cumplen. Corrígelos antes de continuar.',
    'installed' => 'Instalado',
    'missing' => 'Faltante',

    'database_title' => 'Configuración de base de datos',
    'database_subtitle' => 'Introduce los datos de conexión a la base de datos.',
    'db_host' => 'Host de base de datos',
    'db_port' => 'Puerto',
    'db_name' => 'Nombre de la base de datos',
    'db_username' => 'Usuario',
    'db_password' => 'Contraseña',

    'admin_title' => 'Cuenta de administrador',
    'admin_subtitle' => 'Crea la cuenta inicial de administrador.',
    'admin_name' => 'Nombre completo',
    'admin_email' => 'Correo electrónico',
    'admin_password' => 'Contraseña',
    'admin_password_confirm' => 'Confirmar contraseña',
    'password_min' => 'Mínimo 8 caracteres',
    'passwords_must_match' => 'Las contraseñas deben coincidir',

    'streaming_title' => 'Configuración de streaming',
    'streaming_subtitle' => 'Configura la integración con AzuraCast (opcional).',
    'azuracast_url' => 'URL de AzuraCast',
    'azuracast_url_placeholder' => 'https://tu-instancia-azuracast.com',
    'azuracast_api_key' => 'Clave API de AzuraCast',
    'stream_url' => 'URL de stream directo (respaldo)',
    'stream_url_placeholder' => 'https://tu-stream.com/radio.mp3',
    'stream_url_hint' => 'Se usa si AzuraCast no está disponible',
    'skip_streaming' => 'Puedes configurar el streaming más tarde en la configuración del panel.',

    'next' => 'Siguiente',
    'previous' => 'Anterior',
    'install' => 'Instalar NovaRadio',
    'installing' => 'Instalando...',

    'install_success' => '¡Instalación completada! Inicia sesión con tus credenciales de administrador.',
    'install_failed' => 'La instalación falló: :error',
];
