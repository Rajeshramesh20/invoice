<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employees;
use Exception;
// use Vonage\Client;
// use Vonage\Client\Credentials\Basic;
// use Vonage\SMS\Message\SMS;
// use Twilio\Rest\Client;
use Twilio\Rest\Client as TwilioClient;

class SMSController extends Controller
{

   //Twilio SMS Send
   public function twilioSend($id){
    try {
        //get employee PhoneNumber
        $phone = Employees::where('id', $id)->value('contact_number');

        if (!$phone) {
            return response()->json([
                'status' => false, 
                'error' => 'No phone found for this employee ID'
            ], 422);
        }

        //Create client
        $client = new TwilioClient(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
        $from = config('services.twilio.sms_from');
        $text = "Hello! This is a test SMS from Laravel";

        $client->messages->create($phone,[
            'from' => $from,
            'body' => $text
        ]);

        return response()->json([
            'success' => true, 'message' => "SMS sent to {$phone}"
        ]);
    }catch(Exception $e){
        return response()->json([
                'status'     => false,
                'employeeId' => $id,
                'phone'      => $phone,
                'error'      => $e->getMessage(),
            ], 500);
        }
    }

    //vonage SMS send
     /* public function send($id){
    try {
        //get employee PhoneNumber
        $phone = Employees::where('id', $id)->value('contact_number');

        if (!$phone) {
            return response()->json([
                'status' => false, 
                'error' => 'No phone found for this employee ID'
            ], 422);
        }

        //Create client
        $client = new Client(new Basic(
            config('services.vonage.key'),
            config('services.vonage.secret')
        ));
        $from = config('services.vonage.sms_from'); // VonageTest or your purchased number
        $text = "Hello! This is a test SMS from Laravel";

       
            $resp   = $client->sms()->send(new SMS($phone, $from, $text));
            $msg    = $resp->current();
            $status = $msg->getStatus(); // "0" = success

            if ($status === 0) {
                return response()->json([
                    'success'     => true,
                    'employeeId'  => $id,
                    'phone'       => $phone,
                    'message_id'  => $msg->getMessageId(),
                    'vonage_status' => $status, // 0 means success
                ]);
            }

            return response()->json([
                'success'       => false,
                'employeeId'    => $id,
                'phone'         => $phone,
                'vonage_status' => $status,   // non-zero means failed
            ], 400);

        } catch (Exception $e) {
            return response()->json([
                'status'     => false,
                'employeeId' => $id,
                'phone'      => $phone,
                'error'      => $e->getMessage(),
            ], 500);
        }
    }*/

       /* public function sendWhatsapp($id)
    {
        try {
            $employee = Employees::find($id);
        if (!$employee) {
            return response("Employee not found!", 404);
        }

        $raw = $employee->contact_number;

        // Require E.164 and then prefix with 'whatsapp:'
        if (!preg_match('/^\+\d{9,15}$/', $raw)) {
            return response()->json([
                'ok' => false,
                'phone' => $raw,
                'error' => 'Phone not in E.164 format (+CountryCodeNumber)'
            ], 422);
        }

        $to   = 'whatsapp:' . $raw;
        $from = config('services.twilio.whatsapp_from'); // e.g. whatsapp:+14155238886 (sandbox) or your WA business number
        $body = "Hello! This is a Payslip Message";

       
            $twilio = new TwilioClient(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );

            $twilio->messages->create($to, [
                'from' => $from,
                'body' => $body,
            ]);

            return "WhatsApp message sent to {$raw}";
        } catch (\Throwable $e) {
            return response()->json([
                'ok'    => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    

    //     $from = config('services.twilio.whatsapp_from');

    // if (empty($from)) {
    //     return response()->json([
    //         'ok' => false,
    //         'error' => 'TWILIO_WHATSAPP_FROM is empty. Please set it in .env'
    //     ]);
    // }
}
}*/

}
