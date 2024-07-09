<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property string $name       Название
 * @property string $name_en    Название (En)
 * @property string $url        УРЛ
 * @property string $url_en     УРЛ (En)
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read UniversityProfile[] $universityProfiles
 * @property-read UniversityUser[] $universityUsers
 * @property-read Profile[] $coordinatedProfiles
 *
 * @property-read Attachment $logo

 * @mixin \Eloquent
 */
class University extends Model {
    use HasFactory, SoftDeletes, Translable;

    protected $table = 'universities';

    protected $fillable = ['name','name_en', 'url', 'url_en', 'created_at', 'updated_at'];

    public $translable = ['name', 'url'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function universityProfiles(){
        return $this->hasMany(UniversityProfile::class, 'university_id', 'id');
    }

    public function universityUsers(){
        return $this->hasMany(UniversityUser::class, 'university_id', 'id');
    }

    public function logo(){
        return $this->hasOne(Attachment::class, 'item_id')
            ->where('item_type', '=', Attachment::ITEM_TYPE_UNIVERSITY_LOGO);
    }

    public function coordinatedProfiles(){
        return $this->hasMany(Profile::class, 'coordinator_id');
    }

}
