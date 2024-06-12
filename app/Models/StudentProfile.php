<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $student_id        // Соискатель
 * @property int $track_id          // Трек
 * @property int $profile_id        // Профиль
 *
 * @property-read Student $student
 * @property-read Track $track
 * @property-read Profile $profile
 *
 * @mixin \Eloquent
 */
class StudentProfile extends Model {
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = ['student_id', 'track_id', 'profile_id'];

    protected $table = 'student_profiles';

    protected $fillable = ['student_id', 'track_id', 'profile_id'];

    protected $casts = [
        'roles' => 'array'
    ];

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function profile(){
        return $this->belongsTo(Profile::class);
    }

    public function track(){
        return $this->belongsTo(Track::class);
    }

}
