@php
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    $user = auth()->user();
    $roles = $user?->getRoleNames()->implode(', ') ?: 'User';
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Task Manager Dashboard
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Manage tasks, monitor progress, and access your workspace.
            </p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900">
                        Welcome, {{ $user->name }}!
                    </h1>

                    <p class="mt-2 text-gray-600">
                        You are signed in as
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-700">
                            {{ $roles }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <a href="{{ route('sub-content.index') }}"
                   class="block bg-white p-6 rounded-lg shadow-sm border hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-900">Tasks</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        Open your task workspace to create, view, update, or delete tasks based on your access.
                    </p>
                    <div class="mt-4 text-indigo-600 font-semibold">
                        Open Tasks →
                    </div>
                </a>

                @can('view activity logs')
                    <a href="{{ route('activity-logs.index') }}"
                       class="block bg-white p-6 rounded-lg shadow-sm border hover:shadow-md transition">
                        <h3 class="text-lg font-semibold text-gray-900">Activity Logs</h3>
                        <p class="mt-2 text-sm text-gray-600">
                            Review recent user actions and system activity records.
                        </p>
                        <div class="mt-4 text-indigo-600 font-semibold">
                            View Logs →
                        </div>
                    </a>
                @endcan

                <a href="{{ route('profile.edit') }}"
                   class="block bg-white p-6 rounded-lg shadow-sm border hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-900">Profile</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        Update your account information and password.
                    </p>
                    <div class="mt-4 text-indigo-600 font-semibold">
                        Edit Profile →
                    </div>
                </a>

            </div>

        </div>
    </div>
</x-app-layout>
