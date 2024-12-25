<?php

namespace App\Http\Controllers\Auth;

use App\Helper\ErrorHandler;
use App\Helper\LogHandler;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    public function firstChange()
    {
        if (Auth::user()->status === 'set_password') {
            return view('auth.change_password');
        } else {
            return redirect()->back()->withErrors(['warning' => 'Anda tidak dapat mengakses halaman ini!']);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:8|max:255|confirmed',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $user = User::find(Auth::id());
            $user->password = bcrypt($request->password);
            if ($user->first_password) {
                $user->first_password = null;
                $user->status = 'active';
                $proced = $user->save();
            } else {
                $proced = $user->save();
            }

            if ($proced) {
                LogHandler::activity([
                    'act_on' => 'password',
                    'activity' => 'change password',
                    'detail' => 'user ' . Auth::user()->username . ' password has been change'
                ]);
            }

            $roles = config('roles');
            if (Auth::user()->role !== "staff") {
                return redirect()->route($roles[Auth::user()->role] . '.dashboard.index')->with(['success' => 'Kata sandi berhasil diubah!']);
            } else {
                return redirect()->route('applicant.booking.index')->with(['success' => 'Kata sandi berhasil diubah!']);
            }
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'redirect');
        }
    }

    public function skipChange()
    {
        try {
            $proced = null;
            $user = User::find(Auth::id());
            if ($user->status === 'set_password') {
                $user->first_password = null;
                $user->status = 'active';
                $proced = $user->save();
            }

            if ($proced) {
                LogHandler::activity([
                    'act_on' => 'password',
                    'activity' => 'skip change password',
                    'detail' => 'user ' . Auth::user()->username . ' skip change first password'
                ]);
            }

            $roles = [
                'staff' => 'applicant',
                'kasubag' => 'manager',
                'bendahara' => 'administrator',
                'superuser' => 'superuser'
            ];

            return redirect()->route($roles[Auth::user()->role] . '.dashboard.index')->withErrors(['warning' => 'Anda melewati perubahan password pertama anda!']);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'redirect');
        }
    }

    public function profile()
    {
        $user = User::find(Auth::id());
        return view('auth.profile')->with([
            "user" => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'phone_number' => 'required|string|max:15|regex:/^[0-9\-\+]*$/',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $emailCheck = User::where('uuid', '!=', Auth::id())->where('email', $request->email)->first();
            if (!$emailCheck) {
                $user = User::find(Auth::id());
                $user->name = $request->name;
                $user->email = $request->email;
                $user->phone_number = $request->phone_number;
                $proced = $user->save();

                if ($proced) {
                    LogHandler::activity([
                        'act_on' => 'profile',
                        'activity' => 'update profile',
                        'detail' => 'user ' . Auth::user()->username . ' update profile'
                    ]);
                }
                return redirect()->back()->with(['success' => 'Kata sandi berhasil diubah!']);
            } else {
                return redirect()->back()->withErrors(['error' => 'The email has already been taken.']);
            }
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'redirect');
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'old_password' => 'required|string|min:8|max:255',
                'password' => 'required|string|min:8|max:255|confirmed',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $user = User::find(Auth::id());
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = bcrypt($request->password);
                if ($user->first_password) {
                    $user->first_password = null;
                    $proced = $user->save();
                } else {
                    $proced = $user->save();
                }
            } else {
                return redirect()->back()->withErrors(['error' => 'Kata sandi lama anda salah!']);
            }

            if ($proced) {
                LogHandler::activity([
                    'act_on' => 'password',
                    'activity' => 'change password',
                    'detail' => 'user ' . Auth::user()->username . ' password has been change'
                ]);
            }

            return redirect()->back()->with(['success' => 'Kata sandi berhasil diubah!']);
        } catch (\Throwable $th) {
            return ErrorHandler::record($th, 'redirect');
        }
    }
}
