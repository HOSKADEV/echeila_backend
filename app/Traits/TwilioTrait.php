<?php
namespace App\Traits;

use Twilio\Rest\Client;

trait TwilioTrait{

  public function sendOTP($toPhoneNumber){

    $client = new Client(getenv("TWILIO_ACCOUNT_SID"), getenv("TWILIO_AUTH_TOKEN"));
    $verification = $client->verify->v2
      ->services(getenv("TWILIO_VERIFY_SERVICE_SID"))
      ->verifications->create(
        $toPhoneNumber,
        "sms"
      );

    return $verification->status;
  }

  public function verifyOTP($toPhoneNumber, $code){
    $client = new Client(getenv("TWILIO_ACCOUNT_SID"), getenv("TWILIO_AUTH_TOKEN"));
    $verification_check = $client->verify->v2
      ->services(getenv("TWILIO_VERIFY_SERVICE_SID"))
      ->verificationChecks->create([
        "to" => $toPhoneNumber,
        "code" => $code,
      ]);

    return $verification_check->valid;
  }
}
