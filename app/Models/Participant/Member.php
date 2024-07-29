<?php

namespace App\Models\Participant;

use App\Models\Profile;
use App\Models\Track;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


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
 * @mixin \Eloquent
 */
class Member extends Model {
    use HasFactory;

    protected $table = 'members';

    protected $fillable = ['participant_id', 'track_id', 'profile_id'];

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

}
