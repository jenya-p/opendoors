<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property int $id
 * @property string $title
 * @property string $content
 *
 * @prepoerty-read string $snippet
 *
 * @mixin \Eloquent
 */
class Content extends Model
{
    protected $table = 'content';
    public $timestamps = false;
    protected $casts = ['id' => 'string'];

    protected $fillable = ['id','title','content'];


    public function getSnippetAttribute(){
        return mb_substr(strip_tags($this->content), 0, 250);
    }

}
