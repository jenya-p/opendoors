<?php

namespace App\Models\Quiz;

use App\Models\Attachment;
use App\Models\HasAttachments;
use App\Models\Translable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;


/**
 * @property int $id
 *
 * @property string $status
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
 * @property-read string $status_name
 * @property-read string $type_name
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
    use SoftDeletes, Translable, HasAttachments;

    protected $translable = [
        'text', 'description', 'options.*', 'verification.*', 'images'
    ];

    protected $table = 'quiz_questions';

    protected $fillable = ['status', 'group_id','quiz_id',
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
        self::STATUS_ACTIVE =>          'Активно',
        self::STATUS_DISABLED =>        'Архив',
        self::STATUS_VERIFICATION =>    'Проверка',
        self::STATUS_PROBLEM =>         'Проблема',
        self::STATUS_DRAFT =>           'Скрыт',
   ];

    const TYPE_ONE =    'one';
    const TYPE_MANY =   'many';
    const TYPE_MULTI =  'multi';
    const TYPE_WORDS =  'words';
    const TYPE_NUMBER = 'number';
    const TYPE_FREE =   'free';
    const TYPE_MATCH =   'match';

    const TYPE_NAMES = [
        self::TYPE_ONE => 'Задание на выбор одного варианта',
        self::TYPE_MANY => 'Задание на выбор нескольких вариантов',
        self::TYPE_MULTI => 'Задание на множественный выбор',
        self::TYPE_WORDS => 'Задание с эталонным ответом (слово)',
        self::TYPE_NUMBER => 'Задание с эталонным ответом (число/числовой диапазон)',
        self::TYPE_FREE => 'Задание с развёрнутым ответом',
        self::TYPE_MATCH => 'Задание на соответствие',
    ];

    public function quiz(){
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function group(){
        return $this->belongsTo(Group::class, 'group_id');
    }



    public function images(){
        return $this->hasManyAttachments('ru');
    }

    public function images_en(){
        return $this->hasManyAttachments('en');
    }

    public function getSnippetAttribute(){
        $text = strip_tags($this->text);
        if(strlen($text) > 200){
            return trim(mb_substr($text, 0, min(200, strpos($text, ' ', 180)))) .'...';
        } else {
            return trim($text);
        }

    }

    public function getStatusNameAttribute(){
        return \Arr::iif(self::STATUS_NAMES, $this->status);
    }

    public function getTypeNameAttribute(){
        return \Arr::iif(self::TYPE_NAMES, $this->type);
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


    public function scopeFilter(Builder $query, array $filter) {

        if(!empty($filter['profile_id']) ||
            !empty($filter['track']) ||
            !empty($filter['stage'])){

            $quizQuery = Quiz::select('id')->filter(\Arr::only($filter, ['track', 'profile_id', 'stage']));
            $query->whereIn('quiz_id', $quizQuery);
        }


        if(!empty($filter['theme_id'])){
            $groupQuery = Group::select('id')->filter(\Arr::only($filter, ['theme_id']));
            $query->whereIn('group_id', $groupQuery);
        }

        $filterByIds = function ($key) use ($query, $filter) {
            if (!empty($filter[$key])) {
                if (is_array($filter[$key])) {
                    $query->whereIn($this->table . '.' . $key, $filter[$key]);
                } else if (is_numeric($filter[$key])) {
                    $query->where($this->table . '.' . $key, '=', $filter[$key]);
                }
            } else if (array_key_exists($key, $filter) && $filter[$key] === null) {
                $query->whereNull($this->table . '.' . $key);
            }
        };

        $filterByIds('status');

        if(!empty($filter['query'])){
            $lcQuery = '%' . mb_strtolower(trim($filter['query'])) . '%';
            if(is_numeric($filter['query'])){
                $query->where(function(Builder $query) use ($filter, $lcQuery){
                    $query->where('id', $filter)
                        ->orWhereRaw('(text like ? or text_en like ?)', [$lcQuery,$lcQuery])
                        ->orderByRaw('id = ? DESC', $filter);
                });
            } else {
                $query
                    ->whereRaw('(text like ? or text_en like ?)', [$lcQuery,$lcQuery]);
            }
        }


        return $query;
    }


    public function scopeAvailable(Builder $query, $mode = null) {
        if(\Gate::check('admin-quiz')){
            return;
        }
        $idSelect = \Auth::user()->roles()->forItem(Quiz::class)->select('item_id');
        $query->whereIn('quiz_id', $idSelect);
    }

    public function getCanAttribute(){
        return [
            'view'   => \Gate::check('view', $this),
            'update' => \Gate::check('update', $this),
            'probe' => \Gate::check('probe', $this),
            'delete' => \Gate::check('delete', $this),
        ];
    }

}
