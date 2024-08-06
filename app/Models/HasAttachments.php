<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read Attachment[] $attachments
 * @property int[] $attachment_ids
 *
 */
trait HasAttachments {

    public function initializeHasAttachmentsTrait(){
        $this->fillable[] = 'attachment_ids';
    }

    public function attachments(){
        return $this->morphMany(Attachment::class, 'item');
    }

    public function hasOneAttachment($type){
        return $this->morphOne(Attachment::class, 'item')->where('attachments.type', '=', $type)->take(1);
    }

    public function hasManyAttachments($type){
        return $this->attachments()->where('attachments.type', '=', $type);
    }

    public function getAttachmentIdsAttribute() {
        return $this->attachments()->pluck('type', 'id');
    }

    public function setAttachmentIdsAttribute($values) {
        if(\Arr::isList($values)){
            Attachment::find($values)->update([
                'item_type' => $this->getMorphClass(),
                'item_id' => $this->id,
            ]);
        } else {
            foreach ($values as $id => $type){
                Attachment::find($id)->update([
                    'item_type' => $this->getMorphClass(),
                    'item_id' => $this->id,
                    'type' => $type,
                ]);
            }
        }
    }

}
