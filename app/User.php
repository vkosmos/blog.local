<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    const IS_BANNED = 1;
    const IS_ACTIVE = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*
     *
     */

    public static function add($fields)
    {
        $user = new static;
        $user->fill($fields);
        $user-> save();
        return $user;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function generatePassword($password)
    {
        if (null != $password){
            $this->password = bcrypt($password);
            $this->save();
        }
    }

    public function remove()
    {
        $this->removeAvatar();
        $this->delete();
    }

    public function uploadAvatar($image)
    {
        if (null == $image){
            return false;
        }

        //Удаляем старую картинку, если была
        $this->removeAvatar();

        $filename = str_random(10) . '.' . $image->extension();
        //dd(get_class_methods($image));

        $image->storeAs('uploads', $filename);
        $this->avatar = $filename;
        $this->save();
    }

    public function removeAvatar()
    {
        if (null != $this->avatar){
            Storage::delete('uploads/' . $this->avatar);
        }
    }

    public function getImage()
    {
        if (null == $this->avatar){
            return '/img/no-user-image.jpg';
        }

        return '/uploads/' .  $this->avatar;
    }

    public function makeAdmin()
    {
        $this->is_admin = 1;
        $this->save();
    }

    public function makeNormal()
    {
        $this->is_admin = 0;
        $this->save();
    }

    public function toggleAdmin($value)
    {
        if (null == $value){
            $this->makeNormal();
        }
        else{
            $this->makeAdmin();
        }

    }

    public function ban()
    {
        $this->status = User::IS_BANNED;//1
        $this->save;
    }

    public function unban()
    {
        $this->statur = User::IS_ACTIVE;//0
        $this->save;
    }

    public function toggleBan($value)
    {
        if (null == $value){
            return $this->unban();
        }
        return $this->ban();

    }

    /*
     *
     */

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


}
