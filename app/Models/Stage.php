<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property int $track_id
 * @property int $profile_id
 * @property int $order         Порядок
 * @property string $name       Название
 * @property string $name_en    Название (En)
 * @property string $type       Тип
 * @property array $settings    Настройки типа
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read string $type_name
 *
 * @property-read Track $track          Трек
 * @property-read Profile $profile      Профиль
 *
 * @mixin \Eloquent
 */
class Stage extends Model {
    use HasFactory, SoftDeletes, Ordered;

    protected $table = 'stages';

    protected $fillable = [
        'profile_id', 'track_id', 'order',
        'name', 'name_en', 'type', 'settings', 'created_at', 'updated_at'];

    protected $casts = [
        'settings' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    const TYPE_PORTFOLIO = 'portfolio';
    const TYPE_QUIZ = 'quiz';
    const TYPE_INTERVIEW = 'interview';

    const TYPES = [
        self::TYPE_PORTFOLIO => 'Портфолио',
        self::TYPE_QUIZ => 'Тестирование',
        self::TYPE_INTERVIEW => 'Собеседование',
    ];

    protected static $orderedCategory = ['track_id', 'profile_id'];

    public function profile() {
        return $this->belongsTo(Profile::class);
    }

    public function track() {
        return $this->belongsTo(Track::class);
    }

    public function getTypeNameAttribute() {
        if (array_key_exists($this->type, self::TYPES)) {
            return self::TYPES[$this->type];
        } else {
            return $this->type;
        }
    }


    public function scopeFilter(Builder $query, array $filter) {

        $filterByIds = function($key) use ($query, $filter) {
            if (!empty($filter[$key])) {
                if(is_array($filter[$key])){
                    $query->whereIn($this->table.'.'.$key, $filter[$key]);
                } else if (is_numeric($filter[$key])){
                    $query->where($this->table.'.'.$key, '=', $filter[$key]);
                }
            } else if(array_key_exists($key, $filter) && $filter[$key] === null){
                $query->whereNull($this->table.'.'.$key);
            }
        };

        $filterByIds('profile_id');
        $filterByIds('track_id');
        $filterByIds('type');


        return $query;
    }

}
