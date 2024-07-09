<?php

namespace App\Models\Content;

use App\Models\Translable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 *
 * @property string $url
 * @property string $title
 * @property string $title_en
 * @property string $content
 * @property string $content_en
 * @property string $seo_title
 * @property string $seo_title_en
 * @property string $seo_description
 * @property string $seo_description_en
 * @property string $seo_keywords
 * @property string $seo_keywords_en
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @method active()
 *
 * @prepoerty-read string $snippet
 *
 * @mixin \Eloquent
 */
class Content extends Model {

    use SoftDeletes, Translable;

    protected $table = 'content';

    protected $fillable = [
        'url',
        'title',
        'title_en',
        'content',
        'content_en',
        'seo_title',
        'seo_title_en',
        'seo_description',
        'seo_description_en',
        'seo_keywords',
        'seo_keywords_en',
        'created_at',
        'updated_at'
    ];


    protected $casts = [
        'date' => 'date:Y-m-d',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $translable = ['title','content', 'seo_title','seo_description','seo_keywords'];

    public function getSnippetAttribute() {
        return mb_substr(strip_tags($this->content), 0, 250);
    }

    public static function scopeActive(Builder $query){
        return $query->where('status', '=', 'active')->orderBy('date');
    }

}
