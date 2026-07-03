@php
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (! auth()->user()?->can('view sub-content')) {
        abort(403, 'You do not have permission to view tasks.');
    }

    activity()
        ->causedBy(auth()->user())
        ->log('User opened View Tasks page');

    $query = \App\Models\Todo::query();

    if (! auth()->user()->hasRole('superadmin')) {
        $query->where('user_id', auth()->id());
    }

    $tasks = $query->orderBy('created_at', 'desc')->get();

    $sections = [
        'not_started' => [
            'title' => 'Not Yet Started',
            'description' => 'Tasks that are planned but not yet started.',
            'border' => '#f59e0b',
            'badge' => 'background:#fef3c7;color:#92400e;',
        ],
        'pending' => [
            'title' => 'Pending',
            'description' => 'Tasks that are currently ongoing or waiting for completion.',
            'border' => '#4f46e5',
            'badge' => 'background:#e0e7ff;color:#3730a3;',
        ],
        'completed' => [
            'title' => 'Completed',
            'description' => 'Tasks that have already been finished.',
            'border' => '#16a34a',
            'badge' => 'background:#dcfce7;color:#166534;',
        ],
    ];
@endphp

<div style="font-family:Arial,sans-serif;background:#f3f4f6;min-height:100vh;padding:30px;">
    <div style="max-width:1100px;margin:auto;">

        @include('components.pages.main-content.sub-content._task-nav')

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:25px;">
            <div>
                <h1 style="font-size:32px;margin:0;color:#111827;">View Tasks</h1>
                <p style="color:#6b7280;margin-top:8px;">
                    Review your saved tasks, deadlines, and current progress.
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

        <div style="background:white;padding:20px;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);margin-bottom:20px;">
            <input id="taskSearch" type="text" placeholder="Search tasks by title, description, or status..."
                style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:8px;">
        </div>

        <div id="taskList" style="display:grid;gap:22px;">
            @foreach ($sections as $status => $section)
                @php
                    $sectionTasks = $tasks->where('status', $status);
                @endphp

                <section class="task-section" style="background:white;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);overflow:hidden;">
                    <div style="padding:18px 20px;border-left:6px solid {{ $section['border'] }};border-bottom:1px solid #e5e7eb;">
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
                            <div>
                                <h2 style="margin:0;color:#111827;font-size:22px;">{{ $section['title'] }}</h2>
                                <p style="margin:6px 0 0;color:#6b7280;">{{ $section['description'] }}</p>
                            </div>

                            <span style="display:inline-block;padding:6px 10px;border-radius:999px;font-size:13px;font-weight:bold;{{ $section['badge'] }}">
                                {{ $sectionTasks->count() }} task{{ $sectionTasks->count() === 1 ? '' : 's' }}
                            </span>
                        </div>
                    </div>

                    @forelse ($sectionTasks as $task)
                        @php
                            $dueDate = $task->due_date ? $task->due_date->format('F j, Y') : 'No due date';
                            $dueTime = $task->due_time ? \Carbon\Carbon::parse($task->due_time)->format('g:i A') : null;
@endphp

                        <div class="task-card"
                             data-search="{{ strtolower($task->title . ' ' . ($task->description ?? '') . ' ' . $section['title']) }}"
                             style="padding:20px;border-bottom:1px solid #e5e7eb;">
                            <div style="display:flex;justify-content:space-between;gap:16px;flex-wrap:wrap;">
                                <div style="max-width:700px;">
                                    <h3 style="font-size:20px;margin:0 0 8px;color:#111827;">
                                        {{ $task->title }}
                                    </h3>

                                    <p style="margin:0 0 10px;color:#4b5563;overflow-wrap:anywhere;word-break:break-word;line-height:1.5;">
                                        {{ $task->description ?: 'No description provided.' }}
                                    </p>

                                    <p style="margin:0;color:#6b7280;font-size:14px;">
                                        Assigned to: {{ $task->user?->name ?? 'Unknown User' }} • Due: {{ $dueDate }}@if($dueTime) at {{ $dueTime }}@endif
                                    </p>
                                </div>

                                @can('edit sub-content')
                                    <a href="{{ route('sub-content.edit') }}?task={{ $task->id }}&return_to=view"
                                       style="align-self:center;background:#4f46e5;color:white;padding:10px 14px;border-radius:8px;text-decoration:none;font-weight:bold;">
                                        Edit
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @empty
                        <div class="empty-section" style="padding:20px;color:#9ca3af;">
                            No {{ strtolower($section['title']) }} tasks.
                        </div>
                    @endforelse
                </section>
            @endforeach

            <div id="noSearchResults" style="display:none;background:white;padding:30px;border-radius:12px;color:#6b7280;box-shadow:0 1px 4px rgba(0,0,0,.08);">
                No matching tasks found.
            </div>
        </div>

    </div>
</div>

<script>
    const searchInput = document.getElementById('taskSearch');
    const taskCards = document.querySelectorAll('.task-card');
    const sections = document.querySelectorAll('.task-section');
    const noResults = document.getElementById('noSearchResults');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const searchValue = this.value.toLowerCase().trim();
            let visibleCount = 0;

            taskCards.forEach(function (card) {
                const text = card.dataset.search || '';
                const isMatch = text.includes(searchValue);

                card.style.display = isMatch ? 'block' : 'none';

                if (isMatch) visibleCount++;
            });

            sections.forEach(function (section) {
                const visibleCards = section.querySelectorAll('.task-card[style*="display: block"], .task-card:not([style*="display: none"])');
                const hasVisible = Array.from(section.querySelectorAll('.task-card')).some(card => card.style.display !== 'none');
                section.style.display = hasVisible || searchValue === '' ? 'block' : 'none';
            });

            if (noResults) {
                noResults.style.display = visibleCount === 0 && taskCards.length > 0 ? 'block' : 'none';
            }
        });
    }
</script>
