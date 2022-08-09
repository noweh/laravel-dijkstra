<?php

namespace Noweh\Dijkstra\Services;

use DB;
use Validator;
use Illuminate\Support\Collection;
use Noweh\Dijkstra\Models\Point;

class PointsService implements IPointsService
{
    /**
         * @throws \Exception
     */
    public function createStructure(array $points = [], array $relations = []): Collection
    {
        $validator = Validator::make($points, [
            '*.name' => 'required|string|max:1|distinct',
            '*.x' => 'required|integer',
            '*.y' => 'required|integer'
        ]);
        if ($validator->validate()) {
            // fix a strange behaviour with SQL for INSERT INTO ... ON DUPLICATE KEY UPDATE ...
            DB::statement('ALTER TABLE ' . Point::query()->getQuery()->from . ' AUTO_INCREMENT = 0');
            Point::upsert($points, ['name'], ['x', 'y']);
            DB::statement('ALTER TABLE ' . Point::query()->getQuery()->from . ' AUTO_INCREMENT = 0');

            foreach ($relations as $relation) {
                $this->addRelation($relation);
            }

            return $this->getPoints();
        }

        throw new \Exception($validator->failed(), 500);
    }

    /**
     * @throws \Exception
     */
    public function addPoint(array $data = []): Point
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:1|distinct',
            'x' => 'required|integer',
            'y' => 'required|integer'
        ]);
        if ($validator->validate()) {
            return Point::firstOrCreate($data);
        }
        throw new \Exception($validator->failed(), 500);
    }

    public function removePoint(string $name): bool
    {
        return Point::where('name', $name)->delete();
    }

    public function addRelation(array $relation = []): bool
    {
        $basePoint = Point::where('name', key($relation))->first();
        $relatedPoint = Point::where('name', value($relation))->first();

        if ($basePoint && $relatedPoint &&
            !$basePoint->points->contains($relatedPoint) && !$relatedPoint->points->contains($basePoint)
        ) {
            $basePoint->points()->attach($relatedPoint);
            return true;
        }

        return false;
    }

    public function removeRelation(array $relation = []): bool
    {
        $basePoint = Point::where('name', key($relation))->first();
        $relatedPoint = Point::where('name', value($relation))->first();

        if ($basePoint && $relatedPoint && $basePoint->points->contains($relatedPoint)) {
            $basePoint->points()->detach($relatedPoint);
            return true;
        }

        return false;
    }

    public function getPoints(): Collection
    {
        return Point::with('points')->get();
    }
}
