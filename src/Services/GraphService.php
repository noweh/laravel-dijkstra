<?php

namespace Noweh\Dijkstra\Services;

use Illuminate\Database\Eloquent\Model;
use Noweh\Dijkstra\Models\Point;

class GraphService implements IGraphService
{
    public function drawGraph(Model $pointFrom = null, Model $pointTo = null): void
    {
        $shortestPath = [];
        if ($pointFrom && $pointTo) {
            echo '<h1>Shortest path between ' . $pointFrom->name . ' and ' . $pointTo->name . '</h1>';
            $shortestPath = $this->findShortestPath($pointFrom, $pointTo);
        }

        $canvas = imagecreatetruecolor(500, 500);

        $white = imagecolorallocate($canvas, 0xff, 0xff, 0xff); // #ffffff
        $black = imagecolorallocate($canvas, 64, 64, 64);
        $green = imagecolorallocate($canvas, 0, 204, 102);

        imagefill($canvas, 0, 0, $white);

        // Draw Points & lines
        foreach (Point::all() as $point) {
            foreach ($point->points as $related) {
                $isAShortestPath = false;
                foreach ($shortestPath as $path) {
                    if (($path['from'] === $point->name && $path['to'] === $related->name) || ($path['from'] === $related->name && $path['to'] === $point->name)) {
                        $isAShortestPath = true;
                    }
                }
                imagesetthickness($canvas, $isAShortestPath ? 3 : 1);
                imageline($canvas, $point->x, $point->y, $related->x, $related->y, $isAShortestPath ? $green : $black);
            }
            imagefilledellipse($canvas, $point->x, $point->y, 30, 30, $black);
            imagettftext($canvas, 20, 0, $point->x-9, $point->y+10, $white, public_path('fonts/arial.ttf'), $point->name);
        }

        imagejpeg($canvas, "medias/graph.jpg");
        imagedestroy($canvas);

        echo '<p><img src="' . url('medias/graph.jpg') . '" /></p>';
    }

    public function findShortestPath(Model $pointFrom, Model $pointTo): array
    {
        $points = [];
        foreach (Point::all() as $point) {
            // If this $point is the $pointFrom, total_distance is 0
            $points[$point->name] = [
                'total_distance' => $point->x === $pointFrom->x && $point->y === $pointFrom->y ? 0 : PHP_INT_MAX,
                'point' => $point,
                'previous' => [],
                'passed' => false
            ];
        }

        $points = $this->processItem($pointFrom, $pointTo, $points);

        $referenceName = $pointTo->name;

        $paths = [];
        while ($points[$referenceName]['point']->name !== $pointFrom->name) {
            $path = ['from' => null, 'to' => null];
            $path['to'] = $points[$referenceName]['point']->name;
            $referenceName = $points[$referenceName]['previous']->name;
            $path['from'] = $referenceName;
            $paths[] = $path;
        }

        return array_reverse($paths);
    }

    private function processItem(Model $parent, Model $pointTo, array $points): array
    {
        // If the first item is the pointTo item
        if ($parent->name === $pointTo->name) {
            return $points;
        }

        // This point has been passed by
        $points[$parent->name]['passed'] = true;

        foreach ($parent->points as $child) {
            // Dijkstra Algo
            // If child not traversed yet and parent total_distance + distance parent => child < child total_distance
            if (!$points[$child->name]['passed'] &&
                (
                    $points[$parent->name]['total_distance'] + static::findDistance($parent, $child) <
                    $points[$child->name]['total_distance']
                )
            ) {
                $points[$child->name]['total_distance'] =
                    $points[$parent->name]['total_distance'] + static::findDistance($parent, $child);
                $points[$child->name]['previous'] = $parent;
            }
        }

        // Order points to have first the !passed Point with the smallest total_distance
        uasort($points, static function (array $a, array $b): int {
            if ($a['passed']) { return 1; }
            if ($b['passed']) { return -1; }
            return ($a['total_distance'] > $b['total_distance']) ? 1 : -1;
        });

        // If there is still a Point !passed
        if (($nextParent = reset($points)) && !$nextParent['passed'] && $nextParent['total_distance'] !== PHP_INT_MAX) {
            return $this->processItem($nextParent['point'], $pointTo, $points);
        }

        return $points;
    }

    public static function findDistance(Model $point1, Model $point2): float
    {
        // square root of ((x2-x1)^2 + (y2-y1)^2)
        return sqrt(bcpow($point2->x - $point1->x, 2) + bcpow($point2->y - $point1->y, 2));
    }
}
