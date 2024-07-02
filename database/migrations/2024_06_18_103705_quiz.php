<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {

        $this->down();

        Schema::create('quiz_groups', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_id');
            $table->foreign('group_id', 'questions__group')
                ->references('id')
                ->on('quiz_groups')
                ->cascadeOnDelete()->cascadeOnUpdate();


            $table->unsignedMediumInteger('order')->nullable();
            $table->float('weight')->nullable();
            $table->enum('type', array_keys(\App\Models\Quiz\Question::TYPE_NAMES));
            $table->text('text');
            $table->text('text_en');
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->json('options')->nullable();
            $table->json('verification')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quiz_groups');

    }
};
