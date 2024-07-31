<?php

namespace App\Models;

use App\Models\Participant\Member;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;


/**
 * @property int $id
 * @property string $status
 * @property string $name       Название
 * @property string $name_en    Название (En)
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property int[] $required_edu_level_ids
 *
 * @property-read EduLevel[] $required_edu_levels
 * @property-read EduLevel  $max_edu_level
 * @property-read Member[] $members
 *
 * @mixin \Eloquent
 */
class Track extends Model {
    use HasFactory, SoftDeletes, Translable;

    protected $table = 'tracks';

    protected $fillable = ['status', 'name', 'name_en', 'required_edu_level_ids', 'created_at', 'updated_at'];

    protected $translable = ['name'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];


    public function members() {
        return $this->hasMany(Member::class, 'participant_id', 'id');
    }

    public function profileFileTypes() {
        return $this->belongsToMany(ProfileFileType::class, 'profile_file_tracks', 'track_id', 'type_id');
    }

    public function requiredEduLevels() {
        return $this->belongsToMany(EduLevel::class, 'track_edu_levels', 'track_id', 'edu_level_id');
    }

    public function getRequiredEduLevelIdsAttribute() {
        return $this->requiredEduLevels()->pluck('id')->toArray();
    }

    public function setRequiredEduLevelIdsAttribute($values) {
        $this->requiredEduLevels()->sync($values);
    }

    public function getMaxEduLevelAttribute() {
        $id = $this->requiredEduLevels()->active()->pluck('id')->last();
        return EduLevel::find($id);
    }

    public static function scopeActive(Builder $query) {
        return $query->where('status', '=', 'active');
    }
}
