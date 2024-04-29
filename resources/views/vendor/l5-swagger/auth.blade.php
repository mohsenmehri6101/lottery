<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Api Documentation Auth</title>
    <style>
        html {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            background: linear-gradient(#141e30, #243b55);
        }

        .login-box {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 400px;
            padding: 40px;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, .5);
            box-sizing: border-box;
            box-shadow: 0 15px 25px rgba(0, 0, 0, .6);
            border-radius: 10px;
        }

        .login-box h2 {
            margin: 0 0 30px;
            padding: 0;
            color: #fff;
            text-align: center;
        }

        .login-box .user-box {
            position: relative;
        }

        .login-box .user-box input {
            width: 100%;
            padding: 10px 0;
            font-size: 16px;
            color: #fff;
            margin-bottom: 30px;
            border: none;
            border-bottom: 1px solid #fff;
            outline: none;
            background: transparent;
        }

        .login-box .user-box label {
            position: absolute;
            top: 0;
            left: 0;
            padding: 10px 0;
            font-size: 16px;
            color: #fff;
            pointer-events: none;
            transition: .5s;
        }

        .login-box .user-box input:focus ~ label,
        .login-box .user-box input:valid ~ label {
            top: -30px;
            left: 0;
            color: #03e9f4;
            font-size: 12px;
        }

        .login-box form button {
            position: relative;
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            text-decoration: none;
            text-transform: uppercase;
            outline: unset;
            border: unset;
            overflow: hidden;
            transition: .5s;
            margin-top: 40px;
            letter-spacing: 4px;
            background: #0bbbc3;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="login-box">
    <h2>Login</h2>
    @if(isset($error))
        <h2>{{$error}}</h2>
    @endif
    <form action="{{route("api_documentation_auth_login")}}" method="post">
        @csrf
        <div class="user-box">
            <input type="text" name="username" required>
            <label>Username</label>
        </div>
        <div class="user-box">
            <input type="password" name="password" required>
            <label>Password</label>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
</body>
</html>
