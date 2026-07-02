@php
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (! auth()->user()?->can('delete sub-content')) {
        abort(403, 'You do not have permission to delete tasks.');
    }

    activity()
        ->causedBy(auth()->user())
        ->log('User opened Delete Tasks page');

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
    <div style="max-width:1000px; margin:auto;">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
            <div>
                <h1 style="font-size:32px; margin:0; color:#111827;">Delete Tasks</h1>
                <p style="color:#6b7280; margin-top:8px;">
                    Review tasks before removing them from your workspace.
                </p>
            </div>

            <a href="{{ route('sub-content.index') }}" style="color:#4f46e5; font-weight:bold; text-decoration:none;">
                Back to Tasks
            </a>
        </div>

        <div style="background:#fff7ed; border:1px solid #fed7aa; padding:16px; border-radius:12px; margin-bottom:20px;">
            <strong style="color:#9a3412;">Reminder:</strong>
            <span style="color:#7c2d12;">
                Deleting a task is a sensitive action. In a live system, this should require confirmation.
            </span>
        </div>

        <div style="display:grid; gap:16px;">
            @foreach ($tasks as $task)
                <div style="background:white; padding:22px; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.08);">
                    <div style="display:flex; justify-content:space-between; align-items:center; gap:18px; flex-wrap:wrap;">
                        <div>
                            <h3 style="font-size:20px; margin:0 0 8px; color:#111827;">
                                {{ $task['title'] }}
                            </h3>
                            <p style="margin:0 0 8px; color:#4b5563;">
                                {{ $task['description'] }}
                            </p>
                            <p style="margin:0; color:#6b7280; font-size:14px;">
                                Status: {{ $task['status'] }} • Due: {{ $task['due'] }}
                            </p>
                        </div>

                        <button type="button"
                            style="background:#dc2626; color:white; padding:12px 16px; border:0; border-radius:8px; font-weight:bold;">
                            Delete
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
