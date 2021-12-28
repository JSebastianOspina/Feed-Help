<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('icon')->nullable();
            $table->string('owner_name');
            $table->tinyInteger('rt_number');
            $table->smallInteger('delete_minutes');
            $table->string('description');
            $table->integer('followers')->default(0);
            $table->string('whatsapp_group_url')->nullable();
            $table->boolean('enabled')->default(true);
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
        Schema::dropIfExists('decks');
    }
}
