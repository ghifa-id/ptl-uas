<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;

class LogActivity extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

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

    protected $date = ['deleted_at'];

    protected $fillable = [
        'id',
        'user_id',
        'act_on',
        'activity',
        'detail',
    ];
}
