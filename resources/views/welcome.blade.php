<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Laravel Ray</title>
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
        
        .badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
            margin-top: 10px;
        }
        
        h2 {
            color: #333;
            font-size: 22px;
            margin-bottom: 20px;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
            transition: border-color 0.3s;
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
                <h1> Student Management</h1>
                <span class="badge"> Ray Debugger Active</span>
            </div>
            <!-- add student -->
            <h2> Add New Student</h2>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('student.store') }}">
                @csrf
                
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" placeholder="Enter full name" value="{{ old('name') }}">
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" placeholder="Enter email address" value="{{ old('email') }}">
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" placeholder="Enter phone number" value="{{ old('phone') }}">
                    @error('phone')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Course *</label>
                    <select name="course">
                        <option value="">Select Course</option>
                        <option value="PHP" {{ old('course') == 'PHP' ? 'selected' : '' }}>PHP Development</option>
                        <option value="JavaScript" {{ old('course') == 'JavaScript' ? 'selected' : '' }}>JavaScript</option>
                        <option value="Python" {{ old('course') == 'Python' ? 'selected' : '' }}>Python</option>
                        <option value="Laravel" {{ old('course') == 'Laravel' ? 'selected' : '' }}>Laravel</option>
                        <option value="React" {{ old('course') == 'React' ? 'selected' : '' }}>React.js</option>
                        <option value="Node.js" {{ old('course') == 'Node.js' ? 'selected' : '' }}>Node.js</option>
                    </select>
                    @error('course')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <div class="status-toggle">
                        <input type="checkbox" name="status" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                        <label>Active Status</label>
                    </div>
                </div>
                
                <button type="submit"> Save Student</button>
            </form>
            
            <div class="link">
                <a href="{{ route('student.list') }}"> View All Students</a>
            </div>
        </div>
    </div>
</body>
</html>