<?php

namespace Noweh\Dijkstra\Services;

use Noweh\Dijkstra\Models\Point;

class GraphService implements IGraphService
{
    public function drawGraph()
    {
        $canvas = imagecreatetruecolor(500, 500);

        $white = imagecolorallocate($canvas, 0xff, 0xff, 0xff); // #ffffff
        $black = imagecolorallocate($canvas, 64, 64, 64);

        imagefill($canvas, 0, 0, $white);

        // Draw Points & lines
        foreach (Point::all() as $point) {
            foreach ($point->points as $related) {
                $x1 = $point->x;
                $x2 = $related->x;
                if ($x1 > $x2) {
                    $x1 -= 5;
                    $x2 += 5;
                } elseif ($x2 > $x1) {
                    $x1 += 5;
                    $x2 -= 5;
                }

                $y1 = $point->y;
                $y2 = $related->y;
                if ($y1 > $y2) {
                    $y1 -= 5;
                    $y2 += 5;
                } elseif ($y2 > $y1) {
                    $y1 += 5;
                    $y2 -= 5;
                }

                imageline($canvas, $x1, $y1, $x2, $y2, $black);
                //imageline($canvas, $point->x, $point->y, $related->x, $related->y, $black);
            }
            imagefilledellipse($canvas, $point->x, $point->y, 30, 30, $black);
            imagettftext($canvas, 20, 0, $point->x-9, $point->y+10, $white, public_path('fonts/arial.ttf'), $point->name);


        }

        imagejpeg($canvas, "medias/graph.jpg");
        imagedestroy($canvas);

        echo '<p><img src="' . url('medias/graph.jpg') . '" /></p>';
    }
}
