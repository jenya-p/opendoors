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
        $this->down();

        Schema::create('participant_universities', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order')->default(0);
            $table->foreignId('participant_id');
            $table->foreignId('university_id');

            $table->foreign('participant_id', 'participant_universities__participant')
                ->on('participants')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('university_id', 'participant_universities__university')
                ->on('universities')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_universities');
    }
};
