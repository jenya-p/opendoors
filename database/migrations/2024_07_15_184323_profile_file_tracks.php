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
        Schema::dropIfExists('profile_file_tracks');
        Schema::create('profile_file_tracks', function (Blueprint $table) {

            $table->foreignId('type_id');
            $table->foreign('type_id', 'profile_file_tracks__type')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('profile_file_types')
                ->references('id');

            $table->foreignId('track_id');
            $table->foreign('track_id', 'profile_file_tracks__track')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('tracks')
                ->references('id');

            $table->primary(['track_id','type_id']);
        });

        DB::statement("INSERT INTO profile_file_tracks (type_id, track_id) (
            SELECT id, track_id FROM profile_file_types WHERE NOT track_id IS NULL);");

        Schema::table('profile_file_types', function (Blueprint $table) {
            $table->dropForeign('profile_file_types__track');
            $table->dropColumn('track_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('profile_file_types', function (Blueprint $table) {
            $table->foreignId('track_id')->nullable()->after('id');
            $table->foreign('track_id', 'profile_file_types__track')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('tracks')
                ->references('id');
        });

      DB::statement("UPDATE profile_file_types t1
INNER JOIN profile_file_tracks t2 ON t2.type_id = t1.id
SET t1.track_id = t2.track_id;");

        Schema::dropIfExists('profile_file_tracks');

    }
};
