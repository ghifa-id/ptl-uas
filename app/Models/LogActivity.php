<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogActivity extends Model
{
    use HasFactory;

    protected $table = 'log_activity';
    protected $fillable = [
        'user_id',
        'act_on',
        'activity',
        'detail',
    ];

    public function userId()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }
}
