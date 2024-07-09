<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {

        Schema::table('universities', function(Blueprint $table){
            $table->string('url_en', 512)->nullable(true)->after('url');
        });

        Schema::create('study_programs', function (Blueprint $table) {
            $table->id();
            $table->enum('locale', ['ru', 'en'])->default('ru');
            $table->string('name');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::dropIfExists('university_profiles');


        Schema::create('university_profiles', function (Blueprint $table) {

            $table->foreignId('profile_id');
            $table->foreign('profile_id', 'university_profiles__profile')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('profiles')
                ->references('id');


            $table->foreignId('program_id');
            $table->foreign('program_id', 'university_profiles__program')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('study_programs')
                ->references('id');

            $table->foreignId('university_id');
            $table->foreign('university_id', 'university_profiles__university')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('universities')
                ->references('id');

            $table->primary([
                'profile_id',
                'program_id',
                'university_id',
            ]);

        });


        Schema::table('profiles', function (Blueprint $t) {
            $t->foreignId('coordinator_id')->after('order')->nullable();
            $t->foreign('coordinator_id', 'profiles__coordinator')
                ->nullOnDelete()
                ->cascadeOnUpdate()
                ->on('universities')
                ->references('id');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('universities', function(Blueprint $table){
            $table->dropColumn('url_en');
        });

        Schema::dropIfExists('university_profiles');

        Schema::dropIfExists('study_programs');

        Schema::table('profiles', function (Blueprint $t) {

            $t->dropForeign('profiles__coordinator');
            $t->dropColumn('coordinator_id');


        });


    }
};
