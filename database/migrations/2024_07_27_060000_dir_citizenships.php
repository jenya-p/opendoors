<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('dir_citizenships', function (Blueprint $table) {
             $table->id();

             $table->string('name', 256);
             $table->string('name_en', 256)->nullable();
             $table->string('code', 8);

             $table->timestamps();
             $table->softDeletes();
         });
    }

    public function down(): void
    {
        Schema::dropIfExists('dir_citizenships');
    }
};
