<?php

namespace App\Models;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Contract extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = ['client', 'term', 'pages'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($obj) {
            $obj->id = Uuid::uuid4();
            $obj->user_id = auth()->user()->id;
        });
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new UserScope);
    }
}
