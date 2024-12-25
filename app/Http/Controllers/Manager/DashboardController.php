<?php

namespace App\Http\Controllers\Manager;

use App\Helper\ErrorHandler;
use App\Helper\FormatResponse;
use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    protected $table;
    protected $vehicle;

    public function __construct(VehicleApplication $table, Vehicle $vehicles)
    {
        $this->table = $table;
        $this->vehicle = $vehicles;
    }

    public function index()
    {
        return view('pages.manager.dashboard.index');
    }

    public function fetchDataWaitingDecision()
    {
        try {
            $bookingWaiting = $this->table->orderBy('created_at', 'desc')->where('status', 'waiting')->get();
            $data = count($bookingWaiting) > 0;
            return FormatResponse::send(true, $data, "Ambil data yang perlu dilaporkan berhasil!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }

    public function datatable()
    {
        Carbon::setLocale('id');
        return DataTables::of($this->table->orderBy('created_at', 'desc')->where('status', 'waiting')->select([
            'uuid',
            'user_id',
            'vehicle_id',
            'application_detail',
            'start_booking',
            'end_booking',
            'status',
            'decided_by',
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
            ->addColumn('action', function ($row) {
                if ($row->status === "waiting") {
                    return '
                    <div class="flex justify-center gap-3">
                        <button type="button" class="text-blue-500 text-2xl" data-mode="approved" data-id="' . $row->id . '">
                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                        </button>
                        <button type="button" class="text-red-500 text-2xl" data-mode="refused" data-id="' . $row->id . '">
                            <i class="fa fa-ban" aria-hidden="true"></i>
                        </button>
                    </div>';
                }
            })
            ->rawColumns(['action', 'startBooking', 'endBooking', 'applicationDetail', 'merk'])
            ->make(true);
    }
}
