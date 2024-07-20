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


    public static function scopeForItem($builder, $subject){

        if($subject instanceof Model){
            $builder->where('item_type', '=', $subject->getMorphClass())
                ->where('item_id', '=', $subject->id);
            return $builder;

        } else if(is_string($subject)){
            if(array_key_exists($subject, Attachment::ITEM_CLASSES)){
                $subject = Attachment::ITEM_CLASSES[$subject];
            }
            if(class_exists($subject)){
                $subject = (new $subject);
                /** @var Model $subject */
                if($subject instanceof Model){
                    $builder->where('item_type', '=', $subject->getMorphClass())
                        ->whereIn('item_id', $subject->newQuery()->select('id'));
                    return $builder;
                }
            }
        } else if ($subject == null){
            $builder->whereNull('item_type', '=', $subject->getMorphClass())
                ->whereNull('item_id', $subject->newQuery()->select('id'));
        }


        throw new \Exception('Некорректный subject');

    }

}
