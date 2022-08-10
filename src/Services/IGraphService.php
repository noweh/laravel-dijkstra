<?php

namespace Noweh\Dijkstra\Services;

use Illuminate\Database\Eloquent\Model;

interface IGraphService
{
    public function drawGraph(Model $pointFrom = null, Model $pointTo = null): void;
    public function findShortestPath(Model $pointFrom, Model $pointTo): array;
    public static function findDistance(Model $point1, Model $point2): float;
}
