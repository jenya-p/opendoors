<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public $withinTransaction = true;

    public function up(): void {


        Schema::dropIfExists('student_profiles');
        Schema::dropIfExists('students');
        Schema::dropIfExists('university_users');
        Schema::dropIfExists('user_admins');
        Schema::dropIfExists('university_profiles');
        Schema::dropIfExists('stages');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('tracks');
        Schema::dropIfExists('edu_levels');
        Schema::dropIfExists('universities');

        Schema::create('edu_levels', function (Blueprint $table) {
            $table->id();

            $table->string('name', 256);
            $table->string('name_en', 256)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('universities', function (Blueprint $table) {
            $table->id();

            $table->string('name', 256);
            $table->string('name_en', 256)->nullable();

            $table->string('url', 512)->nullable(true);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tracks', function (Blueprint $table) {
            $table->id();

            $table->string('name', 256);
            $table->string('name_en', 256)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order');
            $table->string('name');
            $table->string('name_en')->nullable();

            $table->string('icon', 16)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('stages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('profile_id');
            $table->foreign('profile_id', 'stages__profile')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->on('profiles')
                ->references('id');

            $table->foreignId('track_id');
            $table->foreign('track_id', 'stages__track')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->on('tracks')
                ->references('id');

            $table->unsignedInteger('order');

            $table->enum('type', ['portfolio', 'test', 'interview']);

            $table->json('settings')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('university_profiles', function (Blueprint $table) {

            $table->foreignId('profile_id');
            $table->foreign('profile_id', 'university_profiles__profile')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('profiles')
                ->references('id');

            $table->foreignId('track_id');
            $table->foreign('track_id', 'university_profiles__track')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('tracks')
                ->references('id');

            $table->foreignId('university_id');
            $table->foreign('university_id', 'university_profiles__university')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('universities')
                ->references('id');

            $table->primary(['profile_id',
                'track_id',
                'university_id',]);

        });


        Schema::create('user_admins', function (Blueprint $table) {
            $table->id();
            $table->foreign('id', 'user_admins__user')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('users')
                ->references('id');

            $table->json('roles');
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('university_users', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');
            $table->foreign('user_id', 'university_users__user')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('users')
                ->references('id');

            $table->foreignId('university_id');
            $table->foreign('university_id', 'university_users__university')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('universities')
                ->references('id');

            $table->json('roles')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');
            $table->foreign('user_id', 'students__user')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->on('users')
                ->references('id');

            $table->foreignId('edu_level_id');
            $table->foreign('edu_level_id', 'students__edu_level')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->on('edu_levels')
                ->references('id');

            $table->string('citizenship');

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('student_profiles', function (Blueprint $table) {

            $table->foreignId('student_id');
            $table->foreign('student_id', 'student_profiles__student')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('students')
                ->references('id');

            $table->foreignId('profile_id');
            $table->foreign('profile_id', 'student_profiles__profile')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->on('profiles')
                ->references('id');

            $table->foreignId('track_id');
            $table->foreign('track_id', 'student_profiles__track')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->on('tracks')
                ->references('id');

            $table->primary([
               'student_id','profile_id','track_id',
            ]);

        });


    }

    public function down(): void {

        Schema::dropIfExists('student_profiles');
        Schema::dropIfExists('students');
        Schema::dropIfExists('university_users');
        Schema::dropIfExists('user_admins');
        Schema::dropIfExists('university_profiles');
        Schema::dropIfExists('stages');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('tracks');
        Schema::dropIfExists('edu_levels');
        Schema::dropIfExists('universities');





    }
};
