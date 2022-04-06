<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_children', function (Blueprint $table) {
            $table->integer('parent_id');
            $table->integer('child_id');
            $table->primary(['parent_id', 'child_id']);
            //$table->foreign('parent_id')->references('id')->on('items')->cascadeOnDelete();
            //$table->foreign('child_id')->references('id')->on('items')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_children');
    }
}
