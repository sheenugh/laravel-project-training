@php
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (! auth()->user()?->can('view activity logs')) {
        abort(403, 'You do not have permission to view activity logs.');
    }

    $activities = \Spatie\Activitylog\Models\Activity::with('causer')
        ->latest()
        ->take(50)
        ->get();

    $totalLogs = \Spatie\Activitylog\Models\Activity::count();

    $pageVisits = \Spatie\Activitylog\Models\Activity::where('description', 'like', '%visited%')
        ->orWhere('description', 'like', '%opened%')
        ->count();

    $taskActions = \Spatie\Activitylog\Models\Activity::where('description', 'like', '%task%')
        ->count();

    $latestActivity = \Spatie\Activitylog\Models\Activity::latest()->first();

    function activityTypeLabel($description) {
        $lower = strtolower($description);

        if (str_contains($lower, 'created')) {
            return ['label' => 'Created', 'style' => 'background:#dcfce7;color:#166534;'];
        }

        if (str_contains($lower, 'updated')) {
            return ['label' => 'Updated', 'style' => 'background:#e0e7ff;color:#3730a3;'];
        }

        if (str_contains($lower, 'deleted') || str_contains($lower, 'trash')) {
            return ['label' => 'Trash', 'style' => 'background:#fee2e2;color:#991b1b;'];
        }

        if (str_contains($lower, 'restored')) {
            return ['label' => 'Restored', 'style' => 'background:#d1fae5;color:#065f46;'];
        }

        if (str_contains($lower, 'visited') || str_contains($lower, 'opened')) {
            return ['label' => 'Page Visit', 'style' => 'background:#fef3c7;color:#92400e;'];
        }

        return ['label' => 'Activity', 'style' => 'background:#f3f4f6;color:#374151;'];
    }
@endphp

<div style="font-family:Arial,sans-serif;background:linear-gradient(180deg,#eef2ff 0%,#f8fafc 55%,#eef2ff 100%);min-height:100vh;padding:30px;">
    <div style="max-width:1180px;margin:auto;">

        <div style="display:flex;justify-content:space-between;align-items:center;gap:18px;flex-wrap:wrap;margin-bottom:26px;">
            <div>
                <h1 style="font-size:36px;margin:0;color:#111827;">Activity Logs</h1>
                <p style="color:#6b7280;margin-top:8px;">
                    Monitor user actions, task changes, and system activity.
                </p>
            </div>

            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <a href="{{ route('dashboard') }}"
                   style="background:white;color:#111827;padding:12px 16px;border-radius:10px;text-decoration:none;font-weight:bold;border:1px solid #d1d5db;">
                    Dashboard
                </a>

                <a href="{{ route('sub-content.index') }}"
                   style="background:#4f46e5;color:white;padding:12px 16px;border-radius:10px;text-decoration:none;font-weight:bold;">
                    Task Workspace
                </a>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:16px;margin-bottom:24px;">
            <div style="background:white;padding:20px;border-radius:16px;box-shadow:0 8px 20px rgba(15,23,42,.08);border-top:5px solid #111827;">
                <div style="color:#6b7280;font-weight:bold;">Total Logs</div>
                <div style="font-size:32px;font-weight:bold;color:#111827;margin-top:8px;">{{ $totalLogs }}</div>
            </div>

            <div style="background:#fffbeb;padding:20px;border-radius:16px;box-shadow:0 8px 20px rgba(15,23,42,.08);border-top:5px solid #f59e0b;">
                <div style="color:#92400e;font-weight:bold;">Page Visits</div>
                <div style="font-size:32px;font-weight:bold;color:#92400e;margin-top:8px;">{{ $pageVisits }}</div>
            </div>

            <div style="background:#eef2ff;padding:20px;border-radius:16px;box-shadow:0 8px 20px rgba(15,23,42,.08);border-top:5px solid #4f46e5;">
                <div style="color:#3730a3;font-weight:bold;">Task Actions</div>
                <div style="font-size:32px;font-weight:bold;color:#3730a3;margin-top:8px;">{{ $taskActions }}</div>
            </div>

            <div style="background:#ecfdf5;padding:20px;border-radius:16px;box-shadow:0 8px 20px rgba(15,23,42,.08);border-top:5px solid #16a34a;">
                <div style="color:#166534;font-weight:bold;">Latest Activity</div>
                <div style="font-size:14px;font-weight:bold;color:#166534;margin-top:10px;">
                    {{ $latestActivity ? $latestActivity->created_at->format('M j, Y g:i A') : 'No logs yet' }}
                </div>
            </div>
        </div>

        <div style="background:white;padding:20px;border-radius:16px;box-shadow:0 8px 20px rgba(15,23,42,.08);margin-bottom:22px;">
            <input id="activitySearch" type="text" placeholder="Search logs by action, user, or date..."
                style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:10px;">
        </div>

        <div style="background:white;border-radius:18px;box-shadow:0 8px 22px rgba(15,23,42,.08);overflow:hidden;">
            <div style="padding:20px;border-bottom:1px solid #e5e7eb;">
                <h2 style="margin:0;color:#111827;font-size:24px;">Recent Activity</h2>
                <p style="margin:6px 0 0;color:#6b7280;">
                    Showing latest 50 recorded actions.
                </p>
            </div>

            <div id="activityList">
                @forelse ($activities as $activity)
                    @php
                        $type = activityTypeLabel($activity->description);
                        $causerName = $activity->causer?->name ?? 'Unknown User';
                        $causerEmail = $activity->causer?->email ?? 'No email available';
                    @endphp

                    <div class="activity-card"
                         data-search="{{ strtolower($activity->description . ' ' . $causerName . ' ' . $causerEmail . ' ' . $activity->created_at->format('M j Y g:i A')) }}"
                         style="padding:20px;border-bottom:1px solid #e5e7eb;">
                        <div style="display:flex;justify-content:space-between;gap:18px;align-items:start;flex-wrap:wrap;">
                            <div style="min-width:0;max-width:760px;">
                                <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:8px;">
                                    <span style="display:inline-block;padding:6px 10px;border-radius:999px;font-size:13px;font-weight:bold;{{ $type['style'] }}">
                                        {{ $type['label'] }}
                                    </span>

                                    <span style="color:#6b7280;font-size:14px;">
                                        {{ $activity->created_at ? $activity->created_at->format('F j, Y g:i A') : 'N/A' }}
                                    </span>
                                </div>

                                <h3 style="font-size:19px;margin:0 0 8px;color:#111827;">
                                    {{ $activity->description }}
                                </h3>

                                <p style="margin:0;color:#4b5563;font-size:14px;">
                                    By: <strong>{{ $causerName }}</strong>
                                    <span style="color:#9ca3af;">({{ $causerEmail }})</span>
                                </p>
                            </div>

                            <div style="text-align:right;color:#6b7280;font-size:14px;">
                                <div>Log: {{ $activity->log_name }}</div>
                                <div>Causer ID: {{ $activity->causer_id ?? 'N/A' }}</div>
                                @if ($activity->subject_type)
                                    <div>Subject: {{ class_basename($activity->subject_type) }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="padding:30px;color:#6b7280;">
                        No activity logs found.
                    </div>
                @endforelse

                <div id="noActivityResults" style="display:none;padding:30px;color:#6b7280;">
                    No matching activity logs found.
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    const activitySearch = document.getElementById('activitySearch');
    const activityCards = document.querySelectorAll('.activity-card');
    const noActivityResults = document.getElementById('noActivityResults');

    if (activitySearch) {
        activitySearch.addEventListener('input', function () {
            const searchValue = this.value.toLowerCase().trim();
            let visibleCount = 0;

            activityCards.forEach(function (card) {
                const text = card.dataset.search || '';
                const isMatch = text.includes(searchValue);

                card.style.display = isMatch ? 'block' : 'none';

                if (isMatch) {
                    visibleCount++;
                }
            });

            if (noActivityResults) {
                noActivityResults.style.display = visibleCount === 0 && activityCards.length > 0 ? 'block' : 'none';
            }
        });
    }
</script>
