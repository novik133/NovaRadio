<?php

return [
    'title' => 'Instalator NovaRadio',
    'steps' => [
        'license' => 'Licencja',
        'requirements' => 'Wymagania',
        'database' => 'Baza danych',
        'admin' => 'Administrator',
        'streaming' => 'Streaming',
    ],

    'license_title' => 'Umowa licencyjna',
    'license_subtitle' => 'Przeczytaj i zaakceptuj umowę licencyjną, aby kontynuować.',
    'accept_license' => 'Akceptuję umowę licencyjną',
    'must_accept' => 'Musisz zaakceptować umowę licencyjną, aby kontynuować.',

    'requirements_title' => 'Wymagania systemowe',
    'requirements_subtitle' => 'Upewnij się, że Twój serwer spełnia wszystkie wymagania.',
    'requirements_met' => 'Wszystkie wymagania spełnione!',
    'requirements_not_met' => 'Niektóre wymagania nie są spełnione. Napraw je przed kontynuowaniem.',
    'installed' => 'Zainstalowane',
    'missing' => 'Brakujące',

    'database_title' => 'Konfiguracja bazy danych',
    'database_subtitle' => 'Wprowadź dane połączenia z bazą danych.',
    'db_host' => 'Host bazy danych',
    'db_port' => 'Port',
    'db_name' => 'Nazwa bazy danych',
    'db_username' => 'Nazwa użytkownika',
    'db_password' => 'Hasło',

    'admin_title' => 'Konto administratora',
    'admin_subtitle' => 'Utwórz początkowe konto administratora.',
    'admin_name' => 'Imię i nazwisko',
    'admin_email' => 'Adres email',
    'admin_password' => 'Hasło',
    'admin_password_confirm' => 'Potwierdź hasło',
    'password_min' => 'Minimum 8 znaków',
    'passwords_must_match' => 'Hasła muszą się zgadzać',

    'streaming_title' => 'Konfiguracja streamingu',
    'streaming_subtitle' => 'Skonfiguruj integrację z AzuraCast (opcjonalne).',
    'azuracast_url' => 'URL AzuraCast',
    'azuracast_url_placeholder' => 'https://twoja-instancja-azuracast.com',
    'azuracast_api_key' => 'Klucz API AzuraCast',
    'stream_url' => 'Bezpośredni URL strumienia (zapasowy)',
    'stream_url_placeholder' => 'https://twoj-stream.com/radio.mp3',
    'stream_url_hint' => 'Używany, gdy AzuraCast jest niedostępny',
    'skip_streaming' => 'Streaming możesz skonfigurować później w ustawieniach panelu.',

    'next' => 'Dalej',
    'previous' => 'Wstecz',
    'install' => 'Zainstaluj NovaRadio',
    'installing' => 'Instalowanie...',

    'install_success' => 'Instalacja zakończona! Zaloguj się danymi administratora.',
    'install_failed' => 'Instalacja nie powiodła się: :error',
];
