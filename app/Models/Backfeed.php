<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property string $status
 * @property string $name
 * @property string $email
 * @property string $subject
 * @property string $body
 * @property string $comment

 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read User $user
 * @property-read Attachment[] $attachments
 * @property-read int $attachment_count
 *
 * @mixin \Eloquent
 */
class Backfeed extends Model
{
    use SoftDeletes;

    const STATUS_NEW = 'new';
    const STATUS_PROCESSED = 'processed';

    const STATUSES = [
        self::STATUS_NEW => 'Новый',
        self::STATUS_PROCESSED => 'Обработан',
    ];

    protected $fillable = ['user_id', 'status','name','email','subject','body', 'comment',
        'created_at', 'updated_at'
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function attachments(){
        return $this->hasMany(Attachment::class, 'item_id')
            ->where('item_type', '=', Attachment::ITEM_TYPE_BACKFEED);
    }

    public function getAttachmentCountAttribute(){
        return $this->attachments()->count();
    }

}
