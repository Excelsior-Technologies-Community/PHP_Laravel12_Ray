<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Laravel Ray</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #f5f5f5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 550px;
            margin: 0 auto;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 8px;
        }
        
        .header p {
            color: #666;
            font-size: 14px;
        }
        
        h2 {
            color: #333;
            font-size: 22px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
        }
        
        input, select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #1976d2;
        }
        
        .error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: #1976d2;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        button:hover {
            background: #1565c0;
        }
        
        .link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .link a {
            color: #1976d2;
            text-decoration: none;
            font-size: 14px;
        }
        
        .link a:hover {
            text-decoration: underline;
        }
        
        .status-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .status-toggle label {
            margin: 0;
            cursor: pointer;
        }
        
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .card {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1> Edit Student</h1>
                <p>Update student information</p>
            </div>
            
            <h2>Update Record</h2>
            
            <form method="POST" action="{{ route('student.update', $student->id) }}">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}">
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}">
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone', $student->phone) }}">
                    @error('phone')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Course *</label>
                    <select name="course">
                        <option value="PHP" {{ $student->course == 'PHP' ? 'selected' : '' }}>PHP Development</option>
                        <option value="JavaScript" {{ $student->course == 'JavaScript' ? 'selected' : '' }}>JavaScript</option>
                        <option value="Python" {{ $student->course == 'Python' ? 'selected' : '' }}>Python</option>
                        <option value="Laravel" {{ $student->course == 'Laravel' ? 'selected' : '' }}>Laravel</option>
                        <option value="React" {{ $student->course == 'React' ? 'selected' : '' }}>React.js</option>
                        <option value="Node.js" {{ $student->course == 'Node.js' ? 'selected' : '' }}>Node.js</option>
                    </select>
                    @error('course')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <div class="status-toggle">
                        <input type="checkbox" name="status" value="1" {{ $student->status ? 'checked' : '' }}>
                        <label>Active Status</label>
                    </div>
                </div>
                
                <button type="submit"> Update Student</button>
            </form>
            
            <div class="link">
                <a href="{{ route('student.list') }}">← Back to List</a>
            </div>
        </div>
    </div>
</body>
</html>