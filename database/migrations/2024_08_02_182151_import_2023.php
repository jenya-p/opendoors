<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('quizzes', function(Blueprint $t){
            $t->enum('stage', array_keys(\App\Models\Quiz\Quiz::STAGE_NAMES))->change();
        });



    }

    public function down(): void
    {

    }
};
