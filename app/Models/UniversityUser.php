<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id                // Трек
 * @property int $university_id     // Университет
 * @property int $user_id           // Пользователь
 *
 * @property array $roles
 *
 *
 * @property-read User $user
 * @property-read University $university
 *
 * @property-read array $role_names
 *
 * @mixin \Eloquent
 */
class UniversityUser extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'university_users';

    protected $fillable = ['user_id', 'university_id', 'roles', 'university_id'];

    const ROLE_MANAGE_USERS =       'manage_users';
    const ROLE_MANAGE_SITE =        'manage_site';
    const ROLE_MANAGE_INTERVIEW =   'manage_interview';
    const ROLE_INTERVIEWER =        'interviewer';

    const ROLES = [
        self::ROLE_MANAGE_USERS =>      'Управление пользователями',
        self::ROLE_MANAGE_SITE =>       'Управление сайтом',
        self::ROLE_MANAGE_INTERVIEW =>  'Управление интервью',
        self::ROLE_INTERVIEWER =>       'Интервьюер'
    ];

    protected $casts = [
        'roles' => 'array'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function university(){
        return $this->belongsTo(University::class);
    }

    public function getRoleNamesAttribute(){
        $ret = [];
        foreach ($this->roles as $role){
            if(array_key_exists($role, self::ROLES)){
                $ret[$role] = self::ROLES[$role];
            } else {
                $ret[$role] = $role;
            }
        }
        return $ret;
    }
}
