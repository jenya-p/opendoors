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
 * @property int        $id
 * @property int        $participant_id         // Участник
 * @property int        $edu_level_id           // Уровень образования
 * @property int        $country_id             // Страна
 * @property string     $name                   // Наименование учебного заведения
 * @property int        $graduation_year        // Год выпуска
 * @property string     $diploma_title          // Тема диплома
 * @property boolean    $is_study_russian       // Обучение на русском языке
 * @property boolean    $is_study_english       // Обучение на английском языке
 *
 * @property-read Participant   $participant
 * @property-read EduLevel      $edu_level
 *
 * @mixin \Eloquent
 */
class Education extends Model {
    use SoftDeletes;

    protected $table = 'participant_education';

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
