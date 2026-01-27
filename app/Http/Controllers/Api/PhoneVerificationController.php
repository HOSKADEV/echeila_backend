<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Models\PhoneVerification;
use App\Traits\TwilioTrait;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PhoneVerification\CheckPhoneRequest;
use App\Http\Requests\Api\PhoneVerification\SendOtpRequest;
use App\Http\Requests\Api\PhoneVerification\VerifyOtpRequest;

class PhoneVerificationController extends Controller
{
  use ApiResponseTrait, TwilioTrait;

  public function check_phone(CheckPhoneRequest $request)
  {
    $validated = $this->validateRequest($request);

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

  public function send_otp(SendOtpRequest $request)
  {
    $validated = $this->validateRequest($request);

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

  public function verify_otp(VerifyOtpRequest $request)
  {
    $validated = $this->validateRequest($request);

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
