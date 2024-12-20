<?php

namespace App\Helper;

use App\Models\ErrorLog;
use Illuminate\Support\Facades\Auth;

class ErrorHandler
{
    public static function record($record, $method)
    {
        try {
            if ($record instanceof \PDOException && property_exists($record, 'errorInfo')) {
                ErrorLog::create([
                    'user_id' => Auth::id(),
                    'message' => $record->errorInfo,
                    'trace' => null,
                    'file' => null,
                    'line' => null,
                    'context' => $record
                ]);
                if ($method === 'response') {
                    return FormatResponse::send("error", null, $record->errorInfo, 400);
                } else {
                    return redirect()->back()->withErrors(['error' => $record->errorInfo]);
                }
            } else {
                ErrorLog::create([
                    'user_id' => Auth::id(),
                    'message' => $record->getMessage(),
                    'trace' => $record->getTraceAsString() ?? null,
                    'file' => $record->getFile() ?? null,
                    'line' => $record->getLine() ?? null,
                    'context' => $record
                ]);
                if ($method === 'response') {
                    return FormatResponse::send("error", null, $record->getMessage(), 400);
                } else {
                    return redirect()->back()->withErrors(['error' => $record->getMessage()]);
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
