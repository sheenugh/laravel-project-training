@php
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (auth()->check()) {
        activity()
            ->causedBy(auth()->user())
            ->log('User visited Sub Content page');
    }

    $user = auth()->user();
    $roles = $user ? $user->getRoleNames()->implode(', ') : 'No role';
@endphp

<div style="font-family: Arial, sans-serif; background:#f3f4f6; min-height:100vh; padding:30px;">
    <div style="max-width:1100px; margin:auto;">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
            <div>
                <h1 style="font-size:32px; margin:0; color:#111827;">Tasks</h1>
                <p style="color:#6b7280; margin-top:8px;">
                    Manage your tasks, deadlines, and progress.
                </p>
            </div>

            <a href="{{ route('dashboard') }}" style="color:#4f46e5; font-weight:bold; text-decoration:none;">
                Back to Dashboard
            </a>
        </div>

        <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.08); margin-bottom:20px;">
            <h2 style="margin:0 0 10px; color:#111827;">Welcome, {{ $user?->name }}</h2>
            <p style="margin:0; color:#374151;">
                Current Role:
                <strong style="background:#e0e7ff; color:#3730a3; padding:6px 10px; border-radius:999px;">
                    {{ $roles }}
                </strong>
            </p>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:20px;">
            @can('create sub-content')
                <a href="{{ route('sub-content.create') }}" wire:navigate style="background:white; padding:20px; border-radius:12px; text-decoration:none; color:#111827; box-shadow:0 1px 4px rgba(0,0,0,.08);">
                    <h3>Create</h3>
                    <p>Add a new task to your workspace.</p>
                </a>
            @endcan

            @can('view sub-content')
                <a href="{{ route('sub-content.view') }}" wire:navigate style="background:white; padding:20px; border-radius:12px; text-decoration:none; color:#111827; box-shadow:0 1px 4px rgba(0,0,0,.08);">
                    <h3>View</h3>
                    <p>View your current and upcoming tasks.</p>
                </a>
            @endcan

            @can('edit sub-content')
                <a href="{{ route('sub-content.edit') }}" wire:navigate style="background:white; padding:20px; border-radius:12px; text-decoration:none; color:#111827; box-shadow:0 1px 4px rgba(0,0,0,.08);">
                    <h3>Edit</h3>
                    <p>Update task details, status, or schedule.</p>
                </a>
            @endcan

            @can('delete sub-content')
                <a href="{{ route('sub-content.delete') }}" wire:navigate style="background:white; padding:20px; border-radius:12px; text-decoration:none; color:#111827; box-shadow:0 1px 4px rgba(0,0,0,.08);">
                    <h3>Delete</h3>
                    <p>Remove tasks that are no longer needed.</p>
                </a>
            @endcan
        </div>

        <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.08);">
            <h3 style="margin-top:0;">Module Status</h3>
            <p style="color:#4b5563;">
                Your available actions depend on your account access. Task visits and important actions are recorded for accountability.
            </p>

            @can('view activity logs')
                <a href="{{ route('activity-logs.index') }}" wire:navigate style="display:inline-block; margin-top:10px; background:#111827; color:white; padding:10px 14px; border-radius:8px; text-decoration:none;">
                    View Activity Logs
                </a>
            @endcan
        </div>

    </div>
</div>
