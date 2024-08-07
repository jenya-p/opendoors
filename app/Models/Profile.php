<?php

namespace App\Models;

use App\Models\Dir\KnowledgeArea;
use App\Models\Quiz\Quiz;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property int $order
 * @property string $status     Статус
 * @property int $coordinator_id    Координатор
 *
 * @property string $name          Название
 * @property string $name_en       Название (En)
 * @property string $icon          Иконка
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at

 * @property-read University $coordinator
 * @property-read Stage[] $stages
 * @property-read UniversityProfile[] $universityProfiles
 * @property-read KnowledgeArea[] $areas
 *
 * @mixin \Eloquent
 */
class Profile extends Model {
    use HasFactory, SoftDeletes, Ordered, Translable;

    protected $table = 'profiles';

    protected $fillable = ['order', 'status', 'coordinator_id', 'name', 'name_en', 'icon', 'created_at', 'updated_at'];

    protected $translable = ['name'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function stages(){
        return $this->hasMany(Stage::class);
    }

    public function coordinator(){
        return $this->belongsTo(University::class);
    }

    public function universityProfiles(){
        return $this->hasMany(UniversityProfile::class, 'profile_id', 'id');
    }

    public function areas(){
        return $this->belongsToMany(KnowledgeArea::class, 'profile_areas', 'profile_id', 'area_id');
    }

    public function files(){
        return $this->hasMany(ProfileFile::class, 'profile_id', 'id');
    }

    public function getFilesOfType($type){
        return $this->files()
            ->select('profile_files.*')
            ->with('file', 'file_en', 'type', 'type.tracks')
            ->join('profile_file_types',  'profile_file_types.id','=','profile_files.type_id')
            ->where('profile_file_types.type', '=', $type)
            ->orderBy('profile_file_types.order')->get();
    }

    public static function scopeActive(Builder $query){
        return $query->where('status', '=', 'active');
    }

    public function scopeAvailable(Builder $query) {
        if(\Gate::check('admin')){
            return;
        }
        $idSelect = \Auth::user()->roles()->forItem(Quiz::class)
            ->join('quizzes', 'quizzes.id', '=', 'roles.item_id', 'inner')
            ->select('quizzes.profile_id');
        $query->whereIn('id', $idSelect);
    }

}
