<!DOCTYPE html>
<html>

<head>
    <title>Laravel Ray - Add Student</title>
    <style>
        body {
            background: #0f172a;
            color: white;
            font-family: Arial;
            padding: 40px;
        }

        .card {
            max-width: 600px;
            margin: auto;
            background: #1e293b;
            padding: 25px;
            border-radius: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #38bdf8;
        }

        .header p {
            font-size: 14px;
            color: #94a3b8;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
            background: #334155;
            color: white;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #38bdf8;
            border: none;
            color: black;
            cursor: pointer;
            border-radius: 5px;
        }

        .success {
            background: green;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            text-align: center;
        }

        .error {
            color: red;
        }

        a {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: #38bdf8;
        }
    </style>
</head>

<body>

    <div class="card">

        <!-- 🔥 TITLE -->
        <div class="header">
            <h1>🚀 Laravel Ray Debugging</h1>
            <p>Form Page | Check Ray Desktop App for Debug Output</p>
        </div>

        <h2>Add Student</h2>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('student.store') }}">
            @csrf

            <input type="text" name="name" placeholder="Enter Name">
            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror

            <input type="text" name="email" placeholder="Enter Email">
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror

            <button type="submit">💾 Save Student</button>
        </form>

        <a href="{{ route('student.list') }}">📋 View Students</a>
    </div>

</body>

</html>