<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDijkstraTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('dijkstra_points')) {
            Schema::create('dijkstra_points', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 1)->unique('ndx_dijkstra_points_name');
                $table->integer('x');
                $table->integer('y');
                $table->timestamps();
                $table->unique(['x', 'y'], 'ndx_dijkstra_points_x_y');
            });
        }

        if (!Schema::hasTable('dijkstra_point_related_point')) {
            Schema::create('dijkstra_point_related_point', function (Blueprint $table) {
                $table->integer('point_id')->unsigned()->index('ndx_dijkstra_prp_point_id');
                $table->integer('related_point_id')->unsigned()->index('ndx_dijkstra_prp_related_point_id');
                $table->primary(['point_id','related_point_id'], 'pk_dijkstra_prp_primary');
                $table->foreign('point_id', 'fk_dijkstra_prp_point_id')
                    ->references('id')
                    ->on('dijkstra_points')
                    ->onUpdate('NO ACTION')
                    ->onDelete('CASCADE')
                ;
                $table->foreign('related_point_id', 'fk_dijkstra_prp_related_point_id')
                    ->references('id')
                    ->on('dijkstra_points')
                    ->onUpdate('NO ACTION')
                    ->onDelete('CASCADE')
                ;
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dijkstra_point_related_point');
        Schema::dropIfExists('dijkstra_points');
    }
}
