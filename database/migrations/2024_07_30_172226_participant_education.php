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

        Schema::table('edu_levels', function (Blueprint $table){
            $table->boolean('diploma')->default(false)->after('multiple');
        });

        \App\Models\EduLevel::whereIn('id', [13,15,16])->update(['diploma' => true]);


        Schema::create('participant_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id');
            $table->foreignId('edu_level_id');

            $table->foreign('participant_id', 'participant_educations__participant')
                ->on('participants')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('edu_level_id', 'participant_educations__edu_level')
                ->on('edu_levels')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->timestamps();
            $table->softDeletes();

        });


        Schema::table('members', function (Blueprint $table){
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('edu_levels', function (Blueprint $table){
            $table->dropColumn('diploma');
        });

        Schema::dropIfExists('participant_educations');

        Schema::table('members', function (Blueprint $table){
            $table->dropTimestamps();
            $table->dropSoftDeletes();
        });

    }
};
