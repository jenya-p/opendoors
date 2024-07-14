<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('profile_files');
        Schema::dropIfExists('profile_file_types');

        Schema::create('profile_file_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('track_id')->nullable();
            $table->foreign('track_id', 'profile_file_types__track')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('tracks')
                ->references('id');

            $table->enum('type', ['materials', 'results'])->default('materials');

            $table->unsignedInteger('order')->default(0);

            $table->string('name', 256)->nullable();
            $table->string('name_en', 256)->nullable();

            $table->text('summary')->nullable();
            $table->text('summary_en')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('profile_files', function (Blueprint $table) {
            $table->id();

            $table->enum('status', ['active', 'disabled'])->default('active');

            $table->foreignId('type_id');
            $table->foreign('type_id', 'profile_files__type')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('profile_file_types')
                ->references('id');

            $table->foreignId('profile_id');
            $table->foreign('profile_id', 'profile_files__profile')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('profiles')
                ->references('id');



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_files');
        Schema::dropIfExists('profile_file_types');
    }
};
