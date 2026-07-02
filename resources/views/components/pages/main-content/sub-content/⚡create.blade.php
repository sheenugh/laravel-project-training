@php
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(1);

    if (! auth()->user()?->can('create sub-content')) {
        abort(403, 'You do not have permission to create tasks.');
    }

    activity()
        ->causedBy(auth()->user())
        ->log('User opened Create Task page');
@endphp

<div style="font-family: Arial, sans-serif; background:#f3f4f6; min-height:100vh; padding:30px;">
    <div style="max-width:900px; margin:auto;">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
            <div>
                <h1 style="font-size:32px; margin:0; color:#111827;">Create Task</h1>
                <p style="color:#6b7280; margin-top:8px;">
                    Add a new task and organize your work clearly.
                </p>
            </div>

            <a href="{{ route('sub-content.index') }}" style="color:#4f46e5; font-weight:bold; text-decoration:none;">
                Back to Tasks
            </a>
        </div>

        <div style="background:white; padding:28px; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.08);">
            <form>
                <div style="margin-bottom:18px;">
                    <label style="display:block; font-weight:bold; margin-bottom:8px; color:#111827;">Task Title</label>
                    <input type="text" placeholder="Example: Finish OJT report"
                        style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:8px;">
                </div>

                <div style="margin-bottom:18px;">
                    <label style="display:block; font-weight:bold; margin-bottom:8px; color:#111827;">Description</label>
                    <textarea placeholder="Write task details here..."
                        style="width:100%; min-height:120px; padding:12px; border:1px solid #d1d5db; border-radius:8px;"></textarea>
                </div>

                <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px; margin-bottom:18px;">
                    <div>
                        <label style="display:block; font-weight:bold; margin-bottom:8px; color:#111827;">Due Date</label>
                        <input type="date"
                            style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:8px;">
                    </div>

                    <div>
                        <label style="display:block; font-weight:bold; margin-bottom:8px; color:#111827;">Status</label>
                        <select style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:8px;">
                            <option>Not Yet Started</option>
                            <option>Pending</option>
                            <option>Completed</option>
                        </select>
                    </div>
                </div>

                <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:22px;">
                    <button type="button"
                        style="background:#4f46e5; color:white; padding:12px 18px; border:0; border-radius:8px; font-weight:bold;">
                        Save Task
                    </button>

                    <a href="{{ route('sub-content.index') }}"
                        style="background:#e5e7eb; color:#111827; padding:12px 18px; border-radius:8px; text-decoration:none; font-weight:bold;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>
