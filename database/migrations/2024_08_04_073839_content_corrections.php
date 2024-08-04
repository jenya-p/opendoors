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
        Schema::table('universities', function(Blueprint $table){
            $table->enum('status', ['active', 'disabled'])->after('id')->default('active');
            $table->unsignedInteger('order')->default(0)->after('status');
        });
        DB::statement('UPDATE universities SET `order` = id;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('universities', function(Blueprint $table){
            $table->dropColumn('status');
            $table->dropColumn('order');
        });
    }
};
