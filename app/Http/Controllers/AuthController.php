<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'username' => 'required',
                'address' => 'required',
                'contact_number' => 'required',
            ]);
            $imageName = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $image->getClientOriginalName();
                $image->storeAs('public/images', $imageName);
                $imagePath = 'storage/images/' . $imageName;
            }

            // Create the user record
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'image' => $imagePath, // Save the image path
            ]);

            $customer = Customer::create([
                'user_id' => $user->id,
                'username' => $validatedData['username'],
                'address' => $validatedData['address'],
                'contact_number' => $validatedData['contact_number'],
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function login(Request $request)
    {
        try {
            // Validate the input
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            // Check for validation errors
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password do not match our records.',
                ], 401);
            }

            if ($user->status === 'deactivated') {
                return response()->json([
                    'status' => false,
                    'message' => 'Your account is deactivated by the admin. Please contact support.',
                ], 403);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password do not match our records.',
                ], 401);
            }

            $redirectUrl = $user->role === 'admin' ? '/admin-dashboard' : '/shop';

            if ($user->status === 'inactive') {
                $user->status = 'active'; 
                $user->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Welcome Back!',
                    'token' => $user->createToken("API TOKEN")->plainTextToken,
                    'redirect_url' => $redirectUrl
                ], 200);
            }

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'redirect_url' => $redirectUrl
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->forget('api-token');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Log::info('User logged out', ['user' => $request->user()]);
        return response()->json(['message' => 'Successfully logged out']);
    }
}
