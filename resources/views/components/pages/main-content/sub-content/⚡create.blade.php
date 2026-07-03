@php
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (! auth()->user()?->can('create sub-content')) {
        abort(403, 'You do not have permission to create tasks.');
    }

    $users = \App\Models\User::orderBy('name')->get();

    activity()
        ->causedBy(auth()->user())
        ->log('User opened Create Task page');
@endphp

<div style="font-family:Arial,sans-serif;background:#f3f4f6;min-height:100vh;padding:30px;">
    <div style="max-width:1000px;margin:auto;">

        @include('components.pages.main-content.sub-content._task-nav')

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:25px;">
            <div>
                <h1 style="font-size:32px;margin:0;color:#111827;">Create Task</h1>
                <p style="color:#6b7280;margin-top:8px;">Add a new task and assign it to the right user.</p>
            </div>

            <a href="{{ route('sub-content.index') }}" style="color:#4f46e5;font-weight:bold;text-decoration:none;">
                Back to Tasks
            </a>
        </div>

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

        <div style="background:white;padding:28px;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);">
            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf

                <div style="margin-bottom:18px;">
                    <label style="display:block;font-weight:bold;margin-bottom:8px;">Task Title</label>
                    <input name="title" type="text" required value="{{ old('title') }}" placeholder="Example: Finish OJT report"
                        style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:8px;">
                </div>

                <div style="margin-bottom:18px;">
                    <label style="display:block;font-weight:bold;margin-bottom:8px;">Description</label>
                    <textarea name="description" placeholder="Write task details here..."
                        style="width:100%;min-height:110px;padding:12px;border:1px solid #d1d5db;border-radius:8px;">{{ old('description') }}</textarea>
                </div>

                @if (auth()->user()->hasRole('superadmin'))
                    <div style="margin-bottom:18px;">
                        <label style="display:block;font-weight:bold;margin-bottom:8px;">Assign To</label>
                        <select name="assigned_user_id" required
                            style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:8px;">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_user_id', auth()->id()) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} — {{ $user->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div style="margin-bottom:18px;background:#eef2ff;color:#3730a3;padding:14px;border-radius:10px;">
                        Assigned to: <strong>{{ auth()->user()->name }}</strong>
                    </div>
                @endif

                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:18px;">
                    <div>
                        <label style="display:block;font-weight:bold;margin-bottom:8px;">Due Date</label>
                        <input name="due_date" type="date" required min="{{ date('Y-m-d') }}" value="{{ old('due_date') }}"
                            style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:8px;">
                    </div>

                    <div>
                        <label style="display:block;font-weight:bold;margin-bottom:8px;">Due Time</label>
                        <input name="due_time" type="time" required value="{{ old('due_time') }}"
                            style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:8px;">
                    </div>

                    <div>
                        <label style="display:block;font-weight:bold;margin-bottom:8px;">Status</label>
                        <select name="status" required
                            style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:8px;">
                            <option value="not_started" {{ old('status') === 'not_started' ? 'selected' : '' }}>Not Yet Started</option>
                            <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>

                <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:22px;">
                    <button type="submit" style="background:#4f46e5;color:white;padding:12px 18px;border:0;border-radius:8px;font-weight:bold;">
                        Save Task
                    </button>

                    <a href="{{ route('sub-content.index') }}"
                        style="background:#e5e7eb;color:#111827;padding:12px 18px;border-radius:8px;text-decoration:none;font-weight:bold;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>
