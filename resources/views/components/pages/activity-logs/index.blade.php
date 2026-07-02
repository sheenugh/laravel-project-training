@php
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (! auth()->user()?->can('view activity logs')) {
        abort(403, 'You do not have permission to view activity logs.');
    }

    $activities = \Spatie\Activitylog\Models\Activity::latest()->take(10)->get();
@endphp

<div>
    <h1>Activity Logs</h1>

    <p>This page shows the latest user activities in the application.</p>

    <hr>

    @forelse ($activities as $activity)
        <div style="margin-bottom: 15px;">
            <strong>{{ $activity->description }}</strong><br>
            Log Name: {{ $activity->log_name }}<br>
            Causer ID: {{ $activity->causer_id ?? 'N/A' }}<br>
            Date: {{ $activity->created_at }}
        </div>
    @empty
        <p>No activity logs found.</p>
    @endforelse

    <hr>

    <a href="{{ route('sub-content.index') }}">Back to Sub Content</a>
</div>
