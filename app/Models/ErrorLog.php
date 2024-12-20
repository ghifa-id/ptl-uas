<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ErrorLog extends Model
{
    use HasFactory;

    protected $table = 'error_log';
    protected $fillable = [
        'user_id',
        'message',
        'trace',
        'file',
        'line',
        'context',
    ];

    protected $casts = [
        'context' => 'array',
    ];

    public function userId()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }
}
