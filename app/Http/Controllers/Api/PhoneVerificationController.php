<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Models\PhoneVerification;
use App\Traits\TwilioTrait;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;

class PhoneVerificationController extends Controller
{
  use ApiResponseTrait, TwilioTrait;

  public function check_phone(Request $request)
  {
    $validated = $this->validateRequest($request, [
      'phone' => 'required|string',
    ]);

    try {
      $phoneNumber = $request->phone;

      return $this->successResponse([
        'phone' => $phoneNumber,
        'exists' => User::where('phone', $phoneNumber)->exists(),
        'verified' => PhoneVerification::isVerified($phoneNumber),
      ]);

    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
    }
  }

  public function send_otp(Request $request)
  {
    $validated = $this->validateRequest($request, [
      'phone' => 'required|string',
    ]);

    try {
      $phoneNumber = $request->phone;

      $status = $this->sendOtp($phoneNumber);

      return $this->successResponse([
        'status' => $status,
        'message' => 'OTP sent successfully',
        'phone' => $phoneNumber,
      ]);

    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
    }
  }

  public function verify_otp(Request $request)
  {
    $validated = $this->validateRequest($request, [
      'phone' => 'required|string',
      'code' => 'required|string',
    ]);

    try {
      $phoneNumber = $request->phone;
      $code = $request->code;

      $isValid = $this->verifyOTP($phoneNumber, $code);

      if (!$isValid) {
        throw new Exception('Invalid OTP code', 422);
      }

      PhoneVerification::create([
        'phone_number' => $phoneNumber,
        'verified_at' => now(),
        'expires_at' => now()->addMinutes(15), // Valid for 15 minutes
      ]);

      return $this->successResponse([
        'phone' => $phoneNumber,
        'verified' => true,
        'message' => 'Phone number verified successfully',
      ]);

    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
    }
  }
}
