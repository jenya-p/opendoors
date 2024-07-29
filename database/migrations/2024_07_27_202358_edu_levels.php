<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::table('edu_levels', function (Blueprint $table){
            $table->enum('status', ['active', 'disabled'])->after('id')->default('active');
            $table->unsignedInteger('order')->default(0)->after('status');
            $table->boolean('multiple')->default(false)->after('name_en');
        });

        Schema::create('track_edu_levels', function (Blueprint $table){
            $table->foreignId('track_id');
            $table->foreignId('edu_level_id');

            $table->foreign('track_id', 'track_edu_levels__track')
                ->on('tracks')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('edu_level_id', 'dtrack_edu_levels__edu_level')
                ->on('edu_levels')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->primary(['track_id','edu_level_id']);

        });


        DB::statement('INSERT INTO `track_edu_levels` (`track_id`, `edu_level_id`) VALUES (17, 9);');
        DB::statement('INSERT INTO `track_edu_levels` (`track_id`, `edu_level_id`) VALUES (18, 9);');
        DB::statement('INSERT INTO `track_edu_levels` (`track_id`, `edu_level_id`) VALUES (19, 9);');
        DB::statement('INSERT INTO `track_edu_levels` (`track_id`, `edu_level_id`) VALUES (17, 10);');
        DB::statement('INSERT INTO `track_edu_levels` (`track_id`, `edu_level_id`) VALUES (18, 10);');
        DB::statement('INSERT INTO `track_edu_levels` (`track_id`, `edu_level_id`) VALUES (19, 10);');
        DB::statement('INSERT INTO `track_edu_levels` (`track_id`, `edu_level_id`) VALUES (17, 11);');
        DB::statement('INSERT INTO `track_edu_levels` (`track_id`, `edu_level_id`) VALUES (18, 11);');
        DB::statement('INSERT INTO `track_edu_levels` (`track_id`, `edu_level_id`) VALUES (19, 11);');
        DB::statement('INSERT INTO `track_edu_levels` (`track_id`, `edu_level_id`) VALUES (17, 12);');
        DB::statement('INSERT INTO `track_edu_levels` (`track_id`, `edu_level_id`) VALUES (18, 12);');
        DB::statement('INSERT INTO `track_edu_levels` (`track_id`, `edu_level_id`) VALUES (19, 12);');
        DB::statement('INSERT INTO `track_edu_levels` (`track_id`, `edu_level_id`) VALUES (18, 13);');
        DB::statement('INSERT INTO `track_edu_levels` (`track_id`, `edu_level_id`) VALUES (19, 13);');
        DB::statement('INSERT INTO `track_edu_levels` (`track_id`, `edu_level_id`) VALUES (18, 14);');
        DB::statement('INSERT INTO `track_edu_levels` (`track_id`, `edu_level_id`) VALUES (19, 14);');
        DB::statement('INSERT INTO `track_edu_levels` (`track_id`, `edu_level_id`) VALUES (19, 15);');


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('edu_levels', function (Blueprint $table){
            $table->dropColumn('status');
            $table->dropColumn('order');
            $table->dropColumn('multiple');
        });

        Schema::dropIfExists('track_edu_levels');

    }
};
