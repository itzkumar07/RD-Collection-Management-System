<?php

namespace App\Http\Controllers\Web;

use App\Enums\Gender;
use App\Http\Controllers\Controller;
use App\Jobs\Web\SendPasswordResetMail;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Enum;

interface AuthInterface
{
    public function viewLogin();
    public function handleLogin(Request $request);
    public function viewForgotPassword();
    public function handleForgotPassword(Request $request);
    public function viewResetPassword($token);
    public function handleResetPassword(Request $request, $token);
}

class AuthController extends Controller implements AuthInterface
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * View Login
     *
     * @return mixed
     */
    public function viewLogin(): mixed
    {
        try {

            return view('web.pages.auth.login');
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Handle Login
     *
     * @return mixed
     */
    public function handleLogin(Request $request): mixed
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'min:10', 'max:100', 'exists:users'],
                'password' => ['required', 'string', 'min:1', 'max:20']
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if (Auth::attempt([
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ], $request->get('remember'))) {
                return redirect(RouteServiceProvider::HOME);
            }

            return redirect()->back()->withErrors([
                'password' => [
                    'Wrong password'
                ]
            ])->withInput($request->only('email', 'remember'));
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * View Register
     *
     * @return mixed
     */
    public function viewRegister(): mixed
    {
        try {

            $gender = Gender::class;

            return view('web.pages.auth.register', [
                'gender' => $gender
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
     * Handle Register
     *
     * @return mixed
     */
    public function handleRegister(Request $request): RedirectResponse
    {
        try {

            $validation = Validator::make($request->all(),[
                'name' => ['required', 'string', 'min:1', 'max:250'],
                'email' => ['required', 'string', 'email', 'unique:users', 'min:1', 'max:250'],
                'phone' => ['required', 'numeric', 'unique:users', 'digits:10'],
                'gender' => ['required', 'string', new Enum(Gender::class)],
                'date_of_birth' => ['required', 'string', 'min:1', 'max:250'],
                'aadhaar_card_no' => ['required', 'string', 'min:1', 'max:250'],
                'pan_card_no' => ['required', 'string', 'min:1', 'max:250'],
                'home' => ['nullable', 'string', 'min:1', 'max:250'],
                'street' => ['nullable', 'string', 'min:1', 'max:250'],
                'postal_code' => ['required', 'numeric', 'digits:6'],
                'city' => ['required', 'string', 'min:3', 'max:100'],
                'state' => ['required', 'string', 'min:3', 'max:100'],
                'country' => ['required', 'string', 'min:3', 'max:100'],
                'password' => ['required', 'string', 'min:6', 'max:20'],
            ]);
    
            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }
            
            $user = new User();
            $user->account_no = time();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->gender = $request->input('gender');
            $user->date_of_birth = $request->input('date_of_birth');
            $user->aadhaar_card_no = $request->input('aadhaar_card_no');
            $user->pan_card_no = $request->input('pan_card_no');
            $user->home = $request->input('home');
            $user->street = $request->input('street');
            $user->postal_code = $request->input('postal_code');
            $user->city = $request->input('city');
            $user->state = $request->input('state');
            $user->country = $request->input('country');
            $user->password = Hash::make($request->input('password'));
            $user->generateUserProfile();
            $user->save();

            Auth::login($user);

            return redirect()->to(RouteServiceProvider::HOME)->with('message', [
                'status' => 'success',
                'title' => 'Successfully Registred',
                'description' => 'Your registration is successfully completed'
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
     * View Forgot Password
     *
     * @return mixed
     */
    public function viewForgotPassword(): mixed
    {
        try {

            return view('web.pages.auth.forgot-password');
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Handle Forgot Password
     *
     * @return mixed
     */
    public function handleForgotPassword(Request $request): mixed
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'exists:users', 'min:6', 'max:100'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $token = Str::random(64);

            DB::table('password_reset_tokens')->where('email',  $request->input('email'))->delete();

            DB::table('password_reset_tokens')->insert([
                'email' => $request->input('email'),
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            $url = route('web.view.reset.password', ['token' => $token]);

            dispatch(new SendPasswordResetMail($request->input('email'), $url));

            return redirect()->back()->with('message', [
                'status' => 'success',
                'title' => 'Link sent',
                'description' => 'The password reset link sent to your email'
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
     * View Reset Password
     *
     * @return mixed
     */
    public function viewResetPassword($token): mixed
    {
        try {

            if (DB::table('password_reset_tokens')->where('token', $token)->exists()) {

                $email = DB::table('password_reset_tokens')->where('token', $token)->first()->email;

                return view('web.pages.auth.reset-password', [
                    'token' => $token,
                    'email' => $email
                ]);
            }

            return abort(404);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Handle Reset Password
     *
     * @return mixed
     */
    public function handleResetPassword(Request $request, $token): mixed
    {
        try {
            $validation = Validator::make($request->all(),[
                'email' => ['required', 'string', 'email', 'unique:users', 'min:1', 'max:250'],
                'password' => ['required', 'string', 'min:6', 'max:20','confirmed'],
            ]);
    
            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            if (DB::table('password_reset_tokens')->where(['email' => $request->email, 'token' => $token])->exists()) {

                $user = User::where('email', $request->input('email'))->first();
                $user->password = Hash::make($request->input('password'));
                $user->password_updated_at = Carbon::now();
                $user->update();

                DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

                return redirect()->route('web.view.login')->with('message', [
                    'status' => 'success',
                    'title' => 'Password set',
                    'description' => 'Your account password is successfully set.'
                ]);
            }

            return abort(404);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }
}
