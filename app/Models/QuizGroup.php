<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 *  @preperty-read $question_count
 *
 * @preperty-read Question[] $questions
 * @mixin \Eloquent
 */
class QuizGroup extends Model
{
    use SoftDeletes;

    protected $table = 'quiz_groups';

    protected $fillable = ['name', 'created_at', 'updated_at'];

    public function questions(){
        return $this->hasMany(QuizQuestion::class, 'group_id');
    }

    public function getQuestionCountAttribute(){
        return $this->questions()->count();
    }

}
