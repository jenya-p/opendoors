<?php

namespace App\Models\Participant;

use App\Models\Profile;
use App\Models\Track;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property int $participant_id        // Участник
 * @property int $track_id              // Трек
 * @property int $profile_id            // Профиль
 *
 * @property-read Participant $participant
 * @property-read Track $track
 * @property-read Profile $profile
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @mixin \Eloquent
 */
class Member extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'members';

    protected $fillable = ['participant_id', 'track_id', 'profile_id','created_at','updated_at'];

    protected $casts = [
        'roles' => 'array'
    ];

    public function participant(){
        return $this->belongsTo(Participant::class);
    }

    public function profile(){
        return $this->belongsTo(Profile::class);
    }

    public function track(){
        return $this->belongsTo(Track::class);
    }

    public function statement(){
        return $this->hasOne(Statement::class);
    }

}
