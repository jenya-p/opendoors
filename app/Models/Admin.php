<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property array $roles
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read User $user
 *
 * @property-read array $role_names
 *
 * @mixin \Eloquent
 */
class Admin extends Model {
    use HasFactory, SoftDeletes;

    const ROLE_MANAGE_USERS = 'manage_users';
    const ROLE_MANAGE_SITE = 'manage_site';
    const ROLE_MANAGE_QUIZZES = 'manage_quizzes';
    const ROLE_MANAGE_PORTFOLIOS = 'manage_portfolios';
    const ROLE_MANAGE_INTERVIEW = 'manage_interview';

    const ROLES = [
        self::ROLE_MANAGE_USERS => 'Управление пользователями',
        self::ROLE_MANAGE_SITE => 'Управление сайтом',
        self::ROLE_MANAGE_QUIZZES => 'Управление тестами',
        self::ROLE_MANAGE_PORTFOLIOS => 'Управление портфолио',
        self::ROLE_MANAGE_INTERVIEW => 'Управление интервью'
    ];

    protected $table = 'user_admins';

    protected $fillable = [
        'id', 'roles', 'created_at', 'updated_at'];

    protected $casts = [
        'roles' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'id');
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

    public function hasRole($role){
        return in_array($role, $this->roles);
    }

}
