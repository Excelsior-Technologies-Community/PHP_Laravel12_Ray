<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
    <style>
        .dropzone { border: 3px dashed #4f46e5; border-radius: 12px; padding: 30px; text-align: center; cursor: pointer; transition: all 0.3s; }
        .dropzone:hover { background: #f3f4f6; }
        .current-image { max-width: 100px; max-height: 100px; border-radius: 12px; margin: 10px 0; }
        .error-text { color: #dc2626; font-size: 13px; margin-top: 5px; display: none; }
        .error-text.show { display: block; }
        .input-error { border-color: #dc2626 !important; background: #fef2f2 !important; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">✏️ Edit Student</h1>
                <a href="{{ route('student.index') }}" class="text-indigo-600 hover:text-indigo-800">← Back</a>
            </div>

            <div id="message" class="hidden p-4 rounded-xl mb-4"></div>

            <form id="studentForm">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="name" id="name" value="{{ $student->name }}" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    <div class="error-text" id="nameError">Please enter valid name</div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" id="email" value="{{ $student->email }}" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    <div class="error-text" id="emailError">Please enter valid email</div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="tel" name="phone" id="phone" value="{{ $student->phone }}" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Course *</label>
                    <select name="course" id="course" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                        <option value="PHP" {{ $student->course == 'PHP' ? 'selected' : '' }}>PHP Development</option>
                        <option value="Laravel" {{ $student->course == 'Laravel' ? 'selected' : '' }}>Laravel</option>
                        <option value="React" {{ $student->course == 'React' ? 'selected' : '' }}>React.js</option>
                        <option value="JavaScript" {{ $student->course == 'JavaScript' ? 'selected' : '' }}>JavaScript</option>
                        <option value="Python" {{ $student->course == 'Python' ? 'selected' : '' }}>Python</option>
                        <option value="Node.js" {{ $student->course == 'Node.js' ? 'selected' : '' }}>Node.js</option>
                    </select>
                    <div class="error-text" id="courseError">Please select a course</div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="status" id="status" value="1" {{ $student->status ? 'checked' : '' }} class="w-5 h-5">
                        <label for="status" class="text-sm">Active</label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                    <img src="{{ $student->profile_image_url }}" alt="Profile" class="current-image">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Change Image</label>
                    <div class="dropzone" id="imageDropzone">
                        <div class="dz-message">
                            <div class="text-4xl mb-2">📸</div>
                            <p class="text-gray-600">Drag & drop new image here or click to upload</p>
                            <p class="text-xs text-gray-400">Max 2MB (JPEG, PNG, GIF)</p>
                        </div>
                    </div>
                    <input type="hidden" name="profile_image" id="profileImage" value="{{ $student->profile_image }}">
                </div>

                <button type="submit" id="submitBtn" 
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg font-semibold transition">
                    💾 Update Student
                </button>
            </form>
        </div>
    </div>

    <script>
        Dropzone.autoDiscover = false;

        const dropzone = new Dropzone('#imageDropzone', {
            url: '{{ route("student.upload") }}',
            paramName: 'file',
            maxFilesize: 2,
            acceptedFiles: 'image/*',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(file, response) {
                if (response.success) {
                    document.getElementById('profileImage').value = response.path;
                    alert('✅ Image uploaded successfully!');
                }
            },
            error: function(file, message) {
                let errorMsg = typeof message === 'object' ? (message.message || 'Upload failed') : message;
                alert('❌ Upload failed: ' + errorMsg);
                this.removeFile(file);
            }
        });

        $('#studentForm').on('submit', function(e) {
            e.preventDefault();

            // Clear previous errors
            $('.error-text').removeClass('show');
            $('input, select').removeClass('input-error');

            // Validate
            let hasError = false;
            const name = $('#name').val().trim();
            const email = $('#email').val().trim();
            const course = $('#course').val();

            if (!name || name.length < 3) {
                $('#name').addClass('input-error');
                $('#nameError').text('Name must be at least 3 characters').addClass('show');
                hasError = true;
            }

            if (!email || !email.includes('@')) {
                $('#email').addClass('input-error');
                $('#emailError').text('Please enter valid email address').addClass('show');
                hasError = true;
            }

            if (!course) {
                $('#course').addClass('input-error');
                $('#courseError').text('Please select a course').addClass('show');
                hasError = true;
            }

            if (hasError) {
                return;
            }

            const formData = new FormData(this);
            const id = {{ $student->id }};

            $('#submitBtn').html('⏳ Updating...').prop('disabled', true);

            $.ajax({
                url: '/students/' + id,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PUT'
                },
                success: function(response) {
                    if (response.success) {
                        $('#message').removeClass('hidden')
                            .addClass('bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg')
                            .html('✅ ' + response.message);
                        setTimeout(function() {
                            window.location.href = '{{ route("student.index") }}';
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Something went wrong!';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        let msg = '';
                        Object.keys(errors).forEach(key => {
                            msg += '• ' + errors[key][0] + '\n';
                            $('#' + key).addClass('input-error');
                            $('#' + key + 'Error').text(errors[key][0]).addClass('show');
                        });
                        errorMsg = msg;
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    
                    $('#message').removeClass('hidden')
                        .addClass('bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg')
                        .html('❌ ' + errorMsg.replace(/\n/g, '<br>'));
                    
                    $('#submitBtn').html('💾 Update Student').prop('disabled', false);
                }
            });
        });
    </script>
</body>
</html>