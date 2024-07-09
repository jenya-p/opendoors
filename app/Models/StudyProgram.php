<?php

namespace App\Models;

use Carbon\Carbon;
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
 *
 * @mixin \Eloquent
 */
class StudyProgram extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'study_programs';

    protected $fillable = ['name','name_en', 'created_at', 'updated_at'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function universityProfiles(){
        return $this->hasMany(UniversityProfile::class, 'direction_id', 'id');
    }

}
