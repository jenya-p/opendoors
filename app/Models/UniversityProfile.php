<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property int $profile_id        // Профиль
 * @property int $university_id     // Университет
 * @property int $program_id        // Направление
 *
 * @property-read Profile $profile
 * @property-read University $university
 * @property-read StudyProgram $program
 *
 * @mixin \Eloquent
 */
class UniversityProfile extends Model {
    use HasFactory;
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = ['profile_id', 'university_id', 'program_id'];

    protected $table = 'university_profiles';

    protected $fillable = ['profile_id', 'university_id', 'program_id'];

    public function profile(){
        return $this->belongsTo(Profile::class);
    }

    public function university(){
        return $this->belongsTo(University::class);
    }

    public function program(){
        return $this->belongsTo(StudyProgram::class);
    }
}
