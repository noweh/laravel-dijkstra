<?php

namespace Noweh\Dijkstra;

use Illuminate\Support\ServiceProvider;
use Noweh\Dijkstra\Services\GraphService;
use Noweh\Dijkstra\Services\IGraphService;
use Noweh\Dijkstra\Services\IPointsService;
use Noweh\Dijkstra\Services\PointsService;

class DijkstraServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(IGraphService::class, function () {
            return new GraphService();
        });

        $this->app->bind(IPointsService::class, function () {
            return new PointsService();
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            if (!class_exists('CreateDijkstraTables')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_dijkstra_tables.php.stub' =>
                        database_path('migrations/' . date('Y_m_d_His', time()) . '_create_dijkstra_tables.php')
                ], 'migrations');
            }

            if (!file_exists(public_path('fonts/arial.ttf'))) {
                $this->publishes([
                    __DIR__ . '/../resources/fonts/Arial.ttf' =>
                        public_path('fonts/arial.ttf')
                ], 'migrations');
            }
        }
    }
}
