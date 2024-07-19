<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public $withinTransaction = true;

    public function up(): void {
        Schema::dropIfExists('roles');
        Schema::create('roles', function(Blueprint $table){
            $table->id();
            $table->morphs('item');
            $table->foreignId('user_id');

            $table->foreign('user_id', 'roles__user')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('users')
                ->references('id');

            $table->string('role', 128);

        });
    }


    public function down(): void {
        Schema::dropIfExists('roles');
    }
};
