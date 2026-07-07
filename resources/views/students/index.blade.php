<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Directory</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .status-active { background: #d4edda; color: #155724; }
        .status-inactive { background: #f8d7da; color: #721c24; }
        .student-card { transition: all 0.3s; }
        .student-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .pagination { display: flex; justify-content: center; gap: 8px; margin-top: 20px; }
        .pagination a, .pagination span { padding: 8px 14px; background: white; border-radius: 6px; border: 1px solid #e5e7eb; text-decoration: none; color: #374151; }
        .pagination .active { background: #4f46e5; color: white; border-color: #4f46e5; }
        .pagination a:hover { background: #f3f4f6; }
        .checkbox-column { width: 40px; }
        .profile-thumb { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold">👨‍🎓 Student Directory</h1>
                    <p class="text-gray-500 text-sm">Manage all student records</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('student.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">+ Add Student</a>
                    <a href="{{ route('student.export') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">📥 Export</a>
                    <a href="{{ route('student.activity') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">📋 Activity</a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4 text-center"><div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div><div class="text-sm text-gray-600">Total</div></div>
                <div class="bg-green-50 rounded-lg p-4 text-center"><div class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</div><div class="text-sm text-gray-600">Active</div></div>
                <div class="bg-red-50 rounded-lg p-4 text-center"><div class="text-2xl font-bold text-red-600">{{ $stats['inactive'] }}</div><div class="text-sm text-gray-600">Inactive</div></div>
                <div class="bg-purple-50 rounded-lg p-4 text-center"><div class="text-2xl font-bold text-purple-600">{{ $students->total() }}</div><div class="text-sm text-gray-600">Showing</div></div>
            </div>

            <div class="flex flex-wrap gap-3 mb-4">
                <form method="GET" class="flex flex-wrap gap-3 flex-1">
                    <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}" class="px-4 py-2 border rounded-lg flex-1 min-w-[150px]">
                    <select name="course" class="px-4 py-2 border rounded-lg">
                        <option value="all">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course }}" {{ request('course') == $course ? 'selected' : '' }}>{{ $course }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="px-4 py-2 border rounded-lg">
                        <option value="all">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <select name="sort_by" class="px-4 py-2 border rounded-lg">
                        <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>Sort by ID</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Sort by Name</option>
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Sort by Date</option>
                    </select>
                    <select name="sort_direction" class="px-4 py-2 border rounded-lg">
                        <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">Filter</button>
                    <a href="{{ route('student.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">Reset</a>
                </form>
            </div>

            <div class="flex items-center gap-3 mb-4">
                <button onclick="bulkAction('active')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">✅ Activate Selected</button>
                <button onclick="bulkAction('inactive')" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm">⛔ Deactivate Selected</button>
                <button onclick="bulkAction('delete')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">🗑️ Delete Selected</button>
                <span id="selectedCount" class="text-sm text-gray-500">0 selected</span>
            </div>

            <div id="studentsList">
                @include('students.partials.list')
            </div>

            <div class="mt-4">
                {{ $students->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <script>
        function selectAll() {
            const checked = document.getElementById('selectAll').checked;
            document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = checked);
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const count = document.querySelectorAll('.student-checkbox:checked').length;
            document.getElementById('selectedCount').textContent = count + ' selected';
        }

        function bulkAction(action) {
            const ids = [];
            document.querySelectorAll('.student-checkbox:checked').forEach(cb => {
                ids.push(cb.value);
            });

            if (ids.length === 0) {
                alert('Please select at least one student');
                return;
            }

            if (action === 'delete' && !confirm('Delete ' + ids.length + ' students?')) {
                return;
            }

            $.ajax({
                url: '{{ route("student.bulk") }}',
                type: 'POST',
                data: {
                    ids: ids,
                    action: action,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON?.message || 'Something went wrong');
                }
            });
        }

        function deleteStudent(id) {
            if (!confirm('Delete this student?')) return;

            $.ajax({
                url: '/students/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON?.message || 'Something went wrong');
                }
            });
        }
    </script>
</body>
</html>