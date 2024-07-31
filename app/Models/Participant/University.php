<?php

namespace App\Models\Participant;

use App\Models\Dir\Citizenship;
use App\Models\EduLevel;
use App\Models\Ordered;
use App\Models\University as BaseUniversity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property int $order      // Порядок
 * @property int $participant_id        // Участник *
 * @property int $university_id      // Университет

 *
 * @property-read Participant $participant
 * @property-read BaseUniversity $university
 *
 * @mixin \Eloquent
 */
class University extends Model {
    use Ordered, SoftDeletes;

    protected static $orderedCategory = ['participant_id'];

    protected $table = 'participant_universities';

    protected $fillable = [

        'order',
        'participant_id',
        'university_id',
        ];


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
