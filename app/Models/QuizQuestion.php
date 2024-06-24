<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 *
 * @property int $group_id
 * @property int $order
 * @property int $weight
 * @property string $text
 * @property string $text_en
 * @property string $description
 * @property string $description_en
 * @property string $type
 * @property array $options
 * @property array $verification
 *
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read string $snippet
 *
 * @preperty-read Question[] $questions
 * @mixin \Eloquent
 */
class QuizQuestion extends Model
{
    use SoftDeletes;

    protected $table = 'quiz_questions';

    protected $fillable = ['group_id','order','weight',
        'text','text_en','description','description_en',
        'type','options','verification','created_at','updated_at',
    ];

    protected $casts = [
        'options' => 'json',
        'verification' => 'json',
    ];

    const TYPE_ONE =    'one';
    const TYPE_MANY =   'many';
    const TYPE_MULTI =  'multi';
    const TYPE_WORDS =  'words';
    const TYPE_NUMBER = 'number';
    const TYPE_FREE =   'free';

    const TYPE_NAMES = [
        self::TYPE_ONE => 'Выбор одного варианта',
        self::TYPE_MANY => 'Выбор нескольких вариантов',
        self::TYPE_MULTI => 'Множественный выбор',
        self::TYPE_WORDS => 'С эталонными ответами',
        self::TYPE_NUMBER => 'Числовой',
        self::TYPE_FREE => 'C развернутым ответом'
    ];


    public function group(){
        return $this->belongsTo(QuizGroup::class, 'group_id');
    }

    public function images(){
        return $this->hasMany(Attachment::class, 'item_id')
            ->where('item_type', '=', Attachment::ITEM_TYPE_QUESTION);
    }

    public function images_en(){
        return $this->hasMany(Attachment::class, 'item_id')
            ->where('item_type', '=', Attachment::ITEM_TYPE_QUESTION_EN);
    }

    public function getSnippetAttribute(){
        $text = strip_tags($this->text);
        if(strlen($text) > 200){
            return trim(mb_substr($text, 0, min(200, strpos($text, ' ', 180)))) .'...';
        } else {
            return trim($text);
        }

    }

    public function getTypeNameAttribute(){
        $text = strip_tags($this->text);
        if(strlen($text) > 300){
            return trim(mb_substr($text, 0, min(240, strpos($text, ' ', 200)))) .'...';
        } else {
            return trim($text);
        }
    }

    public function getOptionCountAttribute(){
        if(($this->type == self::TYPE_ONE || $this->type == self::TYPE_MANY || $this->type == self::TYPE_MULTI)
            && is_array($this->options)
            && array_key_exists('options', $this->options)
            && is_array($this->options['options'])
        ){
            return count($this->options['options']);
        } else {
            return null;
        }
    }

}
