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
        $student_id = '';
        $user_id = '';
        if ($request->has('otp_id')) {
            $otp = OTPCode::where('id', $request->otp_id)->first();
            if (!$otp) {
                return response()->json([
                    'message' => 'OTP not found',
                ], 404);
            }
            $student_id = $otp->student_id;
            $user_id = $otp->user_id;
        } else {
            $student_id = $request->student_id;
            $user_id = $request->user_id;
        }
        $mail = Http::get('http://localhost:8000/api/get-email', [
            'user_id' => $user_id,
        ]);
        if (!$mail['email']) {
            return response()->json([
                'message' => 'Failed to get email',
            ], 422);
        }
        $tuition = Tuition::where('student_id', $student_id)->first();
        if (!$tuition) {
            return response()->json([
                'message' => 'Failed to get tuition',
            ], 422);
        }

        if ($request->otp_id) {
            $updateOTP = $otp->update([
                'otp_code' => $otp_code,
                'expired_at' => Carbon::now()->addMinutes(5),
            ]);
            if ($updateOTP) {
                $sent = Mail::to($mail['email'])->send(new OTPMail([
                    'student_id' => $tuition->student_id,
                    'otp' => $otp_code,
                    'tuition_fee' => $tuition->tuition_fee,
                    'fullname' => $tuition->full_name,
                ]));
                if ($sent) {
                    return response()->json([
                        'otp_id' => $otp->id,
                        'message' => 'OTP code has been sent to your email',
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Failed to send OTP code to your email',
                    ], 500);
                }
            } else {
                return response()->json([
                    'message' => 'Failed to update OTP code to your email',
                ], 500);
            }
        } else {
            if ($tuition['status'] == 1) {
                return response()->json([
                    'message' => 'Student\'s tuition has been paid',
                ], 422);
            }
            if (OTPCode::where('student_id', $request->student_id)->first()) {
                return response()->json([
                    'message' => 'This student number is in the process of paying tuition by another account'
                ], 422);
            }
            $result = OTPCode::create([
                'otp_code' => $otp_code,
                'student_id' => $request->student_id,
                'user_id' => $request->user_id,
                'expired_at' => Carbon::now()->addMinutes(5),
                'created_at' => Carbon::now(),
            ]);
            if ($result) {
                $sent = Mail::to($mail['email'])->send(new OTPMail([
                    'student_id' => $tuition->student_id,
                    'otp' => $otp_code,
                    'tuition_fee' => $tuition->tuition_fee,
                    'fullname' => $tuition->full_name,
                ]));
                if ($sent) {
                    return response()->json([
                        'otp_id' => $result->id,
                        'message' => 'OTP code has been sent to your email',
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Failed to send OTP code to your email',
                    ], 422);
                }
            } else {
                return response()->json([
                    'message' => 'Failed to send OTP code to your email',
                ], 500);
            }
        }
    }

    public function verify(Request $request){
        $otp = OTPCode::where('otp_code', $request->otp_code)->first();
        if($otp){
            $tuition = Tuition::where('student_id', $otp->student_id)->first();
            if($otp->expired_at > Carbon::now()){
                $otp->delete();
                $tuition  = Tuition::where('student_id', $otp->student_id)->first();
                $tuition->update([
                    'status' => 1,
                    'updated_at' => date("Y/m/d H:i:s"),
                ]);

                $response = Http::post('http://127.0.0.1:8000/api/payment', [
                    'amount' => $tuition->tuition_fee - $tuition->reduction,
                    'id' => $request->id,
                ]);
                if ($response->status() == 200) {
                    return response()->json([
                        'message' => 'Tuition has been paid',
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Failed to pay tuition',
                    ], 500);
                }
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
