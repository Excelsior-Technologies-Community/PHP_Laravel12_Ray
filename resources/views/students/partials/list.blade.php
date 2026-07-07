@if($students->isEmpty())
    <div class="text-center py-12">
        <div class="text-6xl mb-4">📭</div>
        <h3 class="text-xl font-semibold text-gray-600">No students found</h3>
        <p class="text-gray-400">Add your first student to get started</p>
    </div>
@else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-3 w-10"><input type="checkbox" id="selectAll" onchange="selectAll()"></th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">ID</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Profile</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Name</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Email</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Phone</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Course</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 student-card">
                        <td class="px-4 py-3"><input type="checkbox" class="student-checkbox" value="{{ $student->id }}" onchange="updateSelectedCount()"></td>
                        <td class="px-4 py-3 text-sm">{{ $student->id }}</td>
                        <td class="px-4 py-3">
                            <img src="{{ $student->profile_image_url }}" alt="Profile" class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                        </td>
                        <td class="px-4 py-3 text-sm font-medium">{{ $student->name }}</td>
                        <td class="px-4 py-3 text-sm">{{ $student->email }}</td>
                        <td class="px-4 py-3 text-sm">{{ $student->phone ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full text-xs font-medium">
                                {{ $student->course }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $student->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $student->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('student.edit', $student->id) }}" 
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-xs font-medium transition">Edit</a>
                                <button onclick="deleteStudent({{ $student->id }})" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs font-medium transition">Delete</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif