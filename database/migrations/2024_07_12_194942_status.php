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
        Schema::table('profiles', function(Blueprint $table){
            $table->enum('status', ['active', 'disabled'])->after('order')->default('active');
        });
        Schema::table('tracks', function(Blueprint $table){
            $table->enum('status', ['active', 'disabled'])->after('id')->default('active');
        });
        Schema::table('stages', function(Blueprint $table){
            $table->enum('status', ['active', 'disabled'])->after('order')->default('active');
        });

        DB::statement('UPDATE profiles set status = :status where name = :name', ['status' => 'disabled', 'name' => 'Арифметика TEST']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function(Blueprint $table){
            $table->dropColumn('status');
        });
        Schema::table('tracks', function(Blueprint $table){
            $table->dropColumn('status');
        });
        Schema::table('stages', function(Blueprint $table){
            $table->dropColumn('status');
        });
    }
};
