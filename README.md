# NetDash
Basic RSS/Atom aggregator using Laravel with Livewire/Volt

## Initial Setup
There is some basic data seeding to get you going
```
sail artisan migrate:fresh --seed
```

After which you can fetch the articles with
```
sail artisan app:get-feeds
```