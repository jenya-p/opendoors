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
        Schema::create('faq_categories', function (Blueprint $table) {
            $table->id();

            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->unsignedInteger('order')->default(0);

            $table->string('name', 256);
            $table->string('name_en', 256)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id');
            $table->foreign('category_id', 'faq__category')
                ->restrictOnDelete()->cascadeOnUpdate()
                ->on('faq_categories')->references('id');

            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->unsignedInteger('order')->default(0);

            $table->text('question');
            $table->text('question_en')->nullable();

            $table->text('answer');
            $table->text('answer_en')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('faqs');
        Schema::dropIfExists('faq_categories');
    }
};
