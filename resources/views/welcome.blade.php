<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Practice Project</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: auto;
            padding: 40px 0;
        }

        .hero {
            text-align: center;
            padding: 80px 20px;
        }

        .badge {
            display: inline-block;
            background: #1e293b;
            color: #38bdf8;
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .hero p {
            color: #cbd5e1;
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto 30px;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            background: #ef4444;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }

        .card {
            background: #1e293b;
            padding: 25px;
            border-radius: 15px;
            border: 1px solid #334155;
        }

        .card h3 {
            color: #38bdf8;
            margin-bottom: 10px;
        }

        .card p {
            color: #cbd5e1;
            line-height: 1.5;
        }

        footer {
            text-align: center;
            color: #94a3b8;
            margin-top: 60px;
            padding: 20px;
            border-top: 1px solid #334155;
        }
    </style>
</head>
<body>
    <div class="container">
        <section class="hero">
            <span class="badge">Laravel + WSL Setup Complete</span>
            <h1>Welcome to My Laravel Practice Project</h1>
            <p>
                This is a simple Laravel landing page created while learning how to set up
                WSL, Git, GitHub SSH, PHP, Composer, MySQL, and Laravel development workflow.
            </p>
            <a href="#features" class="btn">View Features</a>
        </section>

        <section id="features" class="cards">
            <div class="card">
                <h3>Laravel Project</h3>
                <p>Created using Composer and configured to run locally with Artisan.</p>
            </div>

            <div class="card">
                <h3>Database Ready</h3>
                <p>Connected to MySQL and prepared for migrations and future data storage.</p>
            </div>

            <div class="card">
                <h3>GitHub Workflow</h3>
                <p>Project changes can be committed and pushed to GitHub using Git commands.</p>
            </div>
        </section>

        <footer>
            <p>Created by Sheena Mae Delima | Laravel Training Project</p>
        </footer>
    </div>
</body>
</html>