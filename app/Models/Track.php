<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property string $status
 * @property string $name       Название
 * @property string $name_en    Название (En)
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read StudentProfile[] $studentProfiles
 *
 * @mixin \Eloquent
 */
class Track extends Model {
    use HasFactory, SoftDeletes, Translable;

    protected $table = 'tracks';

    protected $fillable = ['status', 'name','name_en', 'created_at', 'updated_at'];

    protected $translable = ['name'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];


    public function studentProfiles(){
        return $this->hasMany(StudentProfile::class, 'student_id', 'id');
    }

    public function profileFileTypes(){
        return $this->belongsToMany(ProfileFileType::class, 'profile_file_tracks', 'track_id', 'type_id');
    }

    public static function scopeActive(Builder $query){
        return $query->where('status', '=', 'active');
    }
}
