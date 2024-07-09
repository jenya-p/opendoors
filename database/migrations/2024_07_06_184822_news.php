<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('content_news', function (Blueprint $table) {

            $table->id();
            $table->enum('status', ['active', 'disabled']);
            $table->date('date');
            $table->string('title', 512)->nullable();
            $table->string('title_en', 512)->nullable();
            $table->text('summary')->nullable();
            $table->text('summary_en')->nullable();
            $table->text('content')->nullable();
            $table->text('content_en')->nullable();

            $table->string(     'seo_title', 512)->nullable();
            $table->string(     'seo_title_en', 512)->nullable();
            $table->mediumText('seo_description')->nullable();
            $table->mediumText('seo_description_en')->nullable();
            $table->mediumText('seo_keywords')->nullable();
            $table->mediumText('seo_keywords_en')->nullable();

            $table->timestamps();
            $table->softDeletes();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('content_news');
    }
};
