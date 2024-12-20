<?php

namespace App\Helper;

use App\Models\LogActivity;
use Illuminate\Support\Facades\Auth;

class LogHandler
{
    public static function activity($record)
    {
        try {
            LogActivity::create([
                'user_id' => Auth::id(),
                'act_on' => $record['act_on'],
                'activity' => $record['activity'] ?? null,
                'detail' => $record['detail'] ?? null,
            ]);
        } catch (\Throwable $th) {
            return ErrorHandler::record('error', $th);
        }
    }
}
