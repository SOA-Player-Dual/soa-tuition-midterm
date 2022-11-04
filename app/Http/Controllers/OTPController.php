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
        $student_id = $request->student_id;
        $user_id = $request->user_id;
        $tuition = Tuition::where('student_id', $student_id)->first();
        if (!$tuition){
            return response()->json([
                'error' => 'Tuiton of student ID not found',
            ], 404);
        }
        if ($tuition->status == 1){
            return response()->json([
                'error' => 'Tuition fee already paid',
            ], 422);
        }
        $user = Http::get('https://soa-midterm.000webhostapp.com/api/get-user', [
            'user_id' => $user_id,
        ]);
        if (!$user){
            return response()->json([
                'error' => 'Cannot find email of user',
            ], 404);
        }
        $otp = OTPCode::where('student_id', $student_id)->first();
        if ($otp){
            if ($otp->user_id != $user_id && $otp->expired_at > Carbon::now()) {
                return response()->json([
                    'error' => 'This student id is in the process of paying tuition by another account'
                ], 403);
            } else {
                $otp->otp_code = $otp_code;
                $otp->user_id = $user_id;
                $otp->expired_at = Carbon::now()->addMinutes(5);
                $updateOTP = $otp->save();
                if ($updateOTP){
                    $sent = Mail::to($user['email'])->send(new OTPMail([
                        'student_id' => $tuition->student_id,
                        'otp' => $otp_code,
                        'tuition_fee' => $tuition->tuition_fee,
                        'user_name' => $user['fullname'],
                        'student_name' => $tuition->full_name,
                    ]));
                    if ($sent){
                        return response()->json([
                            'msg' => 'OTP code has been sent to your email',
                        ], 200);
                    } else {
                        return response()->json([
                            'error' => 'Cannot send OTP code to your email',
                        ], 500);
                    }
                } else {
                    return response()->json([
                        'error' => 'Cannot update OTP code',
                    ], 500);
                }
            }
        } else {
            $otp = new OTPCode();
            $otp->student_id = $student_id;
            $otp->user_id = $user_id;
            $otp->otp_code = $otp_code;
            $otp->expired_at = Carbon::now()->addMinutes(5);
            $saveOTP = $otp->save();
            if ($saveOTP){
                $sent = Mail::to($user['email'])->send(new OTPMail([
                    'student_id' => $tuition->student_id,
                    'otp' => $otp_code,
                    'tuition_fee' => $tuition->tuition_fee,
                    'user_name' => $user['fullname'],
                    'student_name' => $tuition->full_name,
                ]));
                if ($sent){
                    return response()->json([
                        'msg' => 'OTP code has been sent to your email',
                    ], 200);
                } else {
                    return response()->json([
                        'error' => 'Cannot send OTP code to your email',
                    ], 500);
                }
            } else {
                return response()->json([
                    'error' => 'Cannot generate OTP code',
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

                $response = Http::post('https://soa-midterm.000webhostapp.com/api/payment', [
                    'amount' => $tuition->tuition_fee - $tuition->reduction,
                    'id' => $request->user_id,
                    'student_id' => $tuition->student_id,
                    'student_name' => $tuition->full_name,
                ]);
                if ($response->status() == 200) {
                    return response()->json([
                        'msg' => 'Tuition has been paid',
                    ], 200);
                } else {
                    return response()->json([
                        'error' => 'Failed to pay tuition',
                    ], 500);
                }
            }else{
                $otp->delete();
                return response()->json([
                    'error' => 'expired',
                ], 410);
            }
        }else{
            return response()->json([
                'error' => 'failed',
            ], 400);
        }
    }
}
