<?php

namespace Noweh\Dijkstra;

class Dijkstra
{
	public function pointsService()
	{
		return app(\Noweh\Dijkstra\Services\IPointsService::class);
	}

	public function graphService()
	{
		return app(\Noweh\Dijkstra\Services\IGraphService::class);
	}
}
