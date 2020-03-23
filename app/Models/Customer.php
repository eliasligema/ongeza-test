<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
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
    protected $table = 'customer';
    
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
        'last_name',
        'town_name',
        'gender_id',
        'is_deleted',
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
    
            
    public function getIsDeletedAttribute()
    {
        if( isset($this->attributes['is_deleted']) && !empty($this->attributes['is_deleted']) ){
            return ($this->attributes['is_deleted'] == 1);
        }

        return false;
    }
}

