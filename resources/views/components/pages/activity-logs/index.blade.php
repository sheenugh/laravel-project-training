@php
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (! auth()->user()?->can('view activity logs')) {
        abort(403, 'You do not have permission to view activity logs.');
    }

    $activities = \Spatie\Activitylog\Models\Activity::latest()->take(10)->get();
@endphp

<div style="font-family: Arial, sans-serif; background:#f3f4f6; min-height:100vh; padding:30px;">
    <div style="max-width:1100px; margin:auto;">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
            <div>
                <h1 style="font-size:32px; margin:0; color:#111827;">Activity Logs</h1>
                <p style="color:#6b7280; margin-top:8px;">
                    Latest recorded user actions in the application.
                </p>
            </div>

            <a href="{{ route('sub-content.index') }}" style="color:#4f46e5; font-weight:bold; text-decoration:none;">
                Back to Task Module
            </a>
        </div>

        <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.08);">
            @forelse ($activities as $activity)
                <div style="padding:16px; border-bottom:1px solid #e5e7eb;">
                    <div style="font-size:18px; font-weight:bold; color:#111827;">
                        {{ $activity->description }}
                    </div>

                    <div style="margin-top:8px; color:#4b5563; font-size:14px;">
                        <strong>Log Name:</strong> {{ $activity->log_name }} <br>
                        <strong>Causer ID:</strong> {{ $activity->causer_id ?? 'N/A' }} <br>
                        <strong>Date:</strong> {{ $activity->created_at->format('F j, Y g:i A') }}
                    </div>
                </div>
            @empty
                <p style="color:#6b7280;">No activity logs found.</p>
            @endforelse
        </div>

    </div>
</div>
