<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Manager</title>
</head>
<body style="margin:0; font-family:Arial, sans-serif; background:#0f172a; color:white;">
    <div style="min-height:100vh; display:flex; align-items:center; justify-content:center; padding:30px;">
        <div style="max-width:1000px; width:100%;">

            <nav style="display:flex; justify-content:space-between; align-items:center; margin-bottom:70px;">
                <h2 style="margin:0; font-size:24px;">Task Manager</h2>

                <div style="display:flex; gap:14px; align-items:center;">
                    @auth
                        <a href="{{ route('dashboard') }}" style="color:white; text-decoration:none;">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" style="color:white; text-decoration:none;">Login</a>
                        <a href="{{ route('register') }}" style="background:#4f46e5; color:white; padding:10px 16px; border-radius:8px; text-decoration:none; font-weight:bold;">Register</a>
                    @endauth
                </div>
            </nav>

            <section style="text-align:center;">
                <div style="display:inline-block; background:#1e293b; color:#93c5fd; padding:8px 14px; border-radius:999px; font-size:14px; margin-bottom:22px;">
                    Organize tasks. Track progress. Stay productive.
                </div>

                <h1 style="font-size:52px; line-height:1.1; margin:0 0 20px;">
                    Manage your tasks with clarity and control.
                </h1>

                <p style="font-size:18px; color:#cbd5e1; max-width:700px; margin:0 auto 32px;">
                    A simple task management app for creating, viewing, updating, and tracking tasks with secure user access and activity monitoring.
                </p>

                <div style="display:flex; justify-content:center; gap:14px; flex-wrap:wrap;">
                    @auth
                        <a href="{{ route('dashboard') }}" style="background:#4f46e5; color:white; padding:14px 22px; border-radius:10px; text-decoration:none; font-weight:bold;">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" style="background:#4f46e5; color:white; padding:14px 22px; border-radius:10px; text-decoration:none; font-weight:bold;">
                            Login
                        </a>
                        <a href="{{ route('register') }}" style="background:#1e293b; color:white; padding:14px 22px; border-radius:10px; text-decoration:none; font-weight:bold; border:1px solid #334155;">
                            Create Account
                        </a>
                    @endauth
                </div>
            </section>

            <section style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:18px; margin-top:80px;">
                <div style="background:#1e293b; padding:24px; border-radius:14px; border:1px solid #334155;">
                    <h3 style="margin-top:0;">Task Tracking</h3>
                    <p style="color:#cbd5e1;">Create and organize tasks with clear status and action pages.</p>
                </div>

                <div style="background:#1e293b; padding:24px; border-radius:14px; border:1px solid #334155;">
                    <h3 style="margin-top:0;">Role-Based Access</h3>
                    <p style="color:#cbd5e1;">User access is controlled based on assigned roles and permissions.</p>
                </div>

                <div style="background:#1e293b; padding:24px; border-radius:14px; border:1px solid #334155;">
                    <h3 style="margin-top:0;">Activity Logs</h3>
                    <p style="color:#cbd5e1;">Important user actions are recorded for monitoring and accountability.</p>
                </div>
            </section>

        </div>
    </div>
</body>
</html>
