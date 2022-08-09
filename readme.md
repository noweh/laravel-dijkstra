## Installation

First you need to add the component to your composer.json

```
composer require noweh/laravel-dijkstra
```

Update your packages with *composer update* or install with *composer install*.

Laravel uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

### Laravel without auto-discovery

After updating composer, add the ServiceProvider to the providers array in config/app.php

    Noweh\Dijkstra\DijkstraServiceProvider::class,

### Migration

The migrations of this package are publishable under the "migrations" tag via:

    php artisan vendor:publish --provider="Noweh\Dijkstra\DijkstraServiceProvider" --tag="migrations"

## Usage

### Operations with Points
```php
    <?php

    /** @var IPointsService $pointsService */
    $pointsService = app(\Noweh\Dijkstra\Services\IPointsService::class);

    // Create all points
    $pointsService->createStructure([
        ['name' => 'A', 'x' => 50, 'y' => 20],
        ['name' => 'B', 'x' => 20, 'y' => 28],
        ['name' => 'C', 'x' => 37, 'y' => 36],
        ['name' => 'D', 'x' => 47, 'y' => 50]
    ], [
        ['A' => 'B'],
        ['A' => 'C'],
        ['B' => 'D'],
        ['C' => 'D']
    ]);

    // add One point
    $pointsService->addPoint(['name' => 'E', 'x' => 60, 'y' => 30]);

    // Remove one point
    $pointsService->removePoint('B');

    // Add relation
    $pointsService->addRelation(['E' => 'B']);

    // Remove relation
    $pointsService->removeRelation(['A' => 'B']);

    // Retrieve all points
    dump($pointsService->getPoints());
```
 