# interNETDASHboard
Basic RSS/Atom aggregator using Laravel with Livewire/Volt

![Dashboard Screenshot](/docs/netdash-home.png?raw=true)

## Initial Setup
Make sure you have PHP 8.1 or greater installed. You can also use Laravel Sail.

Install composer dependencies
```
composer install
```

Use the built-in PHP server
```
php artisan serve
```

There is some basic data seeding to get you going
```
php artisan migrate:fresh --seed
```

After which you can fetch the articles with
```
php artisan app:get-feeds
```

Run the frontend dev server (Requires node >18 on your local)
```
npm run dev
```

Visit `http://localhost` in your browser, and log in with `test@example.com` and `password`

## Testing with PEST
To run the test suite
```
php artisan test
```

## Type checking with PHPStan
Run the static analysis
```
php artisan composer sa
```

Generate updated baseline
```
php artisan composer gb
```

## Run full quality assurance (pint, static analysis, testing)
```
php artisan composer qa
```

## IDE Setup (VSCode)
Install the recommended extensions (`.vscode/extensions.json`)

The `.vscode/settings.json` file will do the rest causing PHP files to be formatted on save.

The paths may need to be updated for the `better-pest` extension to work. The idea is for the `Better Pest: run` VSCode command shortcut to work out the box.
