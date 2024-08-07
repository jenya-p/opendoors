<?php

namespace App\Models\Dir;

use App\Models\Attachment;
use App\Models\HasAttachments;
use App\Models\Ordered;
use Carbon\Carbon;
use App\Models\Translable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $key           Кoд
 * @property string $name           Название
 * @property string $name_en        Название (En)
 * @property string $short_name     Короткое название
 * @property string $single         Одиночное
 * @property string $required       Обязательное
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 *
 * @mixin \Eloquent
 */
class Achievement extends Model {
    use HasFactory, SoftDeletes, Translable, Ordered, HasAttachments;

    protected $table = 'dir_achievements';

    protected $fillable = ['name','name_en', 'short_name', 'key', 'single', 'required', 'created_at', 'updated_at'];

    protected $translable = ['name'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];




}
