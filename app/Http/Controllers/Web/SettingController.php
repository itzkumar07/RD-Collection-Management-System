<?php

namespace App\Http\Controllers\Web;


use App\Enums\Gender;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

interface SettingInterface
{
    public function viewSetting();
    public function viewAccountSetting();
    public function handleAccountSetting(Request $request);
    public function viewPasswordSetting();
    public function handlePasswordSetting(Request $request);
}

class SettingController extends Controller implements SettingInterface
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('logout');
    }

    /**
     * View Setting
     *
     * @return mixed
     */
    public function viewSetting(): mixed
    {
        try {

            return view('web.pages.setting.setting');
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * View Account Setting
     *
     * @return mixed
     */
    public function viewAccountSetting(): mixed
    {
        try {

            $user = User::find(auth()->user()->id);

            $genders = Gender::class;

            return view('web.pages.setting.account-setting', [
                'genders' => $genders,
                'user' => $user
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Handle Account Setting
     *
     * @return RedirectResponse
     */
    public function handleAccountSetting(Request $request): RedirectResponse
    {
        try {

            $validation = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:1', 'max:250'],
                'email' => [
                    'required', 'string', 'min:1', 'max:250',
                    Rule::unique('users')->ignore(auth()->user()->id, 'id')
                ],
                'phone' => [
                    'required', 'numeric', 'digits_between:10,20',
                    Rule::unique('users')->ignore(auth()->user()->id, 'id')
                ],
                'profile_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
                'date_of_birth' => ['nullable', 'string', 'min:1', 'max:50'],
                'gender' => ['nullable', 'string', 'min:1', 'max:50'],
                'account_password' => ['required', 'string', 'min:1', 'max:100'],
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            if (Hash::check($request->input('account_password'), auth()->user()->password)) {

                $user = User::find(auth()->user()->id);
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->phone = $request->input('phone');
                $user->gender = $request->input('gender');
                $user->date_of_birth = $request->input('date_of_birth');
                if ($request->hasFile('profile_image')) {
                    if (!is_null(auth()->user()->profile_image)) Storage::delete(auth()->user()->profile_image);
                    $user->profile_image = $request->file('profile_image')->store('users');
                }
                $user->update();

                return redirect()->back()->with('message', [
                    'status' => 'success',
                    'title' => 'Changes saved',
                    'description' => 'The changes are successfully saved'
                ]);
            }

            return redirect()->back()->withErrors([
                'account_password' => 'Incorrect password'
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * View Password Setting
     *
     * @return mixed
     */
    public function viewPasswordSetting(): mixed
    {
        try {

            return view('web.pages.setting.password-setting');
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Handle Password Setting
     *
     * @return RedirectResponse
     */
    public function handlePasswordSetting(Request $request): RedirectResponse
    {
        try {

            $validation = Validator::make($request->all(), [
                'current_password' => ['required', 'string', 'min:1', 'max:100'],
                'password' => ['required', 'string', 'min:6', 'max:20', 'confirmed'],
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            if (Hash::check($request->input('current_password'), auth()->user()->password)) {

                $user = User::find(auth()->user()->id);
                $user->password = Hash::make($request->input('password'));
                $user->update();

                return redirect()->back()->with('message', [
                    'status' => 'success',
                    'title' => 'Password updated',
                    'description' => 'The password is successfully updated'
                ]);
            }

            return redirect()->back()->withErrors([
                'current_password' => 'Current password not matched'
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }
}
