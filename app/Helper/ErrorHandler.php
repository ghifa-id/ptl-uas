<?php

namespace App\Helper;

use App\Models\ErrorLog;

class ErrorHandler
{
    public static function record($type ,$record)
    {
        try {
            if ($record instanceof \PDOException && property_exists($record, 'errorInfo')) {
                ErrorLog::create([
                    'type' => $type,
                    'message' => $record->errorInfo,
                    'trace' => null,
                    'file' => null,
                    'line' => null,
                    'context' => $record
                ]);
                return FormatResponse::send("error", null, $record->errorInfo, 400);
            } else {
                ErrorLog::create([
                    'type' => $type,
                    'message' => $record->getMessage(),
                    'trace' => $record->getTraceAsString() ?? null,
                    'file' => $record->getFile() ?? null,
                    'line' => $record->getLine() ?? null,
                    'context' => $record
                ]);
                return FormatResponse::send("error", null, $record->getMessage(), 400);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}