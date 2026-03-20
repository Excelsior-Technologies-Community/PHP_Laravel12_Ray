# PHP_Laravel12_Ray


## Project Description

PHP_Laravel12_Ray is a Laravel 12 application that demonstrates how to integrate and use the Spatie Laravel Ray package for advanced debugging.

This project provides a practical implementation of debugging techniques by sending data such as strings, arrays, objects, database queries, and performance metrics to the Ray desktop application in real time.

It also includes a simple student management system with a dark mode UI, form validation, and database operations to showcase real-world usage of Laravel Ray.


##  Features

- Debug data using `ray()` helper
- Display arrays, objects, and collections in Ray
- Capture and monitor SQL queries
- Measure application performance
- Form handling with validation
- Store and retrieve data from database
- Clean dark mode UI using Blade templates
- Multi-page structure (Form Page + List Page)


## Technologies Used

- PHP 8.x
- Laravel 12
- MySQL
- Blade Template Engine
- Spatie Laravel Ray
- HTML & CSS (Dark Mode UI)
- Composer
- Laravel Artisan CLI


---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Ray "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Ray

```

#### Explanation:

Creates a fresh Laravel 12 application using Composer and sets up the base project structure. 

The cd command moves into the project directory.




## STEP 2: Database Setup 

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_Ray
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_Ray

```

### Then Run:

```
php artisan migrate

```


#### Explanation:

Configures Laravel to connect with MySQL database and runs migrations to create default tables required by Laravel.




## STEP 3: Install Laravel Ray Package 

### Run:

```
composer require spatie/laravel-ray --dev

```

#### Explanation:

Installs the Spatie Laravel Ray package, which helps in debugging application data in a separate desktop app.




## STEP 4: Publish Config

```
php artisan ray:publish-config

```

#### Explanation:

Publishes the Ray configuration file (config/ray.php) so you can customize Ray settings.




## STEP 5: ENV Setup

### .env

```
RAY_ENABLED=true
RAY_HOST=127.0.0.1
RAY_PORT=23517

```

#### Explanation:

Enables Laravel Ray and defines the host and port for communication with the Ray desktop application.




## STEP 6: Create Model + Migration

### Run:

```
php artisan make:model Student -m

```


### Open: database/migrations/xxxx_create_students_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

```


### Then Run:

```
php artisan migrate

```


### Open: app/Models/Student.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'email'
    ];
}

```

#### Explanation:

Creates a Student model and migration file to define and manage the students table structure in the database.




## STEP 7: Create Controller

### Run:

```
php artisan make:controller RayController

```

### Open: app/Http/Controllers/RayController.php

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class RayController extends Controller
{
    // FORM PAGE
    public function create()
    {
        ray()->clearAll(); // Clear old logs

        ray('📄 Open Add Student Page')->green();

        return view('welcome');
    }

    // STORE DATA
    public function store(Request $request)
    {
        // 🔵 Show form input
        ray($request->all())->blue()->label('Form Data');

        // 🔴 Validation
        $validated = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email'
        ]);

        // 🟢 Show validated data
        ray($validated)->green()->label('Validated Data');

        // 🟠 Insert into DB
        $student = Student::create($validated);

        ray($student)->orange()->label('Inserted Student');

        // 🟣 Show queries
        ray()->showQueries();

        // ⚡ Performance
        ray()->measure();

        return redirect('/')->with('success', '✅ Student Added Successfully!');
    }

    // LIST PAGE
    public function list()
    {
        $students = Student::all();

        ray($students)->purple()->label('Student List Data');

        return view('list', compact('students'));
    }
}

```

#### Explanation:

Creates RayController to handle form display, data storage, and listing while integrating Laravel Ray debugging.




## STEP 8: Route Setup

### Edit: routes/web.php

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RayController;

Route::get('/', [RayController::class, 'create']);
Route::post('/store', [RayController::class, 'store'])->name('student.store');
Route::get('/students', [RayController::class, 'list'])->name('student.list');

```

#### Explanation:

Defines application routes to connect URLs with controller methods for form display, data submission, and listing.





## STEP 9: Blade View

### resources/views/welcome.blade.php

```
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

```


### resources/views/list.blade.php

```
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

```

#### Explanation:

Creates frontend UI using Blade templates for adding students and displaying the student list with a dark mode design.





## STEP 10: Run the App  

### Start dev server:

```
php artisan serve

```

### Open in browser:

```
http://127.0.0.1:8000

```

#### Explanation:

Starts the Laravel development server and allows you to access the application in the browser.




## Expected Output:

### Home Page:


<img src="screenshots/Screenshot 2026-03-20 151845.png" width="900">


### Laravel Ray Form:


<img src="screenshots/Screenshot 2026-03-20 151258.png" width="900">


### Success Notification:


<img src="screenshots/Screenshot 2026-03-20 151327.png" width="900">


### Stored Details List:


<img src="screenshots/Screenshot 2026-03-20 151625.png" width="900">





---

## Project Folder Structure:

```
PHP_Laravel12_Ray/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── RayController.php
│   │
│   ├── Models/
│   │   └── Student.php
│   │
│   └── Providers/
│
├── bootstrap/
│
├── config/
│   ├── app.php
│   ├── database.php
│   └── ray.php
│
├── database/
│   ├── factories/
│   ├── migrations/
│   │   └── xxxx_create_students_table.php
│   └── seeders/
│
├── public/
│   └── index.php
│
├── resources/
│   ├── views/
│   │   ├── welcome.blade.php   (Form Page)
│   │   └── list.blade.php      (Student List Page)
│   │
│   ├── css/
│   ├── js/
│   └── images/
│
├── routes/
│   └── web.php
│
├── storage/
│
├── tests/
│
├── vendor/
│
├── .env
├── artisan 
├── composer.json
├── composer.lock
└── README.md

```
