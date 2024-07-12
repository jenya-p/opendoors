<?php

namespace App\Models\Quiz;

use App\Models\Ordered;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $quiz_id
 * @property int $order
 * @property int $weight
 *
 * @property-read int $question_count
 *
 * @preperty-read Quiz        $quiz
 * @preperty-read Question[]  $questions
 *
 * @mixin \Eloquent
 */
class Group extends Model
{
    use Ordered;
    public $timestamps = false;

    protected $table = 'quiz_groups';

    protected $fillable = ['quiz_id', 'theme_id', 'order', 'weight'];

    protected static $orderedCategory = 'quiz_id';

    public function quiz(){
        return $this->belongsTo(Quiz::class);
    }

    public function questions(){
        return $this->hasMany(Question::class);
    }

    public function theme(){
        return $this->belongsTo(Theme::class);
    }

    public function getQuestionCountAttribute(){
        return $this->questions()->count();
    }


    public function scopeFilter(Builder $query, array $filter) {

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

        $filterByIds('theme_id');

        return $query;
    }

}
