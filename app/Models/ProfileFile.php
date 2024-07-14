<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

/**
 * @property int    $id
 * @property string $status                     Статус
 * @property int    $profile_id                 Трек
 * @property int    $type_id
 *
 * @property-read Profile           $profile
 * @property-read ProfileFileType   $type
 * @property-read Attachment        $file
 * @property-read Attachment        $file_en
 *
 *
 *
 * @mixin \Eloquent
 */
class ProfileFile extends Model
{
    use Translable, HasAttachments, FilterHelpers;

    public $timestamps = false;

    protected $fillable = ['profile_id','type_id', 'status','created_at','updated_at'];

    protected $translable = ['file'];

    public function profile(){
        return $this->belongsTo(Profile::class);
    }

    public function type(){
        return $this->belongsTo(ProfileFileType::class);
    }

    public function file(){
        return $this->hasOneAttachment('ru');
    }

    public function file_en(){
        return $this->hasOneAttachment('en');
    }


    public function scopeFilter(Builder $query, $filter): Builder {
        if($filter instanceof Request){
            $filter = $filter->only(['profile_id','profile_file_type_id', 'type','track_id']);
        }
        $this->filterByVal($query, $filter, 'profile_id');
        $this->filterByVal($query, $filter, 'profile_file_type_id');

        $pftFilter = \Arr::only($filter, ['type','track_id']);

        if( !empty($pftFilter)){
            $qubQuery = ProfileFileType::select('id')->filter($pftFilter);
            $query->whereIn('profile_file_type_id', $qubQuery);
        }

        return $query;
    }

}
