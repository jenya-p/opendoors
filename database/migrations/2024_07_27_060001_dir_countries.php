<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {

        $this->down();

        Schema::create('dir_regions', function (Blueprint $table) {
            $table->id();

            $table->string('name', 256);
            $table->string('name_en', 256)->nullable();
            $table->string('code', 16);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('dir_countries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('region_id')->nullable();
            $table->foreign('region_id', 'dir_countries__region')
                ->on('dir_regions')
                ->references('id')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('name', 256);
            $table->string('name_en', 256)->nullable();
            $table->string('code', 8);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('dir_countries');
        Schema::dropIfExists('dir_regions');
    }
};
