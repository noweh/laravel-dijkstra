<?php

namespace Noweh\Dijkstra\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    public $table = "dijkstra_points";

    protected $fillable = ['name', 'x', 'y'];

    public function points(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            __CLASS__,
            'dijkstra_point_related_point',
            'point_id',
            'related_point_id'
        );
    }
}
