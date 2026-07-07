<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .activity-item { transition: all 0.3s; }
        .activity-item:hover { transform: translateX(5px); }
        .action-created { border-left: 4px solid #22c55e; }
        .action-updated { border-left: 4px solid #3b82f6; }
        .action-deleted { border-left: 4px solid #ef4444; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">📋 Activity Logs</h1>
                <a href="{{ route('student.index') }}" class="text-indigo-600 hover:text-indigo-800">← Back</a>
            </div>

            <div class="mb-4 text-sm text-gray-500">
                Total Activities: {{ $activities->total() }}
            </div>

            <div class="space-y-3">
                @foreach($activities as $activity)
                    <div class="activity-item action-{{ strtolower($activity->action) }} bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="flex items-center gap-2">
                                    @if($activity->action == 'Created')
                                        <span class="text-green-600 text-xl">✅</span>
                                    @elseif($activity->action == 'Updated')
                                        <span class="text-blue-600 text-xl">🔄</span>
                                    @elseif($activity->action == 'Deleted')
                                        <span class="text-red-600 text-xl">🗑️</span>
                                    @endif
                                    <span class="font-semibold">{{ $activity->student_name }}</span>
                                    <span class="text-sm text-gray-500">-</span>
                                    <span class="text-sm font-medium {{ $activity->action == 'Created' ? 'text-green-600' : ($activity->action == 'Updated' ? 'text-blue-600' : 'text-red-600') }}">
                                        {{ $activity->action }}
                                    </span>
                                </div>
                                @if($activity->details)
                                    <div class="text-xs text-gray-500 mt-1">
                                        @foreach($activity->details as $key => $value)
                                            @if(is_array($value))
                                                @foreach($value as $k => $v)
                                                    <span class="inline-block bg-gray-200 px-2 py-0.5 rounded mr-1">{{ $k }}: {{ $v }}</span>
                                                @endforeach
                                            @else
                                                <span class="inline-block bg-gray-200 px-2 py-0.5 rounded mr-1">{{ $key }}: {{ $value }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $activity->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $activities->links() }}
            </div>

            <div class="mt-4 p-4 bg-indigo-50 rounded-lg">
                <h4 class="font-semibold text-indigo-800">🔍 Ray Debug Active</h4>
                <p class="text-sm text-indigo-600">All activities are logged with color-coded alerts</p>
            </div>
        </div>
    </div>
</body>
</html>