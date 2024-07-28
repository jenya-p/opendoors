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

        /*

        dir_countries
            name
            name_en
            code

        dir_interests
            parent_id
            name
            name_en

        dir_achivements
            id
            order
            name
            name_en
            settings


        edu_levels
            variative

        students -> participants
            user_id
            citizenship_id
            last_name
            first_name
                birthday
                sex (m ,f)
                phone


        participant_edu_levels
            participant_id
            edu_level_id

        student_profiles -> members

        participant_universities
            id
            participant_id
            university_id
            order


        participant_educations
            id
            participant_id
            level_id
            country
            name
            graduation_year
            diploma_title
            is_study_russian     boolean
            is_study_english     boolean
            ->documents

        member_letters
            id
            member_id
            interests
            goals
            achievements
            profile_reason
            country_reason

        member_interests
            id
            member_id
            interest_id

        member_achivemets
            id
            profile_id
            type
            name
            extras: json

            -> attachments


*/

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
