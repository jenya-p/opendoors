<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Participant\Participant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $email                  Email
 * @property Carbon $email_verified_at      Email порверен
 * @property string $password               пароль
 * @property string $remember_token
 * @property string $locale                 Язык
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read $display_name
 * @property-read $role_names
 *
 * @property-read Admin $admin
 * @property-read UniversityUser[] $universityUsers
 * @property-read Participant[] $participants
 * @property-read Attachment[] $userpick
 *
 * @mixin \Eloquent
 *
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $appends = [
        'display_name'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getDisplayNameAttribute(){
        return empty(trim($this->name)) ? $this->email : $this->name;
    }


    public function admin(){
        return $this->hasOne(Admin::class, 'id', 'id');
    }

    public function universityUsers(){
        return $this->hasOne(UniversityUser::class, 'user_id', 'id');
    }

    public function participants(){
        return $this->hasOne(Participant::class, 'user_id', 'id');
    }

    public function userpick(){
        return $this->hasOne(Attachment::class, 'item_id')
            ->where('item_type', '=', Attachment::ITEM_TYPE_USERPICK);
    }

    public function getRoleNamesAttribute(){
        $ret = [];
        if($this->admin()->exists()){
            $ret['admin'] = 'Администратор';
        }
//        if(($count = $this->universityUsers()->count()) > 0){
//            $ret['university-user'] = 'Представитель ВУЗ' . ($count > 1 ? (' (' . $count . ')'): '');
//        }
        if(($count = $this->participants()->count()) > 0){
            $ret['participant'] = 'Участник' . ($count > 1 ? (' (' . $count . ')'): '');
        }

        return $ret;
    }


    public function roles(){
        return $this->hasMany(Role::class, 'user_id');
    }

    public function hasAnyRoleOf($item, $roles = null){
        $query = $this->roles()->forItem($item);
        if(!empty($roles)){
            if(is_string($roles)){
                $roles = [$roles];
            }
            $query->whereIn('role', $roles);
        }
        return $query->exists();
    }

}

