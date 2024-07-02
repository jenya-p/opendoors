<?php

namespace App\Models\Quiz;

use App\Models\Attachment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 *
 * @property int $quiz_id
 * @property int $group_id
 *
 * @property string $text
 * @property string $text_en
 * @property string $description
 * @property string $description_en
 * @property string $type
 * @property array $options
 * @property array $verification
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read string $snippet
 *
 * @preperty-read Quiz $quiz
 * @preperty-read Group $group
 * @preperty-read Attempt[] $attempts
 * @preperty-read Answers[] $answers
 *

 * @mixin \Eloquent
 */
class Question extends Model
{
    use SoftDeletes;

    protected $table = 'quiz_questions';

    protected $fillable = ['group_id','quiz_id',
        'text','text_en','description','description_en',
        'type','options','verification','created_at','updated_at',
    ];

    protected $casts = [
        'options' => 'json',
        'verification' => 'json',
    ];

    const STATUS_ACTIVE =       'active';
    const STATUS_DISABLED =     'disabled';
    const STATUS_VERIFICATION = 'verification';
    const STATUS_PROBLEM =      'problem';
    const STATUS_DRAFT =        'draft';

    const STATUS_NAMES = [
        self::STATUS_ACTIVE => 'Активно',
        self::STATUS_DISABLED => 'Отключено',
        self::STATUS_VERIFICATION => 'Проверка',
        self::STATUS_PROBLEM => 'Проблема',
        self::STATUS_DRAFT => 'Черновик',
   ];

    const TYPE_ONE =    'one';
    const TYPE_MANY =   'many';
    const TYPE_MULTI =  'multi';
    const TYPE_WORDS =  'words';
    const TYPE_NUMBER = 'number';
    const TYPE_FREE =   'free';
    const TYPE_MATCH =   'match';

    const TYPE_NAMES = [
        self::TYPE_ONE => 'Выбор одного варианта',
        self::TYPE_MANY => 'Выбор нескольких вариантов',
        self::TYPE_MULTI => 'Множественный выбор',
        self::TYPE_WORDS => 'С эталонными ответами',
        self::TYPE_NUMBER => 'Числовой',
        self::TYPE_FREE => 'C развернутым ответом',
        self::TYPE_MATCH => 'Соответствие',
    ];

    public function quiz(){
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function group(){
        return $this->belongsTo(Group::class, 'group_id');
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

    }

    public function getOptionCountAttribute(){
        if(($this->type == self::TYPE_ONE || $this->type == self::TYPE_MANY || $this->type == self::TYPE_MULTI)
            && is_array($this->options)
            && array_key_exists('options', $this->options)
            && is_array($this->options['options'])
        ){
            return count($this->options['options']);
        } else if($this->type == self::TYPE_MATCH
            && is_array($this->options)
            && array_key_exists('options', $this->options)
            && is_array($this->options['options'])
            && array_key_exists('categories', $this->options)
            && is_array($this->options['categories'])
        ){
            return count($this->options['options']) . ' + ' . count($this->options['categories']);
        } else {
            return null;
        }
    }

}
