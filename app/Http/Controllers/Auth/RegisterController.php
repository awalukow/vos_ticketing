<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Penumpang;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\LaporanController;
use Mail;
use Exception;
use TCPDF;
use App\Mail\EmailNotification;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['string', 'max:255', 'unique:users'],
            'password' => ['nullable','string', 'min:8', 'confirmed'],
            'email' => ['string', 'max:255']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $password = $data['password'] ? $data['password'] : 'password12345678';
    
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => Hash::make($password),
            'level' => 'Penumpang',
            'email' => $data['email']
        ]);
    
        // Check if user creation was successful
        if ($user) {
            // Define $destination and $message for WA
            $laporanController = new LaporanController();
            $destination = $data['username']; // Replace with the destination number
            $message = '*[NOTIFIKASI VOS] REGISTRASI BERHASIL*
Anda telah berhasil melakukan pendaftaran akun sistem e-Ticket VOS
    
username: ' . $data['username'] . ' 
Password: ' . $data['password'] . ' 
Anda dapat melakukan perubahan password pada menu pengaturan '.url('/pengaturan').'
    
untuk informasi lebih lanjut hubungi: http://wa.me/6285823536364 (Jean) atau http://wa.me/6287780553668 (Tiara)'; 
    
            // Send email
            $emailData = [
                'subject' => '[VOS] Pendaftaran Berhasil!',
                'content' => $message
            ];
    
            //send command
            $response = $laporanController->sendWhatsAppMessage_2($destination, $message);
            Mail::to($data['email'])->send(new EmailNotification($emailData));
            
            return $user;
        } else {
            // Return an error if user creation fails
            return response()->json(['error' => 'User creation failed'], 500);
        }
    }
    
    
    
    public function showFastRegistrationForm()
    {
        return view('auth.fastRegister');
    }
}
