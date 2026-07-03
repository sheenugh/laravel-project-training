@php
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (auth()->check()) {
        activity()
            ->causedBy(auth()->user())
            ->log('User visited Tasks page');
    }

    $user = auth()->user();
    $roles = $user ? $user->getRoleNames()->implode(', ') : 'No role';

    $taskQuery = \App\Models\Todo::query();

    if (! auth()->user()->hasRole('superadmin')) {
        $taskQuery->where('user_id', auth()->id());
    }

    $totalTasks = (clone $taskQuery)->count();
    $notStartedTasks = (clone $taskQuery)->where('status', 'not_started')->count();
    $pendingTasks = (clone $taskQuery)->where('status', 'pending')->count();
    $completedTasks = (clone $taskQuery)->where('status', 'completed')->count();
    $activeTasks = $notStartedTasks + $pendingTasks;

    $trashQuery = \App\Models\Todo::onlyTrashed();

    if (! auth()->user()->hasRole('superadmin')) {
        $trashQuery->where('user_id', auth()->id());
    }

    $trashedTasks = $trashQuery->count();
@endphp

<div style="font-family:Arial,sans-serif;background:#f3f4f6;min-height:100vh;padding:30px;">
    <div style="max-width:1180px;margin:auto;">

        <div style="display:flex;justify-content:space-between;align-items:center;gap:20px;flex-wrap:wrap;margin-bottom:28px;">
            <div>
                <h1 style="font-size:38px;margin:0;color:#111827;">Tasks</h1>
                <p style="color:#6b7280;margin-top:8px;font-size:16px;">
                    Manage your work, deadlines, and progress in one workspace.
                </p>
            </div>

            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                @can('create sub-content')
                    <a href="{{ route('sub-content.create') }}"
                       style="background:#4f46e5;color:white;padding:12px 16px;border-radius:10px;text-decoration:none;font-weight:bold;">
                        + New Task
                    </a>
                @endcan

                <a href="{{ route('dashboard') }}"
                   style="background:white;color:#111827;padding:12px 16px;border-radius:10px;text-decoration:none;font-weight:bold;border:1px solid #d1d5db;">
                    Dashboard
                </a>
            </div>
        </div>

        <div style="background:linear-gradient(135deg,#111827,#312e81);color:white;padding:28px;border-radius:18px;box-shadow:0 10px 25px rgba(17,24,39,.18);margin-bottom:24px;">
            <div style="display:flex;justify-content:space-between;gap:20px;align-items:center;flex-wrap:wrap;">
                <div>
                    <p style="margin:0 0 8px;color:#c7d2fe;font-weight:bold;">Welcome back</p>
                    <h2 style="font-size:28px;margin:0;">{{ $user->name }}</h2>
                    <p style="margin:10px 0 0;color:#e5e7eb;">
                        Current role:
                        <span style="background:rgba(255,255,255,.16);padding:6px 10px;border-radius:999px;font-weight:bold;">
                            {{ $roles }}
                        </span>
                    </p>
                </div>

                <div style="background:rgba(255,255,255,.12);padding:18px;border-radius:14px;min-width:220px;">
                    <p style="margin:0;color:#c7d2fe;">Active tasks</p>
                    <div style="font-size:42px;font-weight:bold;margin-top:4px;">{{ $activeTasks }}</div>
                    <p style="margin:0;color:#e5e7eb;font-size:14px;">Unfinished tasks in your workspace</p>
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:16px;margin-bottom:26px;">
            <div style="background:white;padding:20px;border-radius:14px;box-shadow:0 1px 4px rgba(0,0,0,.08);border-top:5px solid #111827;">
                <div style="color:#6b7280;font-weight:bold;">Total Tasks</div>
                <div style="font-size:32px;font-weight:bold;color:#111827;margin-top:8px;">{{ $totalTasks }}</div>
            </div>

            <div style="background:#fffbeb;padding:20px;border-radius:14px;box-shadow:0 1px 4px rgba(0,0,0,.08);border-top:5px solid #f59e0b;">
                <div style="color:#92400e;font-weight:bold;">Not Yet Started</div>
                <div style="font-size:32px;font-weight:bold;color:#92400e;margin-top:8px;">{{ $notStartedTasks }}</div>
            </div>

            <div style="background:#eef2ff;padding:20px;border-radius:14px;box-shadow:0 1px 4px rgba(0,0,0,.08);border-top:5px solid #4f46e5;">
                <div style="color:#3730a3;font-weight:bold;">Pending</div>
                <div style="font-size:32px;font-weight:bold;color:#3730a3;margin-top:8px;">{{ $pendingTasks }}</div>
            </div>

            <div style="background:#f0fdf4;padding:20px;border-radius:14px;box-shadow:0 1px 4px rgba(0,0,0,.08);border-top:5px solid #16a34a;">
                <div style="color:#166534;font-weight:bold;">Completed</div>
                <div style="font-size:32px;font-weight:bold;color:#166534;margin-top:8px;">{{ $completedTasks }}</div>
            </div>

            <div style="background:#fef2f2;padding:20px;border-radius:14px;box-shadow:0 1px 4px rgba(0,0,0,.08);border-top:5px solid #dc2626;">
                <div style="color:#991b1b;font-weight:bold;">Trash</div>
                <div style="font-size:32px;font-weight:bold;color:#991b1b;margin-top:8px;">{{ $trashedTasks }}</div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:18px;margin-bottom:26px;">
            @can('create sub-content')
                <a href="{{ route('sub-content.create') }}"
                   style="background:white;padding:24px;border-radius:16px;text-decoration:none;color:#111827;box-shadow:0 1px 4px rgba(0,0,0,.08);border:1px solid #e5e7eb;">
                    <div style="font-size:28px;margin-bottom:12px;">＋</div>
                    <h3 style="margin:0 0 8px;font-size:22px;">Create Task</h3>
                    <p style="margin:0;color:#6b7280;line-height:1.5;">Add a new task with due date, due time, and status.</p>
                </a>
            @endcan

            @can('view sub-content')
                <a href="{{ route('sub-content.view') }}"
                   style="background:white;padding:24px;border-radius:16px;text-decoration:none;color:#111827;box-shadow:0 1px 4px rgba(0,0,0,.08);border:1px solid #e5e7eb;">
                    <div style="font-size:28px;margin-bottom:12px;">☰</div>
                    <h3 style="margin:0 0 8px;font-size:22px;">View Tasks</h3>
                    <p style="margin:0;color:#6b7280;line-height:1.5;">Search and review tasks grouped by current status.</p>
                </a>
            @endcan

            @can('edit sub-content')
                <a href="{{ route('sub-content.edit') }}"
                   style="background:white;padding:24px;border-radius:16px;text-decoration:none;color:#111827;box-shadow:0 1px 4px rgba(0,0,0,.08);border:1px solid #e5e7eb;">
                    <div style="font-size:28px;margin-bottom:12px;">✎</div>
                    <h3 style="margin:0 0 8px;font-size:22px;">Edit Tasks</h3>
                    <p style="margin:0;color:#6b7280;line-height:1.5;">Update task details, status, schedule, or move a task to Trash.</p>
                </a>
            @endcan

            @can('delete sub-content')
                <a href="{{ route('sub-content.delete') }}"
                   style="background:white;padding:24px;border-radius:16px;text-decoration:none;color:#111827;box-shadow:0 1px 4px rgba(0,0,0,.08);border:1px solid #fecaca;">
                    <div style="font-size:28px;margin-bottom:12px;">🗑️</div>
                    <h3 style="margin:0 0 8px;font-size:22px;color:#991b1b;">Trash</h3>
                    <p style="margin:0;color:#6b7280;line-height:1.5;">Review deleted tasks and restore them if needed.</p>
                </a>
            @endcan
        </div>


        @if(auth()->user()->hasRole('superadmin'))
            <a href="{{ route('activity-logs.index') }}"
               style="display:block;background:#f8fafc;padding:24px;border-radius:18px;text-decoration:none;color:#111827;box-shadow:0 12px 28px rgba(15,23,42,.12);border:1px solid #cbd5e1;margin-bottom:22px;">
                <div style="font-size:28px;margin-bottom:12px;">📋</div>
                <h3 style="margin:0 0 8px;font-size:22px;color:#111827;">Activity Logs</h3>
                <p style="margin:0;color:#6b7280;line-height:1.5;">Review user actions, task activity, and system records.</p>
            </a>
        @endif


        <div style="background:white;padding:24px;border-radius:16px;box-shadow:0 1px 4px rgba(0,0,0,.08);">
            <h3 style="margin:0 0 14px;color:#111827;font-size:22px;">Status Guide</h3>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;">
                <div style="background:#fffbeb;border:1px solid #fde68a;padding:16px;border-radius:12px;">
                    <strong style="color:#92400e;">Not Yet Started</strong>
                    <p style="margin:8px 0 0;color:#78350f;">Tasks planned but not started.</p>
                </div>

                <div style="background:#eef2ff;border:1px solid #c7d2fe;padding:16px;border-radius:12px;">
                    <strong style="color:#3730a3;">Pending</strong>
                    <p style="margin:8px 0 0;color:#312e81;">Tasks currently in progress or waiting.</p>
                </div>

                <div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:16px;border-radius:12px;">
                    <strong style="color:#166534;">Completed</strong>
                    <p style="margin:8px 0 0;color:#14532d;">Tasks already finished.</p>
                </div>

                <div style="background:#fef2f2;border:1px solid #fecaca;padding:16px;border-radius:12px;">
                    <strong style="color:#991b1b;">Trash</strong>
                    <p style="margin:8px 0 0;color:#7f1d1d;">Deleted tasks that can still be restored.</p>
                </div>
            </div>
        </div>

    </div>
</div>
