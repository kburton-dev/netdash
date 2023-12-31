# interNETDASHboard
Basic RSS/Atom aggregator using Laravel with Livewire/Volt

## Initial Setup with Laravel Sail
Install composer dependencies
```
env WWWUSER=${UID} WWWGROUP=${id -g} docker compose run laravel.test composer install
```
The above command will build the Docker image, so it may take some time

Starting the application and services
```
vendor/bin/sail up
```

There is some basic data seeding to get you going
```
vendor/bin/sail artisan migrate:fresh --seed
```

After which you can fetch the articles with
```
vendor/bin/sail artisan app:get-feeds
```

Run the frontend dev server (Requires node >18 on your local)
```
npm run dev
```

Visit `http://localhost` in your browser, and log in with `test@example.com` and `password`

## Testing with PEST
To run the test suite
```
sail test
```

## Type checking with PHPStan
Run the static analysis
```
sail composer check-types
```

Generate updated baseline
```
sail composer generate-baseline
```

## IDE Setup (VSCode)
Install the recommended extensions (`.vscode/extensions.json`)

The `.vscode/settings.json` file will do the rest causing PHP files to be formatted on save.

The paths may need to be updated for the `better-pest` extension to work. The idea is for the `Better Pest: run` VSCode command shortcut to work out the box.