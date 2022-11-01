<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title></title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: rgb(241, 242, 245);
            font-size: 1rem;
        }
    </style>
</head>
<body>
<div
    style="
                width: 100%;
                height: 8px;
                background-color: #fe2c55;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
            "
></div>
<div
    style="
                font-family: Helvetica, Arial, sans-serif;
                width: 100%;
                height: 100vh;
                line-height: 2;
                position: relative;
            "
>
    <div
        style="
                    position: fixed;
                    margin: auto;
                    top: 0;
                    right: 0;
                    bottom: 0;
                    left: 0;
                    width: 80%;
                    height: fit-content;
                    padding: 20px 0;
                    border: 1px solid rgba(22, 24, 35, 0.09);
                    border-radius: 8px;
                    padding: 12px;
                    background-color: rgb(255, 255, 255);
                "
    >
        <div style="border-bottom: 1px solid #eee">
            <img
                src="{{asset('/logo.png')}}"
                style="width: 110px; height: 110px"
                alt=""
            />
        </div>
        <p style="font-size: 1.1em">Hi, {{$mailData['user_name']}}</p>
        <p>Your information tuition is:</p>
        <div style="padding: 0 0 0 32px">
            <p style="margin: 0; opacity: 0.6">
                - Full name: {{$mailData['student_name']}}
            </p>
            <p style="margin: 0; opacity: 0.6">
                - Student ID: {{$mailData['student_id']}}
            </p>
            <p style="margin: 0">
                <span style="opacity: 0.6">- Tuition:</span>
                <span style="opacity: 1; color: #fe2c55"
                >{{$mailData['tuition_fee']}} VND</span
                >
            </p>
        </div>
        <p>
            Use the following OTP to complete your Tuition fee
            procedures. OTP is valid for 5 minutes
        </p>
        <h2
            style="
                        background: #fe2c55;
                        margin: 0 auto;
                        width: max-content;
                        padding: 0 10px;
                        color: #fff;
                        border-radius: 4px;
                    "
        >
            {{$mailData['otp']}}
        </h2>
        <p style="font-size: 0.9em">
            Regards,<br />Ton Duc Thang University
        </p>
        <hr style="border: none; border-top: 1px solid #eee" />
        <div
            style="
                        float: right;
                        padding: 8px 0;
                        color: #aaa;
                        font-size: 0.8em;
                        line-height: 1;
                        font-weight: 300;
                        text-align: center;
                    "
        >
            <p style="margin: 0; padding-bottom: 8px">
                Copyright © 2022 Dev Team
            </p>
            <p style="margin: 0; padding-bottom: 8px">
                19, Nguyen Huu Tho Street, Tan Phong Ward, 7 District
            </p>
            <p style="margin: 0; padding-bottom: 8px">Ho Chi Minh</p>
        </div>
    </div>
</div>
</body>
</html>
