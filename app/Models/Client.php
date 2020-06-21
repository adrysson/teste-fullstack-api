<?php

namespace App\Models;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Client extends Model
{

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['name', 'email', 'phone'];

    protected $appends = ['services_list'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
    ];

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

    public function services()
    {
        return $this->belongsToMany(Service::class, 'clients_services');
    }

    public function getServicesListAttribute()
    {
        return implode(', ', array_column($this->services->toArray(), 'name'));
    }
}
