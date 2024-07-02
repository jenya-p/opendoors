<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {

        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quiz_groups');

        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('profile_id')->nullable();
            $table->foreign('profile_id', 'quizzes__profile')
                ->on('profiles')
                ->references('id')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->enum('track', ['b', 'ma'])->nullable();
            $table->enum('stage', ['1', '2'])->nullable();

            $table->string('name');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quiz_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id');
            $table->foreign('quiz_id', 'quiz_groups__quiz')->on('quizzes')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedMediumInteger('order');

            $table->float('weight')->nullable();
        });

        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_id');
            $table->foreign('quiz_id', 'quiz_questions__quiz')
                ->on('quizzes')
                ->references('id')
                ->cascadeOnDelete()->cascadeOnUpdate();

            $table->foreignId('group_id');
            $table->foreign('group_id', 'quiz_questions__group')
                ->on('quiz_groups')
                ->references('id')
                ->cascadeOnDelete()->cascadeOnUpdate();

            $table->enum('status', array_keys(\App\Models\Quiz\Question::STATUS_NAMES));

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

        Schema::create('quiz_attempts', function (Blueprint $t) {
            $t->id();

            $t->foreignId('quiz_id');
            $t->foreign('quiz_id', 'quiz_attempts__quiz')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('quizzes')->references('id');

            $t->foreignId('user_id');
            $t->foreign('user_id', 'quiz_attempts__user')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('users')->references('id');


            $t->float('score')->nullable();
            $t->string('result')->nullable();

            $t->dateTime('started_at')->nullable();
            $t->dateTime('completed_at')->nullable();

            $t->timestamps();
            $t->softDeletes();

        });

        Schema::create('quiz_answers', function (Blueprint $t) {
            $t->id();

            $t->foreignId('attempt_id');
            $t->foreign('attempt_id', 'quiz_answers__attempt')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->on('quiz_attempts')->references('id');

            $t->foreignId('question_id');
            $t->foreign('question_id', 'quiz_answers__question')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->on('quiz_attempts')
                ->references('id');

            $t->json('solution')->nullable();
            $t->float('score')->nullable();
            $t->enum('result', array_keys(\App\Models\Quiz\Answer::RESULT_NAMES));

            $t->dateTime('showed_at')->nullable();
            $t->dateTime('solved_at')->nullable();

            $t->timestamps();
            $t->softDeletes();
        });


        DB::statement("ALTER TABLE `stages` CHANGE COLUMN `type` `type` ENUM('portfolio','quiz','test','interview') NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `order`;");
        DB::statement("UPDATE stages SET `type` = 'quiz' WHERE `type` = 'test';");
        DB::statement("ALTER TABLE `stages` CHANGE COLUMN `type` `type` ENUM('portfolio','quiz','interview') NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `order`;");

        DB::statement('UPDATE user_admins SET roles =REPLACE(roles, "manage_tests", "manage_quizzes");');

        DB::statement("ALTER TABLE `quiz_questions`
	CHANGE COLUMN `type` `type` ENUM('one','many','multi','words','number','free','match') NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `status`;");

    }


    public function down(): void {
        Schema::dropIfExists('quiz_answers');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quiz_groups');
        Schema::dropIfExists('quizzes');

        DB::statement("ALTER TABLE `stages` CHANGE COLUMN `type` `type` ENUM('portfolio','quiz','test','interview') NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `order`;");
        DB::statement("UPDATE stages SET `type` = 'test' WHERE `type` = 'quiz';");
        DB::statement("ALTER TABLE `stages` CHANGE COLUMN `type` `type` ENUM('portfolio','test','interview') NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `order`;");

        DB::statement('UPDATE user_admins SET roles =REPLACE(roles, "manage_quizzes", "manage_tests");');
        DB::statement("ALTER TABLE `quiz_questions`
	CHANGE COLUMN `type` `type` ENUM('one','many','multi','words','number','free') NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `status`;");
    }
};
