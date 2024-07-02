<?php

namespace App\Models\Quiz;

use App\Models\Ordered;
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

    protected $fillable = ['quiz_id', 'order', 'weight'];

    public function quiz(){
        return $this->belongsTo(Quiz::class);
    }

    public function questions(){
        return $this->hasMany(Question::class);
    }

    public function getQuestionCountAttribute(){
        return $this->questions()->count();
    }

}
