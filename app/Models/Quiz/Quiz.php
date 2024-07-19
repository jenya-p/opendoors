<?php

namespace App\Models\Quiz;

use App\Models\Attachment;
use App\Models\HasRoles;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property int $profile_id
 * @property string $track
 * @property string $stage
 *
 * @property string $name
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read int $question_count
 * @property-read string $track_name
 * @property-read string $stage_name
 *
 * @property-read Profile $profile
 * @property-read Question[] $questions
 * @property-read Group[] $groups
 * @property-read Attachment[] $attempts
 *
 * @mixin \Eloquent
 */
class Quiz extends Model {
    use SoftDeletes, HasRoles;

    protected $table = 'quizzes';

    const TRACK_B = 'b';
    const TRACK_MA = 'ma';
    const TRACK_NAMES = [
        self::TRACK_B => 'Бакалавриат',
        self::TRACK_MA => 'Магистратура + Аспирантура'
    ];

    const STAGE_1 = '1';
    const STAGE_2 = '2';
    const STAGE_NAMES = [
        self::STAGE_1 => 'Входное тестирование',
        self::STAGE_2 => 'Задания II этапа'
    ];


    const ROLE_MANAGER = 'manager';
    const ROLE_EDITOR = 'editor';
    const ROLE_EXPERT = 'expert';
    const ROLE_VERIFIER = 'verifier';
    const ROLE_APPEALS = 'appeals';

    const ROLE_NAMES = [
        self::ROLE_MANAGER =>   'Управление',
        self::ROLE_EDITOR =>    'Редактор',
        self::ROLE_EXPERT =>    'Эксперт',
        self::ROLE_VERIFIER =>  'Проверка заданий',
        self::ROLE_APPEALS =>   'Апелляционная комиссия',
    ];

    protected $fillable = [
        'profile_id',
        'track',
        'stage',
        'name', 'created_at', 'updated_at'];

    public function profile() {
        return $this->belongsTo(Profile::class);
    }

    public function attempts() {
        return $this->hasMany(Attempt::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function groups() {
        return $this->hasMany(Group::class);
    }

    public function getQuestionCountAttribute() {
        return $this->questions()->count();
    }


    public function getTrackNameAttribute() {
        return Arr::iif(self::TRACK_NAMES, $this->track);
    }

    public function getStageNameAttribute() {
        return Arr::iif(self::STAGE_NAMES, $this->stage);
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


        $filterByIds('profile_id');
        $filterByIds('track');
        $filterByIds('stage');

        if(!empty($filter['query'])){
            $lcQuery = '%' . mb_strtolower(trim($filter['query'])) . '%';
            if(is_numeric($filter['query'])){
                $query->where(function(Builder $query) use ($filter, $lcQuery){
                    $query->where('id', $filter['query'])
                        ->orWhereRaw('name like ?', $lcQuery)
                        ->orderByRaw('id = ? DESC', $filter['query']);
                });
            } else {
                $query
                    ->whereRaw('name like ?', $lcQuery);
            }
        }

        return $query;
    }

}
