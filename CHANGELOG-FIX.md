# Changelog - Naprawa błędu 500 na świeżej instalacji

## Data: 2026-03-10

### Problem
Na świeżej instalacji (bez pliku .env i bazy danych) strona główna wyrzucała błąd 500:
- `Call to undefined function setting()`
- Aplikacja próbowała załadować theme i ustawienia z bazy przed instalacją

### Rozwiązanie

#### 1. Dodano middleware `CheckInstallation`
**Plik:** `app/Http/Middleware/CheckInstallation.php`

Middleware sprawdza czy aplikacja jest zainstalowana:
- Jeśli NIE → przekierowuje na `/install`
- Jeśli TAK → pozwala na normalną pracę

#### 2. Zaktualizowano `bootstrap/app.php`
Dodano middleware do web group:
```php
$middleware->web(append: [
    \App\Http\Middleware\CheckInstallation::class,
    \App\Http\Middleware\SetLocale::class,
]);
```

#### 3. Zabezpieczono helper `setting()`
**Plik:** `app/helpers.php`

Dodano try-catch i sprawdzanie czy baza jest dostępna:
- Jeśli baza nie jest skonfigurowana → zwraca wartość domyślną
- Jeśli wystąpi błąd połączenia → zwraca wartość domyślną
- Zapobiega błędom 500 przed instalacją

#### 4. Zabezpieczono helper `settings()`
Analogicznie jak `setting()` - zwraca pustą kolekcję jeśli baza niedostępna.

### Pliki do wgrania na VPS

**NOWE:**
1. `app/Http/Middleware/CheckInstallation.php`

**ZAKTUALIZOWANE:**
2. `app/helpers.php` (dodano try-catch)
3. `bootstrap/app.php` (dodano middleware)
4. `composer.json` (autoload helpers)
5. `resources/views/install/index.blade.php` (nowy design)
6. `resources/views/admin/layout.blade.php` (nowy design)
7. `resources/views/admin/login.blade.php` (nowy design)
8. `resources/views/admin/dashboard.blade.php` (nowy design)

### Instrukcje wdrożenia

Zobacz: `VPS-UPLOAD-INSTRUCTIONS.md`

### Jak to działa teraz?

1. Użytkownik wchodzi na `https://demo.novaradio.cloud`
2. Middleware `CheckInstallation` sprawdza czy `.env` istnieje i `APP_INSTALLED=true`
3. Jeśli NIE → przekierowanie na `/install`
4. Użytkownik przechodzi przez instalator
5. Instalator tworzy `.env` z `APP_INSTALLED=true`
6. Po instalacji strona główna działa normalnie

### Testowanie

Po wgraniu plików:
```bash
composer dump-autoload --no-scripts
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

Następnie wejdź na stronę - powinna przekierować na `/install`.

### Design Changes (bonus)

Wszystkie strony (installer, login, admin) używają teraz spójnego design systemu:
- Kolor primary: pomarańczowy `hsl(14, 100%, 50%)`
- Fonty: Roboto Mono + Inter
- Jasne tło zamiast ciemnego
- Branding: NovaRadio**CMS**
