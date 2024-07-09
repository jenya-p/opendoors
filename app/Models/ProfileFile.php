<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $profile_id       Профиль
 * @property string $type          Тип
 * @property int $order            Порядок
 * @property string $status        Статус
 *
 * @property string $name          Название
 * @property string $name_en       Название (En)
 * @property string $summary       Описание
 * @property string $summary_en    Описание (En)
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read string $type_name
 *
 * @property-read Profile $profile
 * @property-read Attachment $file
 * @property-read Attachment $file_en
 *
 *
 *
 * @mixin \Eloquent
 */
class ProfileFile extends Model
{
    use HasFactory, Ordered, SoftDeletes, Translable;

    const TYPE_MATERIALS = 'materials';
    const TYPE_RESULTS = 'results';
    const TYPES = [
        self::TYPE_MATERIALS => 'Матреиалы',
        self::TYPE_RESULTS => 'Результаты',
    ];

    protected $fillable = ['profile_id','type','order','status',
        'name','name_en','summary','summary_en',
        'created_at','updated_at',];

    protected $translable = ['name', 'summary'];

    protected static $orderedCategory = ['profile_id', 'type'];

    public function profile(){
        return $this->belongsTo(Profile::class);
    }

    public function file(){
        return $this->belongsTo(Attachment::class, 'file_id');
    }

    public function file_en(){
        return $this->belongsTo(Attachment::class, 'file_en_id');
    }

    public function getTypeNameAttribute(){
        if (array_key_exists($this->type, self::TYPES)) {
            return self::TYPES[$this->type];
        } else {
            return $this->type;
        }
    }

}
