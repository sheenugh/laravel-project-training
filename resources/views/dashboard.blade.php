@php
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    $user = auth()->user();
    $isAdmin = $user->hasRole('superadmin');
    $roles = $user?->getRoleNames()->implode(', ') ?: 'User';

    $taskQuery = \App\Models\Todo::query();

    if (! $isAdmin) {
        $taskQuery->where('user_id', $user->id);
    }

    $totalTasks = (clone $taskQuery)->count();
    $notStartedTasks = (clone $taskQuery)->where('status', 'not_started')->count();
    $pendingTasks = (clone $taskQuery)->where('status', 'pending')->count();
    $completedTasks = (clone $taskQuery)->where('status', 'completed')->count();
    $activeTasks = $notStartedTasks + $pendingTasks;

    $trashQuery = \App\Models\Todo::onlyTrashed();

    if (! $isAdmin) {
        $trashQuery->where('user_id', $user->id);
    }

    $trashTasks = $trashQuery->count();

    $recentTasks = (clone $taskQuery)
        ->orderBy('updated_at', 'desc')
        ->take(4)
        ->get();

    $focusTask = (clone $taskQuery)
        ->whereIn('status', ['pending', 'not_started'])
        ->orderBy('due_date')
        ->orderBy('due_time')
        ->first();

    $totalUsers = \App\Models\User::count();
    $adminUsers = \App\Models\User::role('superadmin')->count();
    $staffUsers = \App\Models\User::role('staff')->count();

    $recentActivities = class_exists(\Spatie\Activitylog\Models\Activity::class)
        ? \Spatie\Activitylog\Models\Activity::latest()->take(5)->get()
        : collect();

    $statusLabels = [
        'not_started' => 'Not Yet Started',
        'pending' => 'Pending',
        'completed' => 'Completed',
    ];

    $statusStyles = [
        'not_started' => 'background:#fef3c7;color:#92400e;',
        'pending' => 'background:#e0e7ff;color:#3730a3;',
        'completed' => 'background:#dcfce7;color:#166534;',
    ];

    $progressPercent = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $isAdmin ? 'Admin Dashboard' : 'Dashboard' }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                {{ $isAdmin ? 'Monitor users, tasks, roles, and system activity.' : 'Quick overview of your workspace.' }}
            </p>
        </div>
    </x-slot>

    <div style="background:linear-gradient(180deg,#eef2ff 0%,#f8fafc 55%,#eef2ff 100%);min-height:100vh;padding:32px;">
        <div style="max-width:1180px;margin:auto;">

            @if ($isAdmin)
                <div style="background:linear-gradient(135deg,#111827,#312e81);color:white;padding:30px;border-radius:20px;box-shadow:0 10px 25px rgba(17,24,39,.18);margin-bottom:24px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:24px;flex-wrap:wrap;">
                        <div>
                            <p style="margin:0 0 8px;color:#c7d2fe;font-weight:bold;">Admin Workspace</p>
                            <h1 style="font-size:34px;margin:0;">Welcome, {{ $user->name }}</h1>
                            <p style="margin:10px 0 0;color:#e5e7eb;">
                                Signed in as
                                <span style="background:rgba(255,255,255,.16);padding:6px 10px;border-radius:999px;font-weight:bold;">
                                    {{ $roles }}
                                </span>
                            </p>
                        </div>

                        <div style="display:flex;gap:10px;flex-wrap:wrap;">
                            <a href="{{ route('sub-content.index') }}"
                               style="background:white;color:#312e81;padding:14px 18px;border-radius:12px;text-decoration:none;font-weight:bold;">
                                Open Task Workspace →
                            </a>

                            @can('view activity logs')
                                <a href="{{ route('activity-logs.index') }}"
                                   style="background:rgba(255,255,255,.15);color:white;padding:14px 18px;border-radius:12px;text-decoration:none;font-weight:bold;border:1px solid rgba(255,255,255,.25);">
                                    Activity Logs
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px;">
                    <div style="background:white;padding:20px;border-radius:16px;box-shadow:0 8px 20px rgba(15,23,42,.08);border-top:5px solid #111827;">
                        <div style="color:#6b7280;font-weight:bold;">Users</div>
                        <div style="font-size:32px;font-weight:bold;color:#111827;margin-top:8px;">{{ $totalUsers }}</div>
                    </div>

                    <div style="background:#eef2ff;padding:20px;border-radius:16px;box-shadow:0 8px 20px rgba(15,23,42,.08);border-top:5px solid #4f46e5;">
                        <div style="color:#3730a3;font-weight:bold;">Total Tasks</div>
                        <div style="font-size:32px;font-weight:bold;color:#3730a3;margin-top:8px;">{{ $totalTasks }}</div>
                    </div>

                    <div style="background:#fff7ed;padding:20px;border-radius:16px;box-shadow:0 8px 20px rgba(15,23,42,.08);border-top:5px solid #f59e0b;">
                        <div style="color:#92400e;font-weight:bold;">Pending / Not Started</div>
                        <div style="font-size:32px;font-weight:bold;color:#92400e;margin-top:8px;">{{ $pendingTasks + $notStartedTasks }}</div>
                    </div>

                    <div style="background:#ecfdf5;padding:20px;border-radius:16px;box-shadow:0 8px 20px rgba(15,23,42,.08);border-top:5px solid #16a34a;">
                        <div style="color:#166534;font-weight:bold;">Completed</div>
                        <div style="font-size:32px;font-weight:bold;color:#166534;margin-top:8px;">{{ $completedTasks }}</div>
                    </div>

                    <div style="background:#fff1f2;padding:20px;border-radius:16px;box-shadow:0 8px 20px rgba(15,23,42,.08);border-top:5px solid #dc2626;">
                        <div style="color:#991b1b;font-weight:bold;">Trash</div>
                        <div style="font-size:32px;font-weight:bold;color:#991b1b;margin-top:8px;">{{ $trashTasks }}</div>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:22px;margin-bottom:24px;">
                    <div style="background:white;padding:24px;border-radius:18px;box-shadow:0 8px 22px rgba(15,23,42,.08);">
                        <h3 style="margin:0 0 14px;font-size:24px;color:#111827;">User Overview</h3>

                        <div style="display:grid;gap:12px;">
                            <div style="display:flex;justify-content:space-between;align-items:center;background:#f8fafc;padding:16px;border-radius:14px;border:1px solid #e5e7eb;">
                                <span style="font-weight:bold;color:#111827;">Superadmins</span>
                                <span style="background:#e0e7ff;color:#3730a3;padding:6px 10px;border-radius:999px;font-weight:bold;">{{ $adminUsers }}</span>
                            </div>

                            <div style="display:flex;justify-content:space-between;align-items:center;background:#f8fafc;padding:16px;border-radius:14px;border:1px solid #e5e7eb;">
                                <span style="font-weight:bold;color:#111827;">Staff Users</span>
                                <span style="background:#dcfce7;color:#166534;padding:6px 10px;border-radius:999px;font-weight:bold;">{{ $staffUsers }}</span>
                            </div>

                            <div style="display:flex;justify-content:space-between;align-items:center;background:#f8fafc;padding:16px;border-radius:14px;border:1px solid #e5e7eb;">
                                <span style="font-weight:bold;color:#111827;">Total Registered Users</span>
                                <span style="background:#f3f4f6;color:#111827;padding:6px 10px;border-radius:999px;font-weight:bold;">{{ $totalUsers }}</span>
                            </div>
                        </div>
                    </div>

                    <div style="background:white;padding:24px;border-radius:18px;box-shadow:0 8px 22px rgba(15,23,42,.08);">
                        <h3 style="margin:0 0 14px;font-size:24px;color:#111827;">Admin Actions</h3>

                        <div style="display:grid;gap:12px;">
                            <a href="{{ route('sub-content.index') }}" style="display:block;background:#111827;color:white;padding:14px 16px;border-radius:12px;text-decoration:none;font-weight:bold;">
                                Open Task Workspace
                            </a>

                            @can('view activity logs')
                                <a href="{{ route('activity-logs.index') }}" style="display:block;background:#4f46e5;color:white;padding:14px 16px;border-radius:12px;text-decoration:none;font-weight:bold;">
                                    View Activity Logs
                                </a>
                            @endcan

                            <a href="{{ route('sub-content.delete') }}" style="display:block;background:#fff1f2;color:#991b1b;padding:14px 16px;border-radius:12px;text-decoration:none;font-weight:bold;border:1px solid #fecaca;">
                                Review Trash
                            </a>

                            <a href="{{ route('profile.edit') }}" style="display:block;background:#f3f4f6;color:#111827;padding:14px 16px;border-radius:12px;text-decoration:none;font-weight:bold;">
                                Profile Settings
                            </a>
                        </div>
                    </div>
                </div>

                <div style="background:white;padding:24px;border-radius:18px;box-shadow:0 8px 22px rgba(15,23,42,.08);">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:18px;flex-wrap:wrap;">
                        <div>
                            <h3 style="margin:0;font-size:24px;color:#111827;">Recent System Activity</h3>
                            <p style="margin:6px 0 0;color:#6b7280;">Latest recorded actions in the application.</p>
                        </div>

                        @can('view activity logs')
                            <a href="{{ route('activity-logs.index') }}" style="color:#4f46e5;font-weight:bold;text-decoration:none;">
                                View All →
                            </a>
                        @endcan
                    </div>

                    <div style="display:grid;gap:12px;">
                        @forelse ($recentActivities as $activity)
                            <div style="background:#f8fafc;border:1px solid #e5e7eb;padding:16px;border-radius:14px;">
                                <div style="display:flex;justify-content:space-between;gap:12px;align-items:start;flex-wrap:wrap;">
                                    <div>
                                        <h4 style="margin:0 0 6px;color:#111827;font-size:17px;">
                                            {{ $activity->description }}
                                        </h4>
                                        <p style="margin:0;color:#6b7280;font-size:14px;">
                                            Causer ID: {{ $activity->causer_id ?? 'N/A' }}
                                        </p>
                                    </div>

                                    <span style="color:#6b7280;font-size:14px;">
                                        {{ $activity->created_at ? $activity->created_at->format('M j, Y g:i A') : 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div style="padding:20px;border:1px dashed #cbd5e1;border-radius:14px;color:#6b7280;background:#f8fafc;">
                                No activity logs yet.
                            </div>
                        @endforelse
                    </div>
                </div>

            @else
                <div style="background:linear-gradient(135deg,#111827,#312e81);color:white;padding:30px;border-radius:20px;box-shadow:0 10px 25px rgba(17,24,39,.18);margin-bottom:24px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:24px;flex-wrap:wrap;">
                        <div>
                            <p style="margin:0 0 8px;color:#c7d2fe;font-weight:bold;">Welcome back</p>
                            <h1 style="font-size:34px;margin:0;">{{ $user->name }}</h1>
                            <p style="margin:10px 0 0;color:#e5e7eb;">
                                Signed in as
                                <span style="background:rgba(255,255,255,.16);padding:6px 10px;border-radius:999px;font-weight:bold;">
                                    {{ $roles }}
                                </span>
                            </p>
                        </div>

                        <a href="{{ route('sub-content.index') }}"
                           style="background:white;color:#312e81;padding:16px 20px;border-radius:14px;text-decoration:none;font-weight:bold;box-shadow:0 8px 20px rgba(0,0,0,.18);">
                            Open Task Workspace →
                        </a>
                    </div>
                </div>

                <a href="{{ route('sub-content.index') }}"
                   style="display:block;background:white;padding:26px;border-radius:18px;text-decoration:none;color:#111827;box-shadow:0 8px 22px rgba(15,23,42,.08);border:1px solid #e5e7eb;margin-bottom:24px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:20px;flex-wrap:wrap;">
                        <div>
                            <p style="margin:0 0 8px;color:#4f46e5;font-weight:bold;">Task Manager</p>
                            <h2 style="font-size:28px;margin:0 0 8px;">Go to your full Tasks interface</h2>
                            <p style="margin:0;color:#6b7280;line-height:1.5;">
                                Open the main workspace to create, view, edit, trash, and restore tasks.
                            </p>
                        </div>

                        <div style="background:#eef2ff;color:#3730a3;padding:16px 18px;border-radius:14px;font-weight:bold;">
                            {{ $activeTasks }} active task{{ $activeTasks === 1 ? '' : 's' }}
                        </div>
                    </div>
                </a>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:22px;margin-bottom:24px;">
                    <div style="background:white;padding:24px;border-radius:18px;box-shadow:0 8px 22px rgba(15,23,42,.08);">
                        <h3 style="margin:0 0 10px;font-size:24px;color:#111827;">Today’s Focus</h3>
                        <p style="margin:0 0 18px;color:#6b7280;">Start with your nearest unfinished task.</p>

                        @if ($focusTask)
                            @php
                                $dueDate = $focusTask->due_date ? $focusTask->due_date->format('F j, Y') : 'No due date';
                                $dueTime = $focusTask->due_time ? \Carbon\Carbon::parse($focusTask->due_time)->format('g:i A') : null;
                                $badgeStyle = $statusStyles[$focusTask->status] ?? $statusStyles['not_started'];
                            @endphp

                            <div style="background:#f8fafc;border:1px solid #e5e7eb;padding:18px;border-radius:14px;">
                                <div style="display:flex;justify-content:space-between;gap:14px;align-items:start;flex-wrap:wrap;">
                                    <div>
                                        <h4 style="margin:0 0 8px;color:#111827;font-size:22px;">{{ $focusTask->title }}</h4>
                                        <p style="margin:0;color:#6b7280;font-size:14px;">
                                            Due: {{ $dueDate }}@if($dueTime) at {{ $dueTime }}@endif
                                        </p>
                                    </div>

                                    <span style="display:inline-block;padding:6px 10px;border-radius:999px;font-size:13px;font-weight:bold;{{ $badgeStyle }}">
                                        {{ $statusLabels[$focusTask->status] ?? $focusTask->status }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:18px;border-radius:14px;color:#166534;">
                                Nice work. No unfinished tasks found.
                            </div>
                        @endif
                    </div>

                    <div style="background:white;padding:24px;border-radius:18px;box-shadow:0 8px 22px rgba(15,23,42,.08);">
                        <h3 style="margin:0 0 10px;font-size:24px;color:#111827;">Progress Overview</h3>
                        <p style="margin:0 0 18px;color:#6b7280;">
                            {{ $completedTasks }} of {{ $totalTasks }} tasks completed.
                        </p>

                        <div style="background:#e5e7eb;height:14px;border-radius:999px;overflow:hidden;margin-bottom:14px;">
                            <div style="width:{{ $progressPercent }}%;background:#16a34a;height:100%;border-radius:999px;"></div>
                        </div>

                        <div style="display:flex;gap:10px;flex-wrap:wrap;color:#6b7280;font-size:14px;">
                            <span style="background:#fff7ed;color:#92400e;padding:6px 10px;border-radius:999px;font-weight:bold;">
                                {{ $notStartedTasks }} not started
                            </span>
                            <span style="background:#eef2ff;color:#3730a3;padding:6px 10px;border-radius:999px;font-weight:bold;">
                                {{ $pendingTasks }} pending
                            </span>
                            <span style="background:#dcfce7;color:#166534;padding:6px 10px;border-radius:999px;font-weight:bold;">
                                {{ $completedTasks }} completed
                            </span>
                        </div>
                    </div>
                </div>

                <div style="background:white;padding:24px;border-radius:18px;box-shadow:0 8px 22px rgba(15,23,42,.08);">
                    <h3 style="margin:0 0 14px;font-size:24px;color:#111827;">Recent Updates</h3>

                    <div style="display:grid;gap:12px;">
                        @forelse ($recentTasks as $task)
                            @php
                                $dueDate = $task->due_date ? $task->due_date->format('M j, Y') : 'No due date';
                                $dueTime = $task->due_time ? \Carbon\Carbon::parse($task->due_time)->format('g:i A') : null;
                                $badgeStyle = $statusStyles[$task->status] ?? $statusStyles['not_started'];
                            @endphp

                            <div style="padding:16px;border:1px solid #e5e7eb;border-radius:14px;background:#f8fafc;">
                                <div style="display:flex;justify-content:space-between;gap:12px;align-items:start;flex-wrap:wrap;">
                                    <div>
                                        <h4 style="margin:0 0 6px;color:#111827;font-size:18px;">{{ $task->title }}</h4>
                                        <p style="margin:0;color:#6b7280;font-size:14px;overflow-wrap:anywhere;">
                                            Due: {{ $dueDate }}@if($dueTime) at {{ $dueTime }}@endif
                                            • Updated: {{ $task->updated_at ? $task->updated_at->format('M j, Y g:i A') : 'N/A' }}
                                        </p>
                                    </div>

                                    <span style="display:inline-block;padding:6px 10px;border-radius:999px;font-size:13px;font-weight:bold;{{ $badgeStyle }}">
                                        {{ $statusLabels[$task->status] ?? $task->status }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div style="padding:20px;border:1px dashed #cbd5e1;border-radius:14px;color:#6b7280;background:#f8fafc;">
                                No recent tasks yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
