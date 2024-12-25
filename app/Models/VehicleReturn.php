<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;

class VehicleReturn extends Model
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
    protected $table = 'vehicle_return';

    protected $fillable = [
        'application_id',
        'return_at',
        'fuel_used',
        'photo_receipt',
        'receipt_amount',
        'claimed_at',
        'status',
    ];

    public function applicantId()
    {
        return $this->belongsTo(VehicleApplication::class, 'application_id', 'uuid');
    }
}
