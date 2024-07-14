<?php

namespace App\Models\Content;

use App\Models\Attachment;
use App\Models\Translable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 *
 * @property string $status
 * @property string $date
 * @property string $title
 * @property string $title_en
 * @property string $summary
 * @property string $summary_en
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
class News extends Model {

    use SoftDeletes, Translable;

    protected $table = 'content_news';

    protected $fillable = [
        'status',
        'date',
        'title',
        'title_en',
        'summary',
        'summary_en',
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

    protected $translable = ['title','summary','content', 'seo_title','seo_description','seo_keywords'];

    public function attachments(){
        return $this->hasMany(Attachment::class, 'item_id')
            ->where('item_type', '=', Attachment::ITEM_TYPE_NEWS);
    }

    public function getSnippetAttribute() {
        return mb_substr(strip_tags($this->summary), 0, 250);
    }

    public static function scopeActive(Builder $query){
        return $query->where('status', '=', 'active')->orderByDesc('date');
    }

}
