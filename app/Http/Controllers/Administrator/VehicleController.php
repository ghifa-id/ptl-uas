<?php

namespace App\Http\Controllers\Administrator;

use App\Helper\ErrorHandler;
use App\Helper\FormatResponse;
use App\Helper\LogHandler;
use App\Http\Controllers\Controller;
use App\Models\TypeVehicle;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\DataTables;

class VehicleController extends Controller
{
    protected $typeVehicle;
    protected $vehicle;

    public function __construct(TypeVehicle $typeVehicles, Vehicle $vehicles)
    {
        $this->typeVehicle = $typeVehicles;
        $this->vehicle = $vehicles;
    }

    public function index()
    {
        $type = $this->typeVehicle->orderBy('created_at', 'desc')->get();
        return view('pages.administrator.vehicle.index')->with([
            'type' => $type
        ]);
    }

    public function datatable()
    {
        return DataTables::of($this->vehicle->orderBy('created_at', 'desc')->select([
            'uuid',
            'type_id',
            'plat_number',
            'merk',
            'status',
        ]))
            ->addIndexColumn()
            ->addColumn('type', function ($row) {
                return $row->typeId ? $row->typeId->name : null;
            })
            ->addColumn('statusCast', function ($row) {
                return $row->status ? 'Aktif' : 'Tidak Aktif';
            })
            ->addColumn('action', function ($row) {
                return '
                <div class="flex justify-center gap-3">
                    <button type="button" class="text-blue-500 text-2xl" data-mode="edit" data-id="' . $row->id . '">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="text-red-500 text-2xl" data-mode="destroy" data-id="' . $row->id . '">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </div>';
            })
            ->rawColumns(['statusCast', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type_id' => 'required',
                'plat_number' => 'required|string|max:10',
                'merk' => 'required|string|max:255',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $store = new $this->vehicle;
            $store->type_id = $request->type_id;
            $store->plat_number = $request->plat_number;
            $store->merk = $request->merk;
            $store->status = $request->status;
            $store->created_by = Auth::id();
            $store->updated_by = null;
            $store->save();

            if ($store) {
                LogHandler::activity([
                    'act_on' => 'vehicle',
                    'activity' => 'add new vehicle',
                    'detail' => 'new data vehicle with plat number ' . $request->plat_number
                ]);
            }

            return FormatResponse::send(true, $store, "Tambah data berhasil!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type_id' => 'required',
                'plat_number' => 'required|string|max:10',
                'merk' => 'required|string|max:255',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $store = $this->vehicle->find($request->uuid);
            $store->type_id = $request->type_id;
            $store->plat_number = $request->plat_number;
            $store->merk = $request->merk;
            $store->status = $request->status;
            $store->updated_by = Auth::id();
            $store->save();

            if ($store) {
                LogHandler::activity([
                    'act_on' => 'vehicle',
                    'activity' => 'update data vehicle',
                    'detail' => 'update data vehicle with plat number ' . $request->plat_number
                ]);
            }

            return FormatResponse::send(true, $store, "Ubah data berhasil!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }

    public function destroy(Request $request)
    {
        try {

            $destroy = $this->vehicle->findOrFail($request->uuid);
            $destroy->status = "0";
            $destroy->save();
            $destroy->delete();

            if ($destroy) {
                LogHandler::activity([
                    'act_on' => 'vehicle',
                    'activity' => 'remove data vehicle',
                    'detail' => 'remove data vehicle with plat number ' . $destroy->plat_number
                ]);
            }

            return FormatResponse::send(true, $destroy, "Hapus data berhasil!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }

    public function typeVehicle()
    {
        return view('pages.administrator.vehicle.type');
    }

    public function datatableTypeVehicle()
    {
        return DataTables::of($this->typeVehicle->orderBy('created_at', 'desc')->select([
            'uuid',
            'name',
        ]))
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                <div class="flex justify-center gap-3">
                    <button type="button" class="text-blue-500 text-2xl" data-mode="edit" data-id="' . $row->id . '">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="text-red-500 text-2xl" data-mode="destroy" data-id="' . $row->id . '">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function storeTypeVehicle(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $store = new $this->typeVehicle;
            $store->name = $request->name;
            $store->save();

            if ($store) {
                LogHandler::activity([
                    'act_on' => 'vehicle',
                    'activity' => 'add new type vehicle',
                    'detail' => 'new data type vehicle with name ' . $request->name
                ]);
            }

            return FormatResponse::send(true, $store, "Tambah data berhasil!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }

    public function updateTypeVehicle(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $store = $this->typeVehicle->find($request->uuid);
            $store->name = $request->name;
            $store->save();

            if ($store) {
                LogHandler::activity([
                    'act_on' => 'vehicle',
                    'activity' => 'update data type vehicle',
                    'detail' => 'update data type vehicle with name ' . $request->name
                ]);
            }

            return FormatResponse::send(true, $store, "Ubah data berhasil!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }

    public function destroyTypeVehicle(Request $request)
    {
        try {

            $destroy = $this->typeVehicle->findOrFail($request->uuid);
            $destroy->delete();

            if ($destroy) {
                $vehicle = $this->vehicle->where('type_id', $request->uuid)->get();
                foreach($vehicle as $data) {
                    $data->status = "0";
                    $data->save();
                    $data->delete();
                }

                LogHandler::activity([
                    'act_on' => 'vehicle',
                    'activity' => 'remove data type vehicle',
                    'detail' => 'remove data type vehicle with name ' . $destroy->name
                ]);
            }

            return FormatResponse::send(true, $destroy, "Hapus data berhasil!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }
}
