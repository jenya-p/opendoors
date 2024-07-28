<?php

namespace App\Models\Dir;

use Carbon\Carbon;
use App\Models\Translable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property string $region_id  Регион
 * @property string $name       Название
 * @property string $name_en    Название (En)
 * @property string $code       Код
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read Region $region
 *
 * @mixin \Eloquent
 */
class Country extends Model {
    use HasFactory, SoftDeletes, Translable;

    protected $table = 'dir_countries';

    protected $fillable = ['name','name_en', 'code', 'region_id'];

    protected $translable = ['name'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function region(){
        return $this->belongsTo(Region::class);
    }

}
