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
        Schema::create('content_widgets', function(Blueprint $t){
            $t->id();
            $t->string('key', 32)->unique();
            // $t->string('schema', 32);
            $t->json('data');
            $t->timestamps();
            $t->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_widgets');
    }
};
