<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read Attachment[] $attachments
 */
trait HasAttachments {

    public function attachments(){
        return $this->morphMany(Attachment::class, 'item');
    }

    public function hasOneAttachment($type){
        return $this->morphOne(Attachment::class, 'item')->where('attachments.type', '=', $type)->take(1);
    }

    public function hasManyAttachments($type){
        return $this->attachments()->where('attachments.type', '=', $type);
    }

}
