<?php

namespace App\Models\Dir;

use Carbon\Carbon;
use App\Models\Translable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property string $name       Название
 * @property string $name_en    Название (En)
 * @property string $code       Код
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @mixin \Eloquent
 */
class Region extends Model {
    use HasFactory, SoftDeletes, Translable;

    protected $table = 'dir_regions';

    protected $fillable = ['name','name_en', 'code'];

    protected $translable = ['name'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];


}
