<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {

        Schema::create('dir_achievements', function(Blueprint $t){
            $t->id();
            $t->enum('status', ['active', 'disabled'])->default('active');
            $t->unsignedInteger('order')->default(0);

            $t->string('key', 36);
            $t->string('name', 256);
            $t->string('name_en', 256);
            $t->string('short_name', 128)->nullable();

            $t->boolean('single')->default(false);
            $t->boolean('required')->default(false);

            $t->timestamps();
            $t->softDeletes();

        });


        Schema::create('participant_achievements', function(Blueprint $table){
            $table->id();
            $table->foreignId('participant_id');
            $table->foreignId('type_id');

            $table->foreign('participant_id', 'participant_achievements__participant')
                ->on('participants')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('type_id', 'participant_achievements__type')
                ->on('dir_achievements')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('name', 1024)->nullable();

            $table->json('content');

            $table->timestamps();
            $table->softDeletes();
        });

    }


    public function down(): void {
        Schema::dropIfExists('participant_achievements');
        Schema::dropIfExists('dir_achievements');

    }
};
