<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ErrorLog extends Model
{
    use HasFactory;

    protected $table = 'error_log';
    protected $fillable = [
        'type',
        'message',
        'trace',
        'file',
        'line',
        'context',
    ];
}
