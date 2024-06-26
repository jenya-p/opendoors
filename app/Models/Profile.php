<?php

namespace App\Models;

use App\Models\Ordered;
use App\Models\Quiz\Question;
use App\Models\Test\Test;
use App\Models\Test\Ticket;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property int $order
 * @property string $name          Название
 * @property string $name_en       Название (En)
 * @property string $icon          Иконка
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read Stage[] $stages
 * @property-read UniversityProfile[] $universityProfiles
 *
 * @mixin \Eloquent
 */
class Profile extends Model {
    use HasFactory, SoftDeletes, Ordered;

    protected $table = 'profiles';

    protected $fillable = ['order', 'name', 'name_en', 'icon', 'created_at', 'updated_at'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function stages(){
        return $this->hasMany(Stage::class);
    }

    public function universityProfiles(){
        return $this->hasMany(UniversityProfile::class, 'profile_id', 'id');
    }

}
