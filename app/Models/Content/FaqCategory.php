<?php

namespace App\Models\Content;

use App\Models\Ordered;
use App\Models\Translable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * @property int $id
 *
 * @property string $name
 * @property string $name_en
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read Faq[] $faqs
 * @property-read int $faqs_count
 *
 * @mixin \Eloquent
 */
class FaqCategory extends Model
{
    use HasFactory, Translable, Ordered, SoftDeletes;

    protected $fillable = [
        'status', 'order', 'name','name_en', 'created_at','updated_at',];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $translable = ['name'];

    public function faqs(){
        return $this->hasMany(Faq::class, 'category_id');
    }

    public function getFaqsCountAttribute(){
        return $this->faqs()->count();
    }

}
