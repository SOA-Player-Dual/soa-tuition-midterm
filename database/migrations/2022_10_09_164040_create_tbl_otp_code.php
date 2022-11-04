<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_otp_code', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('student_id', 8)->unique();
            $table->foreign('student_id')->references('student_id')->on('tbl_tuition');
            $table->string('user_id');
            $table->string('otp_code', 6)->unique();
            $table->timestamp('expired_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_otp_code');
    }
};
