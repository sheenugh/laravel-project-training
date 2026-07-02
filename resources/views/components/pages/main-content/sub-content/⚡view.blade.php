@php
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (! auth()->user()?->can('view sub-content')) {
        abort(403, 'You do not have permission to view tasks.');
    }

    activity()
        ->causedBy(auth()->user())
        ->log('User opened View Tasks page');

    $tasks = [
        [
            'title' => 'Complete Laravel Authentication',
            'description' => 'Set up login, registration, and profile management.',
            'status' => 'Completed',
            'due' => 'July 1, 2026',
        ],
        [
            'title' => 'Implement Spatie Roles and Permissions',
            'description' => 'Add user roles, permissions, and role-based access.',
            'status' => 'In Progress',
            'due' => 'July 2, 2026',
        ],
        [
            'title' => 'Improve Task Manager Interface',
            'description' => 'Polish the task pages and make the app more user-friendly.',
            'status' => 'Pending',
            'due' => 'July 3, 2026',
        ],
    ];
@endphp

<div style="font-family: Arial, sans-serif; background:#f3f4f6; min-height:100vh; padding:30px;">
    <div style="max-width:1100px; margin:auto;">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
            <div>
                <h1 style="font-size:32px; margin:0; color:#111827;">View Tasks</h1>
                <p style="color:#6b7280; margin-top:8px;">
                    Review your tasks, deadlines, and current progress.
                </p>
            </div>

            <a href="{{ route('sub-content.index') }}" style="color:#4f46e5; font-weight:bold; text-decoration:none;">
                Back to Tasks
            </a>
        </div>

        <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.08); margin-bottom:20px;">
            <input type="text" placeholder="Search tasks..."
                style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:8px;">
        </div>

        <div style="background:white; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.08); overflow:hidden;">
            @foreach ($tasks as $task)
                <div style="padding:20px; border-bottom:1px solid #e5e7eb;">
                    <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap;">
                        <div>
                            <h3 style="font-size:20px; margin:0 0 8px; color:#111827;">
                                {{ $task['title'] }}
                            </h3>
                            <p style="margin:0; color:#4b5563;">
                                {{ $task['description'] }}
                            </p>
                        </div>

                        <div style="text-align:right;">
                            <div style="margin-bottom:8px;">
                                <span style="
                                    display:inline-block;
                                    padding:6px 10px;
                                    border-radius:999px;
                                    font-size:13px;
                                    font-weight:bold;
                                    background:
                                        {{ $task['status'] === 'Completed' ? '#dcfce7' : ($task['status'] === 'In Progress' ? '#e0e7ff' : '#fef3c7') }};
                                    color:
                                        {{ $task['status'] === 'Completed' ? '#166534' : ($task['status'] === 'In Progress' ? '#3730a3' : '#92400e') }};
                                ">
                                    {{ $task['status'] }}
                                </span>
                            </div>

                            <div style="color:#6b7280; font-size:14px;">
                                Due: {{ $task['due'] }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
