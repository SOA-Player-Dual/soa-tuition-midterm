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
            $table->string('student_id', 8);
            $table->string('otp_code', 6)->unique();
            $table->integer('tuition_fee');
            $table->integer('reduction')->default(0);
            $table->string('email');
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
