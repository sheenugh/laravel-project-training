<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth'])->group(function () {
    Route::view('/sub-content', 'components.pages.main-content.sub-content.⚡index')->name('sub-content.index');
    Route::view('/sub-content/create', 'components.pages.main-content.sub-content.⚡create')->name('sub-content.create');
    Route::view('/sub-content/view', 'components.pages.main-content.sub-content.⚡view')->name('sub-content.view');
    Route::view('/sub-content/edit', 'components.pages.main-content.sub-content.⚡edit')->name('sub-content.edit');
    Route::view('/sub-content/delete', 'components.pages.main-content.sub-content.⚡delete')->name('sub-content.delete');
    Route::view('/activity-logs', 'components.pages.activity-logs.index')->name('activity-logs.index');
});




Route::post('/tasks', function (\Illuminate\Http\Request $request) {
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (! auth()->user()?->can('create sub-content')) {
        abort(403, 'You do not have permission to create tasks.');
    }

    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'due_date' => ['required', 'date', 'after_or_equal:today'],
        'due_time' => ['required', 'date_format:H:i'],
        'status' => ['required', 'in:not_started,pending,completed'],
        'assigned_user_id' => ['nullable', 'exists:users,id'],
    ]);

    $isAdmin = auth()->user()->hasRole('superadmin');

    $assignedUserId = $isAdmin
        ? ($validated['assigned_user_id'] ?? auth()->id())
        : auth()->id();

    $todo = \App\Models\Todo::create([
        'user_id' => $assignedUserId,
        'created_by' => auth()->id(),
        'assigned_by' => $isAdmin && $assignedUserId != auth()->id() ? auth()->id() : null,
        'title' => $validated['title'],
        'description' => $validated['description'] ?? null,
        'due_date' => $validated['due_date'],
        'due_time' => $validated['due_time'],
        'status' => $validated['status'],
    ]);

    $assignedUser = \App\Models\User::find($assignedUserId);

    activity()
        ->causedBy(auth()->user())
        ->performedOn($todo)
        ->log('Task "' . $todo->title . '" created for ' . ($assignedUser?->name ?? 'Unknown User'));

    return redirect()->route('sub-content.view')->with('success', 'Task "' . $todo->title . '" created successfully.');
})->middleware(['auth'])->name('tasks.store');






Route::patch('/tasks/{todo}', function (\Illuminate\Http\Request $request, \App\Models\Todo $todo) {
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (! auth()->user()?->can('edit sub-content')) {
        abort(403, 'You do not have permission to edit tasks.');
    }

    $isAdmin = auth()->user()->hasRole('superadmin');

    if (! $isAdmin && $todo->user_id !== auth()->id()) {
        abort(403, 'You can only edit tasks assigned to you.');
    }

    if ($isAdmin) {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'due_time' => ['required', 'date_format:H:i'],
            'status' => ['required', 'in:not_started,pending,completed'],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
            'return_to' => ['nullable', 'string'],
        ]);

        $assignedUserId = $validated['assigned_user_id'] ?? $todo->user_id;

        $todo->update([
            'user_id' => $assignedUserId,
            'assigned_by' => $assignedUserId != $todo->created_by ? auth()->id() : $todo->assigned_by,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'],
            'due_time' => $validated['due_time'],
            'status' => $validated['status'],
        ]);
    } else {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:not_started,pending,completed'],
            'return_to' => ['nullable', 'string'],
        ]);

        $todo->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ]);
    }

    activity()
        ->causedBy(auth()->user())
        ->performedOn($todo)
        ->log('Task "' . $todo->title . '" updated');

    $message = 'Task "' . $todo->title . '" updated successfully.';

    if ($request->input('return_to') === 'view') {
        return redirect()->route('sub-content.view')->with('success', $message);
    }

    return redirect()->route('sub-content.edit')->with('success', $message);
})->middleware(['auth'])->name('tasks.update');





Route::delete('/tasks/{todo}', function (\App\Models\Todo $todo) {
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (! auth()->user()?->can('delete sub-content')) {
        abort(403, 'You do not have permission to delete tasks.');
    }

    $isAdmin = auth()->user()->hasRole('superadmin');

    if (! $isAdmin) {
        if ($todo->user_id !== auth()->id()) {
            abort(403, 'You can only delete tasks assigned to you.');
        }

        if ($todo->assigned_by && $todo->assigned_by !== auth()->id()) {
            abort(403, 'You cannot delete a task assigned by an admin.');
        }
    }

    $taskTitle = $todo->title;

    activity()
        ->causedBy(auth()->user())
        ->performedOn($todo)
        ->log('Task "' . $taskTitle . '" moved to trash');

    $todo->delete();

    return redirect()->route('sub-content.delete')->with('success', 'Task "' . $taskTitle . '" moved to Trash successfully.');
})->middleware(['auth'])->name('tasks.destroy');



Route::patch('/tasks/{id}/restore', function ($id) {
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (! auth()->user()?->can('delete sub-content')) {
        abort(403, 'You do not have permission to restore tasks.');
    }

    $query = \App\Models\Todo::onlyTrashed()->where('id', $id);

    if (! auth()->user()->hasRole('superadmin')) {
        $query->where('user_id', auth()->id());
    }

    $todo = $query->firstOrFail();
    $taskTitle = $todo->title;

    $todo->restore();

    activity()
        ->causedBy(auth()->user())
        ->performedOn($todo)
        ->log('User restored a task from trash');

    return redirect()->route('sub-content.delete')->with('success', 'Task "' . $taskTitle . '" restored successfully.');
})->middleware(['auth'])->name('tasks.restore');

require __DIR__.'/auth.php';
