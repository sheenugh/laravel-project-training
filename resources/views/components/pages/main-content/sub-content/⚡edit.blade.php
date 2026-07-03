@php
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (! auth()->user()?->can('edit sub-content')) {
        abort(403, 'You do not have permission to edit tasks.');
    }

    $taskId = request('task');
    $users = \App\Models\User::orderBy('name')->get();

    if ($taskId) {
        $query = \App\Models\Todo::where('id', $taskId);

        if (! auth()->user()->hasRole('superadmin')) {
            $query->where('user_id', auth()->id());
        }

        $task = $query->firstOrFail();
    } else {
        $query = \App\Models\Todo::query();

        if (! auth()->user()->hasRole('superadmin')) {
            $query->where('user_id', auth()->id());
        }

        $tasks = $query->orderBy('updated_at', 'desc')->get();
    }

    $statusLabels = [
        'not_started' => 'Not Yet Started',
        'pending' => 'Pending',
        'completed' => 'Completed',
    ];

    $statusStyles = [
        'not_started' => ['badge' => 'background:#fef3c7;color:#92400e;', 'border' => '#f59e0b'],
        'pending' => ['badge' => 'background:#e0e7ff;color:#3730a3;', 'border' => '#4f46e5'],
        'completed' => ['badge' => 'background:#dcfce7;color:#166534;', 'border' => '#16a34a'],
    ];

    activity()->causedBy(auth()->user())->log('User opened Edit Task page');
@endphp

<div style="font-family:Arial,sans-serif;background:#f3f4f6;min-height:100vh;padding:30px;">
    <div style="max-width:1000px;margin:auto;">

        @include('components.pages.main-content.sub-content._task-nav')

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:25px;">
            <div>
                <h1 style="font-size:32px;margin:0;color:#111827;">Edit Task</h1>
                <p style="color:#6b7280;margin-top:8px;">Update task details, status, and due date.</p>
            </div>

            <a href="{{ route('sub-content.index') }}" style="color:#4f46e5;font-weight:bold;text-decoration:none;">
                Back to Tasks
            </a>
        </div>

        @if (session('success'))
            <div style="background:#dcfce7;color:#166534;padding:14px;border-radius:10px;margin-bottom:18px;border:1px solid #bbf7d0;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="background:#fee2e2;color:#991b1b;padding:14px;border-radius:10px;margin-bottom:18px;border:1px solid #fecaca;">
                <strong>Please check the form:</strong>
                <ul style="margin:8px 0 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($taskId)
            @php
                $dueTimeValue = $task->due_time ? \Carbon\Carbon::parse($task->due_time)->format('H:i') : '';
            @endphp

            <div style="background:white;padding:28px;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);">
                <form method="POST" action="{{ route('tasks.update', $task) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="return_to" value="{{ request('return_to') }}">

                    <div style="margin-bottom:18px;">
                        <label style="display:block;font-weight:bold;margin-bottom:8px;">Task Title</label>
                        <input name="title" type="text" value="{{ $task->title }}" required
                            style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:8px;">
                    </div>

                    <div style="margin-bottom:18px;">
                        <label style="display:block;font-weight:bold;margin-bottom:8px;">Description</label>
                        <textarea name="description"
                            style="width:100%;min-height:110px;padding:12px;border:1px solid #d1d5db;border-radius:8px;">{{ $task->description }}</textarea>
                    </div>


                    @if (auth()->user()->hasRole('superadmin'))
                        <div style="margin-bottom:18px;">
                            <label style="display:block;font-weight:bold;margin-bottom:8px;">Assign To</label>
                            <select name="assigned_user_id" required
                                style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:8px;">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ $task->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} — {{ $user->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div style="margin-bottom:18px;background:#eef2ff;color:#3730a3;padding:14px;border-radius:10px;">
                            Assigned to: <strong>{{ $task->user?->name ?? 'Unknown User' }}</strong>
                        </div>
                    @endif

                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:18px;">
                        <div>
                            <label style="display:block;font-weight:bold;margin-bottom:8px;">Due Date</label>

                            @if (auth()->user()->hasRole('superadmin'))
                                <input name="due_date" type="date" required min="{{ date('Y-m-d') }}"
                                    value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                                    style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:8px;">
                            @else
                                <div style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:8px;background:#f3f4f6;color:#4b5563;">
                                    {{ $task->due_date ? $task->due_date->format('F j, Y') : 'No due date' }}
                                </div>
                            @endif
                        </div>

                        <div>
                            <label style="display:block;font-weight:bold;margin-bottom:8px;">Due Time</label>

                            @if (auth()->user()->hasRole('superadmin'))
                                <input name="due_time" type="time" required value="{{ $dueTimeValue }}"
                                    style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:8px;">
                            @else
                                <div style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:8px;background:#f3f4f6;color:#4b5563;">
                                    {{ $task->due_time ? \Carbon\Carbon::parse($task->due_time)->format('g:i A') : 'No due time' }}
                                </div>
                            @endif
                        </div>

                        <div>
                            <label style="display:block;font-weight:bold;margin-bottom:8px;">Status</label>
                            <select name="status" required style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:8px;">
                                <option value="not_started" {{ $task->status === 'not_started' ? 'selected' : '' }}>Not Yet Started</option>
                                <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>

                    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:22px;align-items:center;">
                        <button type="submit" style="background:#4f46e5;color:white;padding:12px 18px;border:0;border-radius:8px;font-weight:bold;">
                            Update Task
                        </button>

                        <a href="{{ request('return_to') === 'view' ? route('sub-content.view') : route('sub-content.edit') }}"
                           style="background:#e5e7eb;color:#111827;padding:12px 18px;border-radius:8px;text-decoration:none;font-weight:bold;">
                            Cancel
                        </a>
                    </div>
                </form>

                @if(auth()->user()->hasRole('superadmin') || (! $task->assigned_by && $task->user_id === auth()->id()))
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Move this task to Trash?');" style="margin-top:14px;">
                        @csrf
                        @method('DELETE')

                        <button type="submit" title="Move to Trash"
                            style="background:#fee2e2;color:#991b1b;width:44px;height:44px;border:1px solid #fecaca;border-radius:10px;font-size:20px;font-weight:bold;cursor:pointer;">
                            🗑️
                        </button>
                    </form>
                @endif
            </div>
        @else
            <div style="background:white;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);overflow:hidden;">
                @forelse ($tasks as $task)
                    @php
                        $style = $statusStyles[$task->status] ?? $statusStyles['not_started'];
                        $dueDate = $task->due_date ? $task->due_date->format('F j, Y') : 'No due date';
                        $dueTime = $task->due_time ? \Carbon\Carbon::parse($task->due_time)->format('g:i A') : null;
                    @endphp

                    <div style="padding:20px;border-bottom:1px solid #e5e7eb;border-left:6px solid {{ $style['border'] }};display:grid;grid-template-columns:1fr auto;gap:18px;align-items:center;">
                        <div style="min-width:0;">
                            <h3 style="font-size:20px;margin:0 0 8px;color:#111827;">{{ $task->title }}</h3>

                            <p style="margin:0 0 10px;color:#4b5563;overflow-wrap:anywhere;word-break:break-word;line-height:1.5;">
                                {{ $task->description ?: 'No description provided.' }}
                            </p>

                            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;color:#6b7280;font-size:14px;">
                                <span style="display:inline-block;padding:6px 10px;border-radius:999px;font-size:13px;font-weight:bold;{{ $style['badge'] }}">
                                    {{ $statusLabels[$task->status] ?? $task->status }}
                                </span>

                                <span>Due: {{ $dueDate }}@if($dueTime) at {{ $dueTime }}@endif</span>
                                <span>Last updated: {{ $task->updated_at ? $task->updated_at->format('F j, Y g:i A') : 'N/A' }}</span>
                            </div>
                        </div>

                        <div style="display:flex;gap:10px;align-items:center;justify-content:flex-end;white-space:nowrap;">
                            <a href="{{ route('sub-content.edit') }}?task={{ $task->id }}"
                                style="background:#4f46e5;color:white;padding:10px 14px;border-radius:8px;text-decoration:none;font-weight:bold;">
                                Edit
                            </a>

                            @if(auth()->user()->hasRole('superadmin') || (! $task->assigned_by && $task->user_id === auth()->id()))
                                <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Move this task to Trash?');" style="margin:0;">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" title="Move to Trash"
                                        style="background:#fee2e2;color:#991b1b;width:42px;height:42px;border:1px solid #fecaca;border-radius:8px;font-size:18px;font-weight:bold;cursor:pointer;">
                                        🗑️
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="padding:30px;color:#6b7280;">No tasks available to edit.</div>
                @endforelse
            </div>
        @endif
    </div>
</div>
