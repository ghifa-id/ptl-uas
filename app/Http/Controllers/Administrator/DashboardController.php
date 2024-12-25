<?php

namespace App\Http\Controllers\Administrator;

use App\Helper\ErrorHandler;
use App\Helper\FormatResponse;
use App\Helper\LogHandler;
use App\Http\Controllers\Controller;
use App\Models\VehicleReturn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    protected $vehicleReturn;

    public function __construct(VehicleReturn $vehicleReturns)
    {
        $this->vehicleReturn = $vehicleReturns;
    }

    public function index()
    {
        return view('pages.administrator.dashboard.index');
    }

    public function fetchDataWaitingDecision()
    {
        try {
            $dataWaiting = $this->vehicleReturn->orderBy('created_at', 'desc')->where('status', 'req_claim')->get();
            $data = count($dataWaiting) > 0;
            return FormatResponse::send(true, $data, "Ambil data yang perlu dilaporkan berhasil!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }

    public function datatable()
    {
        Carbon::setLocale('id');
        return DataTables::of($this->vehicleReturn->orderBy('created_at', 'desc')->where('status', 'req_claim')->select([
            'uuid',
            'application_id',
            'return_at',
            'fuel_used',
            'photo_receipt',
            'receipt_amount',
            'claim_decision_by',
            'claimed_at',
            'status',
        ]))
            ->addIndexColumn()
            ->addColumn('user', function ($row) {
                return $row->applicantId ? $row->applicantId->userId->name : null;
            })
            ->addColumn('returnAt', function ($row) {
                $date = Carbon::parse($row->return_at)->translatedFormat('d M Y');
                $time = Carbon::parse($row->return_at)->format('H:i:s');
                return '<div class="flex flex-col"><span>' . $date . '</span><span class="italic font-bold text-gray-500">' . $time . '</span></div>';
            })
            ->addColumn('receipt', function ($row) {
                return '<div class="relative flex justify-center"><a href="' . asset($row->photo_receipt) . '" target="_blank"><div class="absolute z-30 flex justify-center items-center w-24 h-24 bg-black/0 hover:bg-black/20 text-white/0 hover:text-white/80 transition-all duration-300"><i class="fa fa-search fa-1x" aria-hidden="true"></i></div><img src="' . asset($row->photo_receipt) . '" class="w-24 h-24 object-contain" alt="receipt"></a></div>';
            })
            ->addColumn('fuelUsed', function ($row) {
                return '<span>' . $row->fuel_used . ' Liter</span>';
            })
            ->addColumn('receiptAmount', function ($row) {
                return '<span>Rp. ' . number_format($row->receipt_amount, 0, ',', ',') . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="flex justify-center gap-3">
                        <button type="button" class="text-blue-500 text-2xl" data-mode="approved" data-id="' . $row->id . '">
                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['action', 'receipt', 'fuelUsed', 'receiptAmount', 'returnAt'])
            ->make(true);
    }

    public function approved(Request $request)
    {
        try {
            $approved = $this->vehicleReturn->findOrFail($request->uuid);
            $approved->claim_decision_by = Auth::id();
            $approved->claimed_at = Carbon::now();
            $approved->status = 'claimed';
            $approved->save();

            if ($approved) {
                LogHandler::activity([
                    'act_on' => 'vehicle return',
                    'activity' => 'approved claim BBM',
                    'detail' => 'approved claim BBM for ' . $approved->applicantId->userId->username
                ]);
            }

            return FormatResponse::send(true, $approved, "Pengajuan ini telah disetujui!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }
}
