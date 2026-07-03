@php
    $active = 'background:#4f46e5;color:white;padding:10px 14px;border-radius:8px;text-decoration:none;font-weight:bold;';
    $idle = 'background:white;color:#111827;padding:10px 14px;border-radius:8px;text-decoration:none;font-weight:bold;border:1px solid #d1d5db;';
    $dangerIdle = 'background:white;color:#991b1b;padding:10px 14px;border-radius:8px;text-decoration:none;font-weight:bold;border:1px solid #fecaca;';
@endphp

<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:22px;">
    @can('create sub-content')
        <a href="{{ route('sub-content.create') }}" style="{{ request()->routeIs('sub-content.create') ? $active : $idle }}">Create</a>
    @endcan

    @can('view sub-content')
        <a href="{{ route('sub-content.view') }}" style="{{ request()->routeIs('sub-content.view') ? $active : $idle }}">View</a>
    @endcan

    @can('edit sub-content')
        <a href="{{ route('sub-content.edit') }}" style="{{ request()->routeIs('sub-content.edit') ? $active : $idle }}">Edit</a>
    @endcan

    @can('delete sub-content')
        <a href="{{ route('sub-content.delete') }}" style="{{ request()->routeIs('sub-content.delete') ? $active : $dangerIdle }}">Trash</a>
    @endcan

    <a href="{{ route('dashboard') }}" style="background:#e5e7eb;color:#111827;padding:10px 14px;border-radius:8px;text-decoration:none;font-weight:bold;">Dashboard</a>
</div>
