<?php

namespace App\Models\Content;

use App\Models\Ordered;
use App\Models\Translable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @property int $id

 * @property int $category_id
 * @property string $status
 *
 * @property string $question
 * @property string $question_en
 * @property string $answer
 * @property string $answer_en
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 *
 * @property-read FaqCategory $category
 *
 * @mixin \Eloquent
 */
class Faq extends Model
{
    use HasFactory, Translable, Ordered;

    protected $fillable = ['status', 'category_id','question','question_en','answer','answer_en',
        'created_at','updated_at',];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $translable = ['question', 'answer'];

    protected static $orderedCategory = 'category_id';

    public function category(){
        return $this->belongsTo(FaqCategory::class);
    }

}
