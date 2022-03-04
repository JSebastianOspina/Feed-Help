<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeckJoinRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deck_join_requests', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->foreignId('deck_id')->constrained()->cascadeOnDelete();
            $table->string('twitter_account');
            $table->string('twitter_followers');
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
        Schema::dropIfExists('deck_join_requests');
    }
}
