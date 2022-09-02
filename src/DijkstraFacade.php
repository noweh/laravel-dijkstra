<?php

namespace Noweh\Dijkstra;

use Illuminate\Support\Facades\Facade;

class DijkstraFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Dijkstra';
    }
}
