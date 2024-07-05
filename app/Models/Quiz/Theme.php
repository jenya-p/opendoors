<?php

namespace App\Models\Quiz;

use App\Models\Ordered;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $name
 *
 * @property-read int $group_count
 *
 * @preperty-read Group[]  $groups
 *
 * @mixin \Eloquent
 */
class Theme extends Model
{
    public $timestamps = false;

    protected $table = 'quiz_themes';

    protected $fillable = ['name'];

    public function groups(){
        return $this->hasMany(Group::class);
    }


    public function getQGroupCountAttribute(){
        return $this->groups()->count();
    }

}
