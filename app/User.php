<?php

namespace App;


use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    // use Notifiable, HasApiTokens, SoftDeletes;
    use HasApiTokens, SoftDeletes;

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;
    public const STATUS_BANNED = 2;

    public const ROLE_ADMIN = 1;
    public const ROLE_MANAGER = 2;

    public const STRONG_PASSWORD = "regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/";

    protected $primaryKey = 'id';

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'first_name', 
            'second_name', 
            'last_name', 
            'email', 
            'phone', 
            'password',
            'type',
            'profile_url',
        ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $appends = [
        'profile_image'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->user_id = (string) Str::uuid();
        });
    }

    public static function scopeActive($query) {
        return $query->where('status', self::STATUS_ACTIVE);
    }
    
    public static function scopeInactive($query) {
        return $query->where('status', self::STATUS_INACTIVE);
    }
    
    public static function scopeBanned($query) {
        return $query->where('status', self::STATUS_BANNED);
    }

    public function getProfileImageAttribute()
    {
        $url = asset('img/avatar-placeholder.jpg');

        if( isset($this->attributes['profile_url']) && !empty(trim($this->attributes['profile_url']))) {
            if(strpos('http', $this->attributes['profile_url']) === false){
                $url = asset('uploaded/'.$this->attributes['profile_url']);
            } else {
                $url = $this->attributes['profile_url'];
            }
        }

        return $url;
    }


    public function get_profile()
    {
        $value = '<img src="'.$this->profile_image.'" class="rounded img-profile rounded-circle border border-dark w-100 h-100">';

        return $value;
    }

    public function get_profile_image()
    {
        return $this->profile_image;
    }

    public function get_profile_card($truncate = false)
    {
        $profile = $this->get_profile();
        $fullname = $this->fullname();
        $email = null;
        $truncate_class = null;

        if($this->attributes['email'] || trim($this->attributes['email']) != "" ){
            $email = $this->attributes['email'];
        }

        if($truncate) {
            $truncate_class = 'text-truncate col-12';
        }

        $value = <<<EOT
        <div class="media">
            <div class="media-image-sm align-self-center mr-2 w-100" style="max-width:30px;min-width:30px;max-height:30px;min-height:30px;">
                $profile
            </div>
            
            <div class="media-body text-left">
                <span class="d-inline-block $truncate_class p-0" style="max-width:150px;">$fullname</span>
                <div class="font-italic text-light small">$email</div>
            </div>
        </div>
EOT;

        return $value;
    }

    public function fullname() {
        
        if (isset($this->attributes['first_name']) && isset($this->attributes['last_name'])){
            return $this->attributes['first_name'].' '.$this->attributes['second_name'].' '.$this->attributes['last_name'];
        }

        return $this->phone_hint();
    }

}
