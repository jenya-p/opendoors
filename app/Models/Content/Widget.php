<?php

namespace App\Models\Content;

use App\Models\Translable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Util\Json;

/**
 * @property string $id
 * @property string $key
 * @property array $data
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @mixin \Eloquent
 *
 */
class Widget extends Model {

    use SoftDeletes, Translable;

    protected $table = 'content_widgets';

    protected $fillable = ['key', 'data'];

    protected $casts = [
        'data' => 'array'
    ];

    protected $translable = ['data.*'];

    public function getNameAttribute(){
        switch ($this->key){
            case( 'about'): return "Страница \"Олимпиада\"";
            case('footer'): return "Футер";
            case( 'home.partners'): return "Партнеры";
            case('home.top'): return "Главная. Верхняя часть";
            case( 'rules'): return "Страница \"Правила\"";
            case( 'tracks'): return "Страница \"Треки\"";
            default: return $this->key;
        }

    }

    public static function findByKey($key){
        return self::where('key', '=', $key)->first();
    }

}
