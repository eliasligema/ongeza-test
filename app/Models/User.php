<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    // soft
    // use SoftDeletes;
    
    /**
     * The attribute associated with primary key in the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = [
    //     'deleted_at'
    // ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'first_name',
        'second_name',
        'last_name',
        'username',
        'email',
        'phone',
        'profile_url',
        'password',
        'role',
        'remember_token',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
        });
    }
    
            
    public function getProfileUrlAttribute()
    {
        $url = asset('img/placeholder.png');

        if( isset($this->attributes['profile_url']) && !empty($this->attributes['profile_url']) ){
            if(strpos($this->attributes['profile_url'], 'http') === false){
                $url = asset('uploaded/'.$this->attributes['profile_url']);
            } else {
                $url = $this->attributes['profile_url'];
            }
        }

        return $url;
    }
}

