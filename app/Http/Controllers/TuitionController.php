<?php

namespace App\Http\Controllers;

use App\Http\Requests\TuitionPaymentRequest;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use App\Models\Tuition;
use App\Models\OTPCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class TuitionController extends Controller
{

    public function sendOTPMail(TuitionPaymentRequest $request){
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

    public function resendOTPMail(Request $request){
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

    public function verifyOTP(Request $request){
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('student_id')) {
            $tuition = Tuition::where('student_id', $request->student_id)->first();
            if ($tuition) {
                return response()->json([
                    'full_name' => $tuition->full_name,
                    'tuition_fee' => $tuition->tuition_fee,
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Student ID not found',
                ], 404);
            }
        } else {
            return response()->json([
                'error' => 'id is required',
            ], 422);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
