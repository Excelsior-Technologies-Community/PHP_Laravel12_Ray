<!DOCTYPE html>
<html>

<head>
    <title>Activity Logs</title>

    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
            padding: 40px;
        }

        .card {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
        }

        .item {
            padding: 15px;
            background: #eee;
            margin-bottom: 10px;
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <div class="card">

        <h2>Student Activity Logs</h2>

        @foreach($activities as $activity)

        <div class="item">

            {{ $activity->student_name }}

            -

            {{ $activity->action }}

            <br>

            <small>

                {{ $activity->created_at->diffForHumans() }}

            </small>

        </div>

        @endforeach

        <br>

        <a href="{{route('student.list')}}">
            Back
        </a>

    </div>

</body>

</html>