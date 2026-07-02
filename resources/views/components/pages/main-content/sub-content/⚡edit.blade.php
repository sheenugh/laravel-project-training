@php
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (! auth()->user()?->can('edit sub-content')) {
        abort(403, 'You do not have permission to edit tasks.');
    }

    activity()
        ->causedBy(auth()->user())
        ->log('User opened Edit Tasks page');

    $tasks = [
        [
            'title' => 'Complete Laravel Authentication',
            'description' => 'Set up login, registration, and profile management.',
            'status' => 'Completed',
            'due' => '2026-07-01',
        ],
        [
            'title' => 'Implement Spatie Roles and Permissions',
            'description' => 'Add user roles, permissions, and role-based access.',
            'status' => 'In Progress',
            'due' => '2026-07-02',
        ],
        [
            'title' => 'Improve Task Manager Interface',
            'description' => 'Polish the task pages and make the app more user-friendly.',
            'status' => 'Pending',
            'due' => '2026-07-03',
        ],
    ];
@endphp

<div style="font-family: Arial, sans-serif; background:#f3f4f6; min-height:100vh; padding:30px;">
    <div style="max-width:1100px; margin:auto;">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
            <div>
                <h1 style="font-size:32px; margin:0; color:#111827;">Edit Tasks</h1>
                <p style="color:#6b7280; margin-top:8px;">
                    Update task details, status, and deadlines.
                </p>
            </div>

            <a href="{{ route('sub-content.index') }}" style="color:#4f46e5; font-weight:bold; text-decoration:none;">
                Back to Tasks
            </a>
        </div>

        <div style="display:grid; gap:18px;">
            @foreach ($tasks as $task)
                <div style="background:white; padding:22px; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.08);">
                    <div style="display:grid; grid-template-columns:2fr 1fr 1fr auto; gap:16px; align-items:end;">
                        <div>
                            <label style="display:block; font-weight:bold; margin-bottom:8px; color:#111827;">Task Title</label>
                            <input type="text" value="{{ $task['title'] }}"
                                style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:8px;">
                            <p style="margin:8px 0 0; color:#6b7280; font-size:14px;">
                                {{ $task['description'] }}
                            </p>
                        </div>

                        <div>
                            <label style="display:block; font-weight:bold; margin-bottom:8px; color:#111827;">Status</label>
                            <select style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:8px;">
                                <option {{ $task['status'] === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option {{ $task['status'] === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option {{ $task['status'] === 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div>
                            <label style="display:block; font-weight:bold; margin-bottom:8px; color:#111827;">Due Date</label>
                            <input type="date" value="{{ $task['due'] }}"
                                style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:8px;">
                        </div>

                        <button type="button"
                            style="background:#4f46e5; color:white; padding:12px 16px; border:0; border-radius:8px; font-weight:bold;">
                            Update
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
