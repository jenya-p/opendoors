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
        Schema::table('attachments', function(Blueprint $table){
            $table->string('type', 64)->after('item_id')->nullable();
            $table->unsignedInteger('order')->after('type')->default(0);
        });

        DB::statement("update attachments SET item_type = 'university', `type` = 'logo_ru' WHERE item_type = 'university_logo';");
        DB::statement("update attachments SET item_type = 'university', `type` = 'logo_en' WHERE item_type = 'university_logo_en';");
        DB::statement("update attachments SET item_type = 'question', `type` = 'ru' WHERE item_type = 'question';");
        DB::statement("update attachments SET item_type = 'question', `type` = 'en' WHERE item_type = 'question_en';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        DB::statement("update attachments SET item_type = 'university_logo' WHERE  item_type = 'university' and `type` = 'logo';");
        DB::statement("update attachments SET item_type = 'university_logo_en' WHERE  item_type = 'university' and `type` = 'logo_en';");
        DB::statement("update attachments SET item_type = 'question' WHERE  item_type = 'question' and `type` = 'ru';");
        DB::statement("update attachments SET item_type = 'question_en' WHERE  item_type = 'question' and `type` = 'en';");

        Schema::table('attachments', function(Blueprint $table){
            $table->dropColumn('type');
            $table->dropColumn('order');
        });



    }
};
