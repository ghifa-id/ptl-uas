<?php

namespace App\Http\Controllers\Manager;

use App\Helper\ErrorHandler;
use App\Helper\FormatResponse;
use App\Helper\LogHandler;
use App\Http\Controllers\Controller;
use App\Models\VehicleApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class BookingController extends Controller
{
    protected $table;

    public function __construct(VehicleApplication $table)
    {
        $this->table = $table;
    }

    public function index()
    {
        return view('pages.manager.booking.index');
    }

    public function datatable()
    {
        Carbon::setLocale('id');
        return DataTables::of($this->table->where('status', '!=', 'waiting')->orderBy('created_at', 'desc')->select([
            'uuid',
            'user_id',
            'vehicle_id',
            'application_detail',
            'start_booking',
            'end_booking',
            'status',
            'decided_by',
            'decided_at',
        ]))
            ->addIndexColumn()
            ->addColumn('user', function ($row) {
                return $row->userId ? $row->userId->name : null;
            })
            ->addColumn('merk', function ($row) {
                $merk = $row->vehicleId ? $row->vehicleId->merk : 'Error';
                $platNumber = $row->vehicleId ? $row->vehicleId->plat_number : 'Error';
                return '<div class="flex flex-col">
                    <span>' . $merk . '</span>
                    <span class="italic font-bold text-gray-500">' . $platNumber . '</span>
                </div>';
            })
            ->addColumn('applicationDetail', function ($row) {
                return '<div class="text-left"><span class="line-clamp-3">' . $row->application_detail . '</span></div>';
            })
            ->addColumn('startBooking', function ($row) {
                $date = Carbon::parse($row->start_booking)->translatedFormat('d M Y');
                $time = Carbon::parse($row->start_booking)->format('H:i:s');
                return '<div class="flex flex-col"><span>' . $date . '</span><span class="italic font-bold text-gray-500">' . $time . '</span></div>';
            })
            ->addColumn('endBooking', function ($row) {
                $date = Carbon::parse($row->end_booking)->translatedFormat('d M Y');
                $time = Carbon::parse($row->end_booking)->format('H:i:s');
                return '<div class="flex flex-col"><span>' . $date . '</span><span class="italic font-bold text-gray-500">' . $time . '</span></div>';
            })
            ->addColumn('statusCast', function ($row) {
                $status = [
                    'waiting' => 'Menunggu Persetujuan',
                    'cancel' => 'Dibatalkan oleh user',
                    'approved' => 'Disetujui',
                    'refused' => 'Ditolak',
                    'returned' => 'Kendaraan Dikembalikan',
                ];
                if ($row->status === "approved" || $row->status === "refused") {
                    return $status[$row->status] . ' oleh ' . $row->decideBy->name ?? "Error";
                } else {
                    return $status[$row->status] ?? "Error";
                }
            })
            ->addColumn('decidedAt', function ($row) {
                $date = Carbon::parse($row->decided_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y');
                $time = Carbon::parse($row->decided_at)->timezone('Asia/Jakarta')->format('H:i:s');
                if ($row->status === "approved" || $row->status === "refused" || $row->status === "returned") {
                    return '<div class="flex flex-col"><span>' . $date . '</span><span class="italic font-bold text-gray-500">' . $time . '</span></div>';
                } else {
                    return "-";
                }
            })
            ->rawColumns(['action', 'startBooking', 'endBooking', 'applicationDetail', 'merk', 'decidedAt'])
            ->make(true);
    }

    public function approved(Request $request)
    {
        try {
            $cancel = $this->table->findOrFail($request->uuid);
            $cancel->decided_by = Auth::id();
            $cancel->decided_at = Carbon::now();
            $cancel->status = 'approved';
            $cancel->save();

            if ($cancel) {
                LogHandler::activity([
                    'act_on' => 'vehicle booking',
                    'activity' => 'approved booking of vehicle',
                    'detail' => 'approved booking of vehicle by ' . Auth::user()->username
                ]);
            }

            return FormatResponse::send(true, $cancel, "Pengajuan ini telah disetujui!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }

    public function refused(Request $request)
    {
        try {
            $cancel = $this->table->findOrFail($request->uuid);
            $cancel->decided_by = Auth::id();
            $cancel->decided_at = Carbon::now();
            $cancel->status = 'refused';
            $cancel->save();

            if ($cancel) {
                LogHandler::activity([
                    'act_on' => 'vehicle booking',
                    'activity' => 'refuse booking of vehicle',
                    'detail' => 'refuse booking of vehicle by ' . Auth::user()->username
                ]);
            }

            return FormatResponse::send(true, $cancel, "Pengajuan ini berhasil ditolak!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }
}
