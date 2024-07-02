<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property int $track_id          // Трек
 * @property int $profile_id        // Профиль
 * @property int $university_id     // Университет
 *
 * @property-read Track $track
 * @property-read Profile $profile
 * @property-read Track $university
 *
 * @mixin \Eloquent
 */
class UniversityProfile extends Model {
    use HasFactory;
    public $timestamps = false;

    protected $primaryKey = ['profile_id', 'track_id', 'university_id'];

    protected $table = 'university_profiles';

    protected $fillable = ['profile_id', 'track_id', 'university_id'];

    protected $casts = [];

    public function profile(){
        return $this->belongsTo(Profile::class);
    }

    public function track(){
        return $this->belongsTo(Track::class);
    }

    public function university(){
        return $this->belongsTo(University::class);
    }
}
