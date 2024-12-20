<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey ='uuid';
    public $incrementing = false;
    protected $keyType ='string';
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model){
            if (empty($model->uuid)) {
                $model->uuid = Uuid::uuid4()->toString();
            }
        });
    }

    protected $dates = ['deleted_at'];
    protected $table = 'department';

    protected $fillable = [
        'code',
        'name',
    ];
}
