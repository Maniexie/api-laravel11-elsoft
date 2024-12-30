<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private function getBrowserInfo()
    {
        return [
            'chrome' => true,
            'chrome_view' => false,
            'chrome_mobile' => false,
            'chrome_mobile_ios' => false,
            'safari' => false,
            'safari_mobile' => false,
            'msedge' => false,
            'msie_mobile' => false,
            'msie' => false,
        ];
    }

    private function getMachineInfo()
    {
        return [
            'brand' => 'Apple',
            'model' => '',
            'os_name' => 'Mac',
            'os_version' => '10.15',
            'type' => 'desktop',
        ];
    }

    private function getOsInfo()
    {
        return [
            'android' => false,
            'blackberry' => false,
            'ios' => false,
            'windows' => false,
            'windows_phone' => false,
            'mac' => true,
            'linux' => false,
            'chrome' => false,
            'firefox' => false,
            'gamingConsole' => false,
        ];
    }

    private function getOsNameInfo()
    {
        return [
            'name' => 'Mac',
            'version' => '10.15',
            'platform' => '',
        ];
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }


        return response()->json([
            'UserName' => $user->username,
            'Company' => $user->company,
            'browserInfo' => $this->getBrowserInfo(),
            'machineInfo' => $this->getMachineInfo(),
            'osInfo' => $this->getOsInfo(),
            'osNameInfo' => $this->getOsNameInfo(),
            'Device' => 'web_' . now()->timestamp,
            'Model' => 'Admin Web',
            'Source' => $request->ip(),
            'Exp' => 3,
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'company' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (User::where('username', $request->username)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'username already exists!',
            ], 422);
        }

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'email already exists!',
            ], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company' => $request->company
        ]);





        return response()->json([
            'UserName' => $user->username,
            'Password' => $request->password, // Jangan kembalikan plaintext password di produksi!
            'Company' => $user->company,
            'browserInfo' => $this->getBrowserInfo(),
            'machineInfo' => $this->getMachineInfo(),
            'osInfo' => $this->getOsInfo(),
            'osNameInfo' => $this->getOsNameInfo(),
            'Device' => 'web_' . now()->timestamp,
            'Model' => 'Admin Web',
            'Source' => $request->ip(),
            'Exp' => 3,
        ]);
    }
}
