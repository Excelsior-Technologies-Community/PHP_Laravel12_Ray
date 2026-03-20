<!DOCTYPE html>
<html>

<head>
    <title>Laravel Ray - Student List</title>
    <style>
        body {
            background: #0f172a;
            color: white;
            font-family: Arial;
            padding: 40px;
        }

        .card {
            max-width: 700px;
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

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            background: #334155;
            margin: 8px 0;
            padding: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
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
            <h1>📊 Laravel Ray Debugging</h1>
            <p>Student List Page | Data Debugged using Ray</p>
        </div>

        <h2>📋 Student List</h2>

        <ul>
            @foreach($students as $student)
                <li>
                    <span>{{ $student->name }}</span>
                    <span>{{ $student->email }}</span>
                </li>
            @endforeach
        </ul>

        <a href="/">⬅ Back to Form</a>
    </div>

</body>

</html>