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
        Schema::dropIfExists('student_profiles');
        Schema::dropIfExists('students');


        $this->down();


        Schema::create('participants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');
            $table->foreign('user_id', 'participants__user')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->on('users')
                ->references('id');

            $table->foreignId('citizenship_id');
            $table->foreign('citizenship_id', 'participants__citizenship')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->on('dir_citizenships')
                ->references('id');

            $table->string('last_name', 255);
            $table->string('first_name', 255);

            $table->enum('sex', ['m', 'f']);
            $table->date('birthdate');
            $table->string('phone', 32);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('participant_edu_level', function (Blueprint $table) {
            $table->foreignId('participant_id');
            $table->foreignId('edu_level_id');

            $table->foreign('participant_id', 'participant_edu_levels__participant')
                ->on('participants')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('edu_level_id', 'participant_edu_levels__edu_level')
                ->on('edu_levels')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->primary(['participant_id','edu_level_id']);
        });

        Schema::create('members', function (Blueprint $table) {

            $table->id();

            $table->foreignId('participant_id');
            $table->foreign('participant_id', 'members__participant')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('participants')
                ->references('id');

            $table->foreignId('profile_id');
            $table->foreign('profile_id', 'members__profile')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->on('profiles')
                ->references('id');

            $table->foreignId('track_id');
            $table->foreign('track_id', 'members__track')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->on('tracks')
                ->references('id');

        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
        Schema::dropIfExists('participant_edu_level');
        Schema::dropIfExists('participants');

    }
};
