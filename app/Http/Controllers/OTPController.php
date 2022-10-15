<?php

namespace App\Http\Controllers;

use App\Http\Requests\TuitionPaymentRequest;
use App\Mail\OTPMail;
use App\Models\OTPCode;
use App\Models\Tuition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class OTPController extends Controller
{
    public function send(TuitionPaymentRequest $request){
        $otp_code = rand(100000, 999999);
        OTPCode::create([
            'email' => $request->mail,
            'otp_code' => $otp_code,
            'student_id' => $request->student_id,
            'tuition_fee' => $request->tuition_fee,
            'expired_at' => Carbon::now()->addMinutes(5),
            'created_at' => Carbon::now(),
        ]);

        Mail::to($request->mail)->send(new OTPMail([
            'student_id' => $request->student_id,
            'otp' => $otp_code,
            'tuition_fee' => $request->tuition_fee,
            'fullname' => $request->fullname,
        ]));
    }

    public function resend(Request $request){
        $otp_code = rand(100000, 999999);
        $otp = OTPCode::where('id', $request->id)->first();
        $otp->update([
            'otp_code' => $otp_code,
            'expired_at' => Carbon::now()->addMinutes(5),
            'updated_at' => Carbon::now(),
        ]);

        Mail::to($otp->email)->send(new OTPMail([
            'student_id' => $otp->student_id,
            'otp' => $otp_code,
            'tuition_fee' => $otp->tuition_fee,
            'fullname' => $otp->fullname,
        ]));
    }

    public function verify(Request $request){
        $otp = OTPCode::where('otp_code', $request->otp_code)->first();
        if($otp){
            if($otp->expired_at > Carbon::now()){
                $otp->delete();
                $tuition  = Tuition::where('student_id', $otp->student_id)->first();
                $tuition->update([
                    'status' => 1,
                    'updated_at' => date("Y/m/d H:i:s"),
                ]);

                $response = Http::patch('http://localhost:8012/authenticate-service/public/api/payment', [
                    'amount' => $otp->tuition_fee,
                    'id' => $request->id,
                ]);

                return response()->json([
                    'message' => 'success',
                ], 200);
            }else{
                $otp->delete();
                return response()->json([
                    'message' => 'expired',
                ], 200);
            }
        }else{
            return response()->json([
                'message' => 'failed',
            ], 200);
        }
    }
}
