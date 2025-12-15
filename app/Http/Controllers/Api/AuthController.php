<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use App\Traits\FirebaseTrait;
use App\Traits\RandomTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Exception\FirebaseException;

class AuthController extends Controller
{
    use ApiResponseTrait, FirebaseTrait, RandomTrait;

    public function register(RegisterRequest $request)
    {
        $validated = $this->validateRequest($request);

        try {

            $firebase_user = $this->getFirebaseUser($request->id_token);

            if ($firebase_user instanceof FirebaseException) {
                throw new Exception($firebase_user->getMessage(), 422);
            }

            if ($firebase_user?->phoneNumber != $request->phone) {
                throw new Exception('Phone number does not match with Firebase user', 409);
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

            $uid = $firebase_user->uid;

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
                'uid' => $uid,
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

            $uid = $this->getFirebaseUserByPhone($request->phone)?->uid;

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
                'uid' => $uid,
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

    public function checkPhone(Request $request)
    {
        $validated = $this->validateRequest($request, [
            'phone' => 'required|string',
        ]);

        try {

            return $this->successResponse([
                'exists' => User::where('phone', $request->phone)->exists(),
                'phone' => $request->phone,
            ]);

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
            $user->delete();

            return $this->successResponse();

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
