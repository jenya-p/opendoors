<?php

namespace App\Models\Participant;

use App\Models\Dir\Citizenship;
use App\Models\Dir\Country;
use App\Models\Dir\KnowledgeArea;
use App\Models\EduLevel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property int $member_id     Участник
 * @property string $interests     Научные интересы
 * @property string $goals         Личные цели
 * @property string $achievements  Профессиональные достижения
 * @property string $motive        Причина выбора профиля
 * @property string $country       Причина выбора обучения в России
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read Member $member
 * @property-read KnowledgeArea[] $areas
 *
 * @property-read int $area_ids
 *
 * @mixin \Eloquent
 */
class Statement extends Model {
    use SoftDeletes;

    protected $table = 'participant_statements';

    protected $fillable = ['member_id','interests','goals','achievements','motive','country',
        'created_at','updated_at'];


    public function member() {
        return $this->belongsTo(Member::class);
    }

    public function areas(){
        return $this->belongsToMany(KnowledgeArea::class, 'participant_areas', 'statement_id', 'area_id')
            ->orderByPivot('id');
    }

    public function getAreaIdsAttribute(){
        return $this->areas()->pluck('dir_knowledge_areas.id')->toArray();
    }


}
