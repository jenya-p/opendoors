<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id                // Трек
 * @property int $user_id           // Пользователь
 * @property int $edu_level_id      // Уровень образования
 *
 * @property-read User $user
 * @property-read EduLevel $eduLevel
 * @property-read StudentProfile $profiles
 *
 * @mixin \Eloquent
 */
class Student extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'students';

    protected $fillable = ['user_id', 'edu_level_id', 'citizenship', 'created_at', 'updated_at'];

    protected $casts = [
        'roles' => 'array'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function eduLevel(){
        return $this->belongsTo(EduLevel::class);
    }

    public function profiles(){
        return $this->hasMany(StudentProfile::class);
    }


}
