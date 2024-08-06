<?php

namespace App\Models\Participant;

use App\Models\Dir\Citizenship;
use App\Models\Dir\Country;
use App\Models\EduLevel;
use App\Models\HasAttachments;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property int $participant_id         // Участник
 * @property int $edu_level_id           // Уровень образования
 * @property int $country_id             // Страна
 * @property string $name                   // Наименование учебного заведения
 * @property int $graduation_year        // Год выпуска
 * @property string $diploma_title          // Тема диплома
 * @property boolean $is_study_russian       // Обучение на русском языке
 * @property boolean $is_study_english       // Обучение на английском языке

 *
 * @property-read Participant $participant
 * @property-read EduLevel $edu_level
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @mixin \Eloquent
 */
class Degree extends Model {
    use SoftDeletes, HasAttachments;

    protected $table = 'participant_degrees';

    protected $fillable = ['participant_id', 'edu_level_id', 'country_id', 'name', 'graduation_year',
        'diploma_title', 'is_study_russian', 'is_study_english', 'created_at', 'updated_at'
    ];


    public function participant() {
        return $this->belongsTo(Participant::class);
    }

    public function eduLevel() {
        return $this->belongsTo(EduLevel::class);
    }

    public function country() {
        return $this->belongsTo(Country::class);
    }


}
