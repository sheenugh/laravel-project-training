<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

@php
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (auth()->check()) {
        activity()
            ->causedBy(auth()->user())
            ->log('User visited Sub Content page');
    }
@endphp

<div>
    <h1>Sub Content Index</h1>

    <p>This is the main fullpage Livewire componentHI.</p>

    <nav>
        @can('create sub-content')
            <a href="{{ route('sub-content.create') }}" wire:navigate>Create</a>
        @endcan

        @can('view sub-content')
            | <a href="{{ route('sub-content.view') }}" wire:navigate>View</a>
        @endcan

        @can('edit sub-content')
            | <a href="{{ route('sub-content.edit') }}" wire:navigate>Edit</a>
        @endcan

        @can('delete sub-content')
            | <a href="{{ route('sub-content.delete') }}" wire:navigate>Delete</a>
        @endcan

        @can('view activity logs')
            | <a href="{{ route('activity-logs.index') }}" wire:navigate>Activity Logs</a>
        @endcan

    </nav>

    <hr>

    <p>Livewire is working if this page loads through a fullpage component.</p>
</div>
