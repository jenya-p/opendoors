<?php

namespace App\Models\Participant;

use App\Models\Dir\Citizenship;
use App\Models\EduLevel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property int $user_id               // Пользователь
 *
 * @property int    $citizenship_id     // Гражданство
 * @property string $last_name          // Имя
 * @property string $first_name         // Фамилия
 * @property string $sex                // Пол
 * @property Carbon $birthdate          // Дата рождения
 * @property string $phone              // Телефон
 *
 * @property int[] $edu_level_ids
 *
 * @property-read User $user
 * @property-read Citizenship $citizenship
 * @property-read EduLevel[] $eduLevels
 * @property-read Member[] $members
 *
 * @mixin \Eloquent
 */
class Participant extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'participants';

    protected $fillable = ['user_id', 'citizenship_id',
        'last_name',
        'first_name',
        'sex',
        'birthdate',
        'phone', 'edu_level_ids', 'created_at', 'updated_at'];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function eduLevels(){
        return $this->belongsToMany(EduLevel::class, 'participant_edu_level', 'participant_id', 'edu_level_id');
    }

    public function citizenship(){
        return $this->belongsTo(Citizenship::class);
    }

    public function members(){
        return $this->hasMany(Member::class);
    }

    public function getEduLevelIdsAttribute() {
        return $this->eduLevels()->pluck('id');
    }

    public function setEduLevelIdsAttribute($values) {
        $this->eduLevels()->sync($values);
    }

}
