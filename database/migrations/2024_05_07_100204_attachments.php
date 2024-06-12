<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {

            $table->increments('id');

            $table->string('item_type', 32);
            $table->integer('item_id');
            $table->string('file', 256);
            $table->string('name', 256);
            $table->timestamps();
            $table->softDeletes();

        });
    }


    public function down()
    {
        Schema::dropIfExists('attachments');
    }
};
