<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        Log::info('Register attempt: ', $request->all());

        try {
            // Validate the request data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
                'role' => 'required|in:admin,supplier,vendor,manufacturer,wholesaler,retailer,customer',
            ]);

            // Start database transaction
            DB::beginTransaction();

            // Create the user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            DB::commit();

            Log::info('User created successfully', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ]);

            return redirect()->route('login')
                ->with('success', 'Registration successful! Please log in with your credentials.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed during registration', [
                'errors' => $e->errors(),
            ]);
            throw $e;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during user registration', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['error' => 'An error occurred during registration. Please try again.']);
        }
    }
}
