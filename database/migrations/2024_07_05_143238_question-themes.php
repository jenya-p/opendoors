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
        Schema::create('quiz_themes', function(Blueprint $t){
            $t->id();
            $t->string('name', 256);
        });

        Schema::table('quiz_groups', function(Blueprint $t){
            $t->foreignId('theme_id')->after('quiz_id')->nullable();
            $t->foreign('theme_id', 'quiz_group__theme')->cascadeOnUpdate()->cascadeOnDelete()
                ->on('quiz_themes')
                ->references('id');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_groups', function(Blueprint $t){
            $t->dropForeign('quiz_group__theme');
            $t->dropColumn('theme_id');
        });
        Schema::dropIfExists('quiz_themes');
    }
};
