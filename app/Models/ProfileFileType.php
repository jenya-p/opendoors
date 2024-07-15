<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    $id
 * @property string $type          Тип
 * @property int    $order         Порядок
 * @property int    $track_id      Трек
 *
 * @property string $name          Название
 * @property string $name_en       Название (En)
 * @property string $summary       Описание
 * @property string $summary_en    Описание (En)
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read string $type_name
 * @property-read string $file_count
 * @property-read string $can_delete
 *
 * @property-read Track[] $tracks
 * @property-read ProfileFile[] $files
 *
 * @mixin \Eloquent
 */
class ProfileFileType extends Model
{
    use Ordered, SoftDeletes, Translable, FilterHelpers;

    const TYPE_MATERIALS = 'materials';
    const TYPE_RESULTS = 'results';
    const TYPES = [
        self::TYPE_MATERIALS => 'Материалы',
        self::TYPE_RESULTS => 'Результаты',
    ];

    protected $fillable = ['track_id','type','order',
        'name','name_en','summary','summary_en',
        'created_at','updated_at',];

    protected $translable = ['name', 'summary'];

    protected static $orderedCategory = ['type'];

    public function tracks(){
        return $this->belongsToMany(Track::class, 'profile_file_tracks', 'type_id', 'track_id');
    }

    public function files(){
        return $this->hasMany(ProfileFile::class, 'type_id');
    }

    public function getTypeNameAttribute(){
        return \Arr::iif(self::TYPES, $this->type);
    }

    public function getFileCountAttribute(){
        return $this->files()->count();
    }

    public function getCanDeleteAttribute(){
        return $this->file_count == 0;
    }

    public function scopeFilter(Builder $query, $filter): Builder {

        $this->filterByVal($query, $filter, 'type');
        $this->filterByVal($query, $filter, 'track_id');

        return $query;
    }


}
