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

        Schema::dropIfExists('participant_education');
        Schema::create('participant_degrees', function (Blueprint $table) {

            $table->id();
            $table->foreignId('participant_id');
            $table->foreignId('edu_level_id');

            $table->foreign('participant_id', 'participant_degrees__participant')
                ->on('participants')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('edu_level_id', 'participant_degrees__edu_level')
                ->on('edu_levels')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('country_id');
            $table->foreign('country_id', 'participant_degrees__country')
                ->restrictOnDelete()->cascadeOnUpdate()
                ->on('dir_countries')
                ->references('id');

            $table->string('name', 512);
            $table->integer('graduation_year');
            $table->string('diploma_title', 512)->nullable();
            $table->boolean('is_study_russian');
            $table->boolean('is_study_english');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('dir_knowledge_areas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('parent_id')->nullable();
            $table->integer('level')->default(0);

            $table->string('name', 256);
            $table->string('name_en', 256)->nullable();
            $table->string('code', 16);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('profile_areas', function (Blueprint $table) {
            $table->foreignId('profile_id');
            $table->foreignId('area_id');

            $table->foreign('profile_id', 'profile_knowledge_areas__profile')
                ->on('profiles')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('area_id', 'profile_knowledge_areas__area')
                ->on('dir_knowledge_areas')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->primary(['profile_id','area_id']);
        });

        Schema::create('participant_statements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('member_id');
            $table->foreign('member_id')
                ->restrictOnDelete()->cascadeOnUpdate()
                ->on('members')
                ->references('id');

            $table->text('interests')->nullable();
            $table->text('goals')->nullable();
            $table->text('achievements')->nullable();
            $table->text('motive')->nullable();
            $table->text('country')->nullable();


            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('participant_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('statement_id');
            $table->foreignId('area_id');

            $table->foreign('statement_id', 'participant_interests__statement')
                ->on('participant_statements')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('area_id', 'participant_interests__area')
                ->on('dir_knowledge_areas')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_degrees');
        Schema::dropIfExists('participant_areas');
        Schema::dropIfExists('participant_statements');
        Schema::dropIfExists('profile_areas');

        Schema::dropIfExists('dir_knowledge_areas');
    }
};
