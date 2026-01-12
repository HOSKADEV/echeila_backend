<?php

namespace App\Http\Controllers\Api;

use App\Models\PhoneVerification;
use Exception;
use App\Models\User;
use App\Traits\RandomTrait;
use App\Traits\TwilioTrait;
use Illuminate\Http\Request;
use App\Traits\FirebaseTrait;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use Kreait\Firebase\Exception\FirebaseException;

class AuthController extends Controller
{
    use ApiResponseTrait, RandomTrait, TwilioTrait;

    public function register(RegisterRequest $request)
    {
        $validated = $this->validateRequest($request);

        try {

            if(!PhoneVerification::isVerified($request->phone)) {
                throw new Exception('Phone number is not verified', 407);
            }

            do {
                $username = 'ECH-'.now()->format('y')."-{$this->random(6, 'uppercase_alphanumeric')}";
            } while (User::where('username', $username)->exists());

            $user = User::create([
                'phone' => $request->phone,
                'username' => $username,
                'password' => Hash::make($request->password),
                'device_token' => $request->device_token,
            ]);

            $user->passenger()->create();

            $user->wallet()->create();

            $token = $user->createToken($this->random(8))->plainTextToken;

            $user->refresh()->load(
                'wallet',
                'passenger',
                'federation',
                'driver.federation',
                'driver.subscription',
                'driver.services',
                'driver.cards',
                'driver.vehicle.color',
                'driver.vehicle.model.brand'
            );

            return $this->successResponse([
                'token' => $token,
                'user' => new UserResource($user),
            ]);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function login(LoginRequest $request)
    {
        $validated = $this->validateRequest($request);

        try {

            $user = User::where('phone', $request->phone)->first();

            if (! Hash::check($request->password, $user->password)) {
                throw new Exception('Invalid credentials', 401);
            }

            if ($request->filled('device_token')) {
                $user->update(['device_token' => $request->device_token]);
            }

            $token = $user->createToken($this->random(8))->plainTextToken;

            $user->load(
                'wallet',
                'passenger',
                'federation',
                'driver.federation',
                'driver.subscription',
                'driver.services',
                'driver.cards',
                'driver.vehicle.color',
                'driver.vehicle.model.brand'
            );

            return $this->successResponse([
                'token' => $token,
                'user' => new UserResource($user),
            ]);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($user) {
                $request->user()->currentAccessToken()->delete();
                $user->update(['device_token' => null]);
            }

            return $this->successResponse();

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function user(Request $request)
    {
        try {

            $user = $request->user();

            $user->load(
                'wallet',
                'passenger',
                'federation',
                'driver.federation',
                'driver.subscription',
                'driver.services',
                'driver.cards',
                'driver.vehicle.color',
                'driver.vehicle.model.brand'
            );

            return $this->successResponse(new UserResource($user));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function resetPassword(Request $request)
    {
        $validated = $this->validateRequest($request, [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $user = $request->user();

            if (! Hash::check($request->old_password, $user->password)) {
                throw new Exception('Old password is incorrect', 401);
            }

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            // $user->tokens()->delete();

            return $this->successResponse();

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function forgetPassword(Request $request)
    {
        $validated = $this->validateRequest($request, [
            'id_token' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        try {

            $firebase_user = $this->getFirebaseUser($request->id_token);

            if ($firebase_user instanceof FirebaseException) {
                throw new Exception($firebase_user->getMessage(), 422);
            }

            $user = User::where('phone', $firebase_user->phoneNumber)->first();

            if (! $user) {
                throw new Exception('User not found', 404);
            }

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            // $user->tokens()->delete();

            return $this->successResponse();

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function deleteAccount(Request $request)
    {
        try {

            $user = $request->user();
            $user->tokens()->delete();
            $user->phoneVerifications()->delete();
            $user->delete();

            return $this->successResponse();

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
