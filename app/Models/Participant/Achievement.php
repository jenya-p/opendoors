<?php

namespace App\Models\Participant;

use App\Models\HasAttachments;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Dir\Achievement as DirAchievement;


/**
 * @property int $id
 *
 * @property int $participant_id
 * @property int $type_id
 * @property array $content
 * @property string $name
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read Participant $participant
 * @property-read DirAchievement $type
 *
 * @mixin \Eloquent
 */
class Achievement extends Model {
    use SoftDeletes, HasAttachments;

    protected $table = 'participant_achievements';

    protected $fillable = ['participant_id', 'type_id', 'content', 'name', 'created_at', 'updated_at'];


    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'content' => 'array'
    ];

    public function participant() {
        return $this->belongsTo(Participant::class);
    }

    public function type() {
        return $this->belongsTo(DirAchievement::class);
    }

}
