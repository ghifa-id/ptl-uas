<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;

class Vehicle extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';
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
    protected $table = 'vehicle';

    protected $fillable = [
        'type_id',
        'plat_number',
        'merk',
        'status',
        'created_by',
        'updated_by',
    ];

    public function typeId()
    {
        return $this->belongsTo(TypeVehicle::class, 'type_id', 'uuid');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'uuid');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'uuid');
    }
}
