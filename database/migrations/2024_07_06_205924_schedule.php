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
        Schema::create('schedule_items', function (Blueprint $table) {
            $table->id();

            $table->enum('status', ['active', 'disabled']);

            $table->date('date_from');
            $table->date('date_to')->nullable();
            $table->string('title', 512)->nullable();
            $table->string('title_en', 512)->nullable();
            $table->text('summary')->nullable();
            $table->text('summary_en')->nullable();
            $table->text('details')->nullable();
            $table->text('details_en')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('schedule_items');
    }
};
