@php
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (! auth()->user()?->can('delete sub-content')) {
        abort(403, 'You do not have permission to view trash.');
    }

    $query = \App\Models\Todo::onlyTrashed();

    if (! auth()->user()->hasRole('superadmin')) {
        $query->where('user_id', auth()->id());
    }

    $deletedTasks = $query->latest('deleted_at')->get();

    activity()
        ->causedBy(auth()->user())
        ->log('User opened Trash page');

    $statusLabels = [
        'not_started' => 'Not Yet Started',
        'pending' => 'Pending',
        'completed' => 'Completed',
    ];
@endphp

<div style="font-family:Arial,sans-serif;background:#f3f4f6;min-height:100vh;padding:30px;">
    <div style="max-width:1000px;margin:auto;">

        @include('components.pages.main-content.sub-content._task-nav')

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:25px;">
            <div>
                <h1 style="font-size:32px;margin:0;color:#111827;">Trash</h1>
                <p style="color:#6b7280;margin-top:8px;">
                    Review deleted tasks and restore them if they were removed by mistake.
                </p>
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

        <div style="background:#fff7ed;border:1px solid #fed7aa;padding:16px;border-radius:12px;margin-bottom:20px;">
            <strong style="color:#9a3412;">Trash:</strong>
            <span style="color:#7c2d12;">
                Deleted tasks are hidden from your active task list. Click a task card to view details, or restore it directly.
            </span>
        </div>

        <div style="display:grid;gap:16px;">
            @forelse ($deletedTasks as $task)
                @php
                    $dueDate = $task->due_date ? $task->due_date->format('F j, Y') : 'No due date';
                    $dueTime = $task->due_time ? \Carbon\Carbon::parse($task->due_time)->format('g:i A') : null;
                    $status = $statusLabels[$task->status] ?? $task->status;
                @endphp

                <div style="background:white;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);border-left:6px solid #dc2626;display:grid;grid-template-columns:1fr auto;gap:18px;align-items:center;padding:22px;">

                    <button type="button"
                        onclick="document.getElementById('task-modal-{{ $task->id }}').showModal()"
                        style="text-align:left;background:transparent;border:0;padding:0;cursor:pointer;width:100%;font-family:Arial,sans-serif;">
                        <h3 style="font-size:20px;margin:0 0 8px;color:#111827;">
                            {{ $task->title }}
                        </h3>

                        <p style="margin:0 0 10px;color:#4b5563;overflow-wrap:anywhere;word-break:break-word;line-height:1.5;max-width:720px;">
                            {{ \Illuminate\Support\Str::limit($task->description ?: 'No description provided.', 120) }}
                        </p>

                        <p style="margin:0;color:#6b7280;font-size:14px;">
                            {{ $status }} • Deleted: {{ $task->deleted_at ? $task->deleted_at->format('F j, Y g:i A') : 'N/A' }}
                        </p>
                    </button>

                    <form method="POST" action="{{ route('tasks.restore', $task->id) }}" style="margin:0;">
                        @csrf
                        @method('PATCH')

                        <button type="submit"
                            style="background:#16a34a;color:white;padding:12px 16px;border:0;border-radius:8px;font-weight:bold;white-space:nowrap;">
                            Restore
                        </button>
                    </form>
                </div>

                <dialog id="task-modal-{{ $task->id }}"
                    style="border:0;border-radius:16px;padding:0;width:min(600px,92vw);box-shadow:0 20px 60px rgba(0,0,0,.35);">
                    <div style="padding:24px;background:white;font-family:Arial,sans-serif;">
                        <div style="display:flex;justify-content:space-between;gap:16px;align-items:start;margin-bottom:18px;">
                            <div>
                                <h2 style="margin:0;color:#111827;font-size:26px;">{{ $task->title }}</h2>
                                <p style="margin:8px 0 0;color:#6b7280;">Deleted task details</p>
                            </div>

                            <button type="button"
                                onclick="document.getElementById('task-modal-{{ $task->id }}').close()"
                                style="background:#e5e7eb;border:0;border-radius:8px;padding:8px 12px;font-weight:bold;">
                                Close
                            </button>
                        </div>

                        <div style="display:grid;gap:14px;">
                            <div>
                                <strong>Description</strong>
                                <p style="margin:8px 0 0;color:#4b5563;overflow-wrap:anywhere;word-break:break-word;line-height:1.5;">
                                    {{ $task->description ?: 'No description provided.' }}
                                </p>
                            </div>

                            <div>
                                <strong>Status</strong>
                                <p style="margin:8px 0 0;color:#4b5563;">{{ $status }}</p>
                            </div>

                            <div>
                                <strong>Due</strong>
                                <p style="margin:8px 0 0;color:#4b5563;">
                                    {{ $dueDate }}@if($dueTime) at {{ $dueTime }}@endif
                                </p>
                            </div>

                            <div>
                                <strong>Deleted</strong>
                                <p style="margin:8px 0 0;color:#4b5563;">
                                    {{ $task->deleted_at ? $task->deleted_at->format('F j, Y g:i A') : 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:24px;">
                            <form method="POST" action="{{ route('tasks.restore', $task->id) }}">
                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                    style="background:#16a34a;color:white;padding:12px 16px;border:0;border-radius:8px;font-weight:bold;">
                                    Restore Task
                                </button>
                            </form>

                            <button type="button"
                                onclick="document.getElementById('task-modal-{{ $task->id }}').close()"
                                style="background:#e5e7eb;color:#111827;padding:12px 16px;border:0;border-radius:8px;font-weight:bold;">
                                Cancel
                            </button>
                        </div>
                    </div>
                </dialog>
            @empty
                <div style="background:white;padding:30px;border-radius:12px;color:#6b7280;box-shadow:0 1px 4px rgba(0,0,0,.08);">
                    Trash is empty. Deleted tasks will appear here.
                </div>
            @endforelse
        </div>

    </div>
</div>
