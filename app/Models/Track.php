<?php

namespace App\Models;

use App\Models\Ordered;
use App\Models\Quiz\Question;
use App\Models\Test\Test;
use App\Models\Test\Ticket;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property string $name       Название
 * @property string $name_en    Название (En)
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read UniversityProfile[] $universityProfiles
 * @property-read StudentProfile[] $studentProfiles
 *
 * @mixin \Eloquent
 */
class Track extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'tracks';

    protected $fillable = ['name','name_en', 'created_at', 'updated_at'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function universityProfiles(){
        return $this->hasMany(UniversityProfile::class, 'track_id', 'id');
    }

    public function studentProfiles(){
        return $this->hasMany(StudentProfile::class, 'student_id', 'id');
    }


}
