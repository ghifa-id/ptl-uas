<?php

namespace App\Http\Controllers\Administrator;

use App\Helper\ErrorHandler;
use App\Helper\FormatResponse;
use App\Helper\LogHandler;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    protected $table;
    protected $department;

    public function __construct(User $table, Department $departments)
    {
        $this->table = $table;
        $this->department = $departments;
    }
    public function index()
    {
        $department = $this->department->where('status', true)->orderBy('created_at', 'ASC')->get();
        return view('pages.administrator.users.index')->with([
            'department' => $department
        ]);
    }
    public function datatable()
    {
        return DataTables::of($this->table->where('uuid', '!=', Auth::id())->where('role','!=','superuser')->orderBy('created_at', 'desc')->select([
            'uuid',
            'name',
            'username',
            'email',
            'first_password',
            'phone_number',
            'department_id',
            'role',
            'status',
        ]))
            ->addIndexColumn()
            ->addColumn('department', function ($row) {
                return $row->department_id ? $row->departmentId->name : null;
            })
            ->addColumn('roleCast', function ($row) {
                $roles = [
                    'staff' => 'Staff',
                    'kasubag' => 'Kepala Bagian',
                    'bendahara' => 'Bendahara',
                ];
                return $roles[$row->role] ?? 'Error';
            })
            ->addColumn('statusCast', function ($row) {
                if ($row->status === 'set_password') {
                    return '<button type="button" id="showInfoLogin" class="text-green-500 text-2xl" data-id="' . $row->id . '">
                        <i class="fa fa-address-card-o" aria-hidden="true"></i>
                    </button>';
                } else {
                    return $row->status !== 'inactive' ? 'Aktif' : 'Tidak Aktif';
                }
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
            // return FormatResponse::send(true, null, "tes", 400);
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username|regex:/^\S*$/',
                'email' => 'required|string|email|max:255|unique:users,email',
                'phone_number' => 'required|string|max:15|regex:/^[0-9\-\+]*$/',
                'department_id' => 'required|exists:department,uuid',
                'role' => 'required|in:bendahara,kasubag,staff',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            function generateRandomString($length = 8)
            {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                while (strlen($randomString) < $length) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }

                $randomString = substr($randomString, 0, $length);
                return $randomString;
            }

            $randomString = generateRandomString();

            $store = new $this->table;
            $store->name = $request->name;
            $store->username = $request->username;
            $store->email = $request->email;
            $store->phone_number = $request->phone_number;
            $store->department_id = $request->department_id;
            $store->password = bcrypt($randomString);
            $store->first_password = $randomString;
            $store->role = $request->role;
            $store->save();

            if ($store) {
                LogHandler::activity([
                    'act_on' => 'user',
                    'activity' => 'register new user',
                    'detail' => 'new data user with username ' . $request->username
                ]);
            }

            return FormatResponse::send(true, ['record' => $store, 'act' => 'store'], "Registrasi berhasil!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'phone_number' => 'required|string|max:15|regex:/^[0-9\-\+]*$/',
                'department_id' => 'required|exists:department,uuid',
                'role' => 'required|in:bendahara,kasubag,staff',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $store = $this->table->find($request->uuid);
            $store->name = $request->name;
            $store->email = $request->email;
            $store->phone_number = $request->phone_number;
            $store->department_id = $request->department_id;
            $store->role = $request->role;
            if($store->status !== 'set_password') {
                $store->status = $request->status;
                $store->update();
            } else {
                $store->update();
            }

            if ($store) {
                LogHandler::activity([
                    'act_on' => 'user',
                    'activity' => 'update data user',
                    'detail' => 'update data user with username ' . $store->username
                ]);
            }

            return FormatResponse::send(true, ['record' => $store, 'act' => 'update'], "Ubah data pengguna berhasil!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }

    public function destroy(Request $request)
    {
        try {

            $destroy = $this->table->findOrFail($request->uuid);
            if($destroy->status !== 'set_password') {
                $destroy->status = 'inactive';
                $destroy->save();
            }
            $destroy->delete();

            if ($destroy) {
                LogHandler::activity([
                    'act_on' => 'user',
                    'activity' => 'remove data user',
                    'detail' => 'remove data user with username ' . $destroy->username
                ]);
            }

            return FormatResponse::send(true, $destroy, "Hapus data pengguna berhasil!", 200);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'response');
        }
    }
}
