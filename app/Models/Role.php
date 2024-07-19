<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $item_type
 * @property int $item_id
 * @property int $user_id
 * @property string $role
 *
 *
 * @property-read User $user
 * @property-read Item $item
 *
 * @mixin \Eloquent
 */
class Role extends Model
{
    public $timestamps = false;
    protected $fillable = ['item_type','item_id','user_id','role',];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function item(){
        return $this->morphTo();
    }
}
