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
                    'student_id' => $tuition->student_id,
                    'full_name' => $tuition->full_name,
                    'tuition_fee' => $tuition->tuition_fee,
                    'tuition_status' => $tuition->status,
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
