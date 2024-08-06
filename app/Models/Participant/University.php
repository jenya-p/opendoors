<?php

namespace App\Models\Participant;

use App\Models\Dir\Citizenship;
use App\Models\EduLevel;
use App\Models\Ordered;
use App\Models\University as BaseUniversity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property int $order              // Порядок
 * @property int $participant_id     // Участник
 * @property int $university_id      // Университет
 *
 * @property-read Participant $participant
 * @property-read BaseUniversity $university
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @mixin \Eloquent
 */
class University extends Model {
    use Ordered, SoftDeletes;

    protected static $orderedCategory = ['participant_id'];

    protected $table = 'participant_universities';

    protected $fillable = ['order','participant_id',
        'university_id','created_at','updated_at'];


    public function participant(){
        return $this->belongsTo(Participant::class);
    }

    public function university(){
        return $this->belongsTo(University::class);
    }


}
