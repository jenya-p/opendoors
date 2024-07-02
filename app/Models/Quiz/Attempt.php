<?php

namespace App\Models\Quiz;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property int $quiz_id
 *
 * @property string $result
 * @property Carbon $started_at
 * @property Carbon $completed_at
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @preperty-read User $user
 * @preperty-read Quiz $quiz
 * @preperty-read Answer[] $answers
 *
 * @mixin \Eloquent
 */
class Attempt extends Model
{
    use SoftDeletes;

    protected $table = 'quiz_attempts';

    protected $fillable = ['user_id','quiz_id','result','started_at','completed_at',
        'created_at', 'updated_at'];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function quiz(){
        return $this->belongsTo(Quiz::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function answers(){
        return $this->hasMany(Answers::class);
    }
}
