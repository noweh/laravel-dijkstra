<?php

namespace Noweh\Dijkstra\Services;

use Illuminate\Support\Collection;
use Noweh\Dijkstra\Models\Point;

interface IPointsService
{
    public function createStructure(array $points, array $relations): collection;
    public function addPoint(array $data = []): Point;
    public function removePoint(string $name): bool;
    public function addRelation(array $relation = []): bool;
    public function removeRelation(array $relation = []): bool;
    public function getPoints(): Collection;
}
