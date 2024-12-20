<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;

class VehicleApplication extends Model
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
                $model->uuid = Uuid::uuid4();
            }
        });
    }

    protected $dates = ['deleted_at'];
    protected $table = 'vehicle_application';

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'application_detail',
        'start_booking',
        'end_booking',
        'status',
        'decided_by',
    ];

    public function userId()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    public function vehicleId()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'uuid');
    }

    public function decideBy()
    {
        return $this->belongsTo(User::class, 'decided_by', 'uuid');
    }
}
