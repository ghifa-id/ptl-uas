<?php

namespace App\Http\Controllers\Administrator;

use App\Helper\ErrorHandler;
use App\Helper\FormatResponse;
use App\Helper\LogHandler;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\DataTables;

class DepartmentController extends Controller
{
    protected $table;

    public function __construct(Department $table)
    {
        $this->table = $table;
    }
    public function index()
    {
        return view('pages.administrator.department.index');
    }
    public function datatable()
    {
        return DataTables::of($this->table->orderBy('created_at', 'desc')->select([
            'uuid',
            'code',
            'name',
            'status',
        ]))
            ->addIndexColumn()
            ->addColumn('statusCast', function ($row) {
                return $row->status ? 'Aktif' : 'Tidak Aktif';
            })
            ->addColumn('action', function ($row) {
                return '
                <div class="flex justify-center gap-2">
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

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|max:255|unique:department,code',
                'name' => 'required|string|max:255',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $store = new $this->table;
            $store->code = $request->code;
            $store->name = $request->name;
            $store->status = $request->status;
            $store->save();

            if ($store) {
                LogHandler::activity([
                    'act_on' => 'department',
                    'activity' => 'add new department',
                    'detail' => 'new data department with code ' . $request->code
                ]);
            }

            return FormatResponse::send(true, $store, "Registrasi berhasil!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|max:255|unique:department,code',
                'name' => 'required|string|max:255',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $store = $this->table->find($request->uuid);
            $store->code = $request->code;
            $store->name = $request->name;
            $store->status = $request->status;
            $store->save();

            if ($store) {
                LogHandler::activity([
                    'act_on' => 'department',
                    'activity' => 'update data department',
                    'detail' => 'update data department with code ' . $request->code
                ]);
            }

            return FormatResponse::send(true, $store, "Ubah data pengguna berhasil!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }

    public function destroy(Request $request)
    {
        try {

            $destroy = $this->table->findOrFail($request->uuid);
            $destroy->delete();

            if ($destroy) {
                LogHandler::activity([
                    'act_on' => 'user',
                    'activity' => 'remove data user',
                    'detail' => 'remove data user with username ' . $request->username
                ]);
            }

            return FormatResponse::send(true, $destroy, "Hapus data pengguna berhasil!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }
}
