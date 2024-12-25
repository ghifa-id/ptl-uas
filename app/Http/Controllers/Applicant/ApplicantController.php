<?php

namespace App\Http\Controllers\Applicant;

use App\Helper\ErrorHandler;
use App\Helper\FormatResponse;
use App\Helper\LogHandler;
use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleApplication;
use App\Models\VehicleReturn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\DataTables;

class ApplicantController extends Controller
{
    protected $table;
    protected $vehicle;
    protected $vehicleReturn;

    public function __construct(VehicleApplication $table, VehicleReturn $vehicleReturns, Vehicle $vehicles)
    {
        $this->table = $table;
        $this->vehicleReturn = $vehicleReturns;
        $this->vehicle = $vehicles;
    }

    public function index()
    {
        $vehicle = $this->vehicle->where('status', true)->get();
        return view('pages.applicant.booking.index')->with([
            'vehicle' => $vehicle
        ]);
    }

    public function datatable()
    {
        Carbon::setLocale('id');
        return DataTables::of($this->table->orderBy('created_at', 'desc')->select([
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
                return $row->vehicleId ? $row->vehicleId->merk : null;
            })
            ->addColumn('plat_number', function ($row) {
                return $row->vehicleId ? $row->vehicleId->plat_number : null;
            })
            ->addColumn('startBook', function ($row) {
                return Carbon::parse($row->start_booking)->translatedFormat('d M Y H:i:s');;
            })
            ->addColumn('endBook', function ($row) {
                return Carbon::parse($row->end_booking)->translatedFormat('d M Y H:i:s');;
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
            ->addColumn('action', function ($row) {
                if (Auth::id() === $row->user_id && $row->status === 'waiting') {
                    return '
                    <div class="flex justify-center gap-3">
                        <button type="button" class="text-red-500 text-2xl" data-mode="cancel" data-id="' . $row->id . '">
                            <i class="fa fa-ban" aria-hidden="true"></i>
                        </button>
                    </div>';
                } else {
                    return '
                    <div class="flex justify-center gap-3">
                        <button type="button" class="text-blue-500 text-2xl" data-mode="detail" data-id="' . $row->id . '">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                        </button>
                    </div>';
                }
            })
            ->rawColumns(['action', 'startBooking', 'endBooking', 'decidedAt'])
            ->make(true);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'vehicle_id' => 'required',
                'application_detail' => 'required',
                'start_booking' => 'required',
                'end_booking' => 'required',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            if ($request->start_booking >= $request->end_booking) {
                return FormatResponse::send(false, null, 'Waktu pengembalian yang dipilih tidak valid, periksa waktu pengembalian yang anda kirimkan!', 400);
            }

            $checkApplicantWaiting = $this->table->where('user_id', Auth::id())->where('status', 'waiting')->first();
            if ($checkApplicantWaiting) {
                return FormatResponse::send(false, null, 'Pengajuan kamu sebelumnya masih menunggu keputusan!', 400);
            }

            $checkApplicantApproved = $this->table->where('user_id', Auth::id())->where('status', 'approved')->first();
            if ($checkApplicantApproved) {
                return FormatResponse::send(false, null, 'Harap selesaikan laporan pengembalian kendaraan sebelumnya agar dapat mengajukan penggunaan kendaraan kembali!', 400);
            }

            $checkApplicant = $this->table->where('user_id', Auth::id())
                ->where(function ($query) use ($request) {
                    $query->where('status', 'approved')
                        ->where(function ($query) use ($request) {
                            $query->where('start_booking', '<', $request->end_booking)
                                ->where('end_booking', '>', $request->start_booking);
                        });
                })->first();
            if ($checkApplicant) {
                return FormatResponse::send(false, null, 'Kamu memiliki pengajuan penggunaan kendaraan diwaktu yang sama!', 400);
            }

            $checkAvailable = $this->table->where('vehicle_id', $request->vehicle_id)
                ->where(function ($query) use ($request) {
                    $query->whereIn('status', ['waiting', 'approved'])
                        ->where(function ($query) use ($request) {
                            $query->where('start_booking', '<', $request->end_booking)
                                ->where('end_booking', '>', $request->start_booking);
                        });
                })->first();

            if ($checkAvailable) {
                $message = "Kendaraan yang anda pilih tidak tersedia pada waktu yang anda tentukan!";
                return FormatResponse::send(false, null, $message, 400);
            } else {
                $store = new $this->table;
                $store->user_id = Auth::id();
                $store->vehicle_id = $request->vehicle_id;
                $store->application_detail = $request->application_detail;
                $store->start_booking = $request->start_booking;
                $store->end_booking = $request->end_booking;
                $store->status = 'waiting';
                $store->save();

                if ($store) {
                    LogHandler::activity([
                        'act_on' => 'vehicle booking',
                        'activity' => 'add new data booking of vehicle',
                        'detail' => 'new data booking by ' . Auth::user()->username
                    ]);
                }

                return FormatResponse::send(true, $store, "Pengajuan berhasil di kirim, mohon tunggu persetujuan kepala bagian!", 200);
            }
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }

    public function cancel(Request $request)
    {
        try {
            $cancel = $this->table->findOrFail($request->uuid);
            $cancel->status = 'cancel';
            $cancel->save();

            if ($cancel) {
                LogHandler::activity([
                    'act_on' => 'vehicle booking',
                    'activity' => 'cancel booking of vehicle',
                    'detail' => 'cancel booking of vehicle by ' . Auth::user()->username
                ]);
            }

            return FormatResponse::send(true, $cancel, "Batalkan pengajuan berhasil!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }

    public function return()
    {
        return view('pages.applicant.return.index');
    }

    public function fetchDataVehicleNeedReport()
    {
        try {
            $data = $this->table->where('user_id', Auth::id())->where('status', 'approved')->first();
            return FormatResponse::send(true, $data, "Ambil data yang perlu dilaporkan berhasil!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }

    public function datatableReturn()
    {
        Carbon::setLocale('id');

        return DataTables::of(
            $this->vehicleReturn
                ->whereHas('applicantId', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->with('applicantId.vehicleId')
                ->orderBy('created_at', 'desc')
                ->select([
                    'uuid',
                    'application_id',
                    'return_at',
                    'fuel_used',
                    'photo_receipt',
                    'receipt_amount',
                    'claim_decision_by',
                    'claimed_at',
                    'status',
                ])
        )
            ->addIndexColumn()
            ->addColumn('photoReceipt', function ($row) {
                return asset($row->photo_receipt);
            })
            ->addColumn('merkPlatNumber', function ($row) {
                $merk = $row->applicantId->vehicleId->merk ?? null;
                $platNumber = $row->applicantId->vehicleId->plat_number ?? null;
                return '<div class="flex flex-col"><span>' . $merk . '</span><span class="italic font-bold text-gray-500">' . $platNumber . '</span></div>';
            })
            ->addColumn('returnAt', function ($row) {
                $date = Carbon::parse($row->return_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y');
                $time = Carbon::parse($row->return_at)->timezone('Asia/Jakarta')->format('H:i:s');
                return '<div class="flex flex-col"><span>' . $date . '</span><span class="italic font-bold text-gray-500">' . $time . '</span></div>';
            })
            ->addColumn('statusCast', function ($row) {
                $status = [
                    'req_claim' => 'Menunggu Pembayaran BBM',
                    'ready_to_claim' => 'Silahkan Ambil Uang BBM',
                    'returned' => 'Kendaraan Dikembalikan',
                    'claimed' => 'Uang BBM Sudah Diberikan',
                    'refused' => 'Pembayaran BBM Di Tolak',
                ];
                return $status[$row->status] ?? "Error";
            })
            ->addColumn('receiptAmount', function ($row) {
                return 'Rp. ' . number_format($row->receipt_amount, 0, ',', ',');
            })
            ->addColumn('action', function ($row) {
                return '
                <div class="flex justify-center gap-4">
                    <div class="flex justify-center gap-3">
                        <button type="button" class="text-blue-500 text-2xl" data-mode="detail" data-id="' . $row->uuid . '">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                ';
            })
            ->rawColumns(['action', 'returnAt', 'merkPlatNumber', 'receiptAmount'])
            ->make(true);
    }

    public function storeReport(Request $request)
    {
        try {
            if ($request->claim_bbm === "true") {
                $validator = Validator::make($request->all(), [
                    'application_id' => 'required',
                    'photo_receipt' => 'required|file|mimes:jpg,jpeg|max:10240',
                    'fuel_used' => 'required',
                    'receipt_amount' => 'required',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'application_id' => 'required',
                ]);
            }

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            if ($request->claim_bbm === "true") {
                $store = new $this->vehicleReturn;
                $store->application_id = $request->application_id;
                $store->return_at = Carbon::now();

                if ($request->hasFile('photo_receipt')) {
                    $imageName = time() . '.' . $request->photo_receipt->extension();
                    $request->photo_receipt->move(public_path('storage/receipts'), $imageName);
                    $store->photo_receipt = 'storage/receipts/' . $imageName;
                }

                $store->fuel_used = $request->fuel_used;
                $store->receipt_amount = $request->receipt_amount;
                $store->status = 'req_claim';
                $store->save();
            } else {
                $store = new $this->vehicleReturn;
                $store->application_id = $request->application_id;
                $store->return_at = Carbon::now();
                $store->status = 'returned';
                $store->save();
            }

            if ($store) {
                $vehicleApplication = $this->table->findOrFail($request->application_id);
                $vehicleApplication->status = 'returned';
                $vehicleApplication->save();

                LogHandler::activity([
                    'act_on' => 'vehicle return',
                    'activity' => 'add new report return of vehicle',
                    'detail' => $request->claim_bbm === "true" ? 'new data report return of vehicle with BBM claim - by ' . Auth::user()->username : 'new data report return of vehicle - by' . Auth::user()->username
                ]);
            }

            return FormatResponse::send(true, $store, "Pengajuan berhasil di kirim, mohon tunggu persetujuan kepala bagian!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }
}
