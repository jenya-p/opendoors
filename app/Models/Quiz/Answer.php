<?php

namespace App\Models\Quiz;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $attempt_id
 * @property int $question_id
 * @property Carbon $showed_at
 * @property Carbon $solved_at
 * @property array  $solution
 * @property float  $score
 * @property string $result
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @preperty-read Question $question
 * @preperty-read Attempt $attempt
 *
 * @mixin \Eloquent
 */
class Answer extends Model
{
    use SoftDeletes;

    protected $table = 'quiz_answers';

    protected $fillable = [
        'attempt_id','question_id','solution', 'score', 'result',
        'showed_at', 'solved_at','created_at', 'updated_at'];

    protected $casts = [
        'solution' => 'array',
        'showed_at' => 'datetime',
        'solved_at' => 'datetime',
    ];

    const RESULT_FAILED = 'failed';
    const RESULT_COMPLETE = 'solved';
    const RESULT_PARTIAL = 'partial';

    const RESULT_NAMES = [
        self::RESULT_FAILED => 'Не верно',
        self::RESULT_COMPLETE => 'Полное решение',
        self::RESULT_PARTIAL => 'Частичное решение',
    ];

    public function question(){
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }


}
