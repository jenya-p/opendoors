<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int        $id
 * @property string     $status         Статус
 * @property int        $order          Порядок
 * @property string     $name           Название
 * @property string     $name_en        Название (En)
 * @property boolean    $multiple      Множественность
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @method Builder active()
 *
 * @mixin \Eloquent
 */
class EduLevel extends Model {
    use HasFactory, SoftDeletes, Translable, Ordered;

    protected $translable = ['name'];

    protected $table = 'edu_levels';

    protected $fillable = ['status','order','name','name_en', 'multiple', 'created_at', 'updated_at'];

    protected $casts = [
        'multiple' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public static function scopeActive(Builder $query){
        return $query->where('status', '=', 'active');
    }


}
