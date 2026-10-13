```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Class Booking</title>
    <link rel="stylesheet" href="assets/style.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #173568;
            background: #ffffff;
            font-family: Arial, sans-serif;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 20px 6%;
            background: #ffffff;
            box-shadow: 0 3px 15px rgba(30, 90, 180, 0.10);
        }

        .brand {
            color: #1455d9;
            font-size: 23px;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
        }

        .nav-links a {
            color: #29466f;
            text-decoration: none;
            font-weight: bold;
        }

        .nav-links a:hover {
            color: #1764e8;
        }

        .hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            padding: 75px 7%;
            background: linear-gradient(120deg, #edf5ff, #ffffff);
        }

        .hero-content {
            flex: 1;
        }

        .tagline {
            display: inline-block;
            padding: 9px 15px;
            border-radius: 30px;
            color: #1455d9;
            background: #dceaff;
            font-weight: bold;
        }

        .hero h1 {
            margin: 22px 0;
            font-size: clamp(34px, 4.5vw, 55px);
            line-height: 1.15;
            color: #102c60;
        }

        .hero h1 span {
            color: #1764e8;
        }

        .hero p {
            max-width: 560px;
            color: #536b8c;
            font-size: 18px;
            line-height: 1.8;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 15px 22px;
            border: 1px solid #1764e8;
            border-radius: 12px;
            background: #ffffff;
            color: #1455d9;
            text-decoration: none;
            font-weight: bold;
            transition: 0.2s;
        }

        .btn-primary {
            background: #1764e8;
            color: #ffffff;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(23, 100, 232, 0.18);
        }

        .hero-art {
            flex: 1;
            text-align: center;
            font-size: 140px;
            padding: 30px;
            border-radius: 35px;
            background: radial-gradient(circle, #d8e9ff, #edf5ff);
        }

        .features {
            padding: 55px 7%;
            text-align: center;
        }

        .features h2 {
            margin-bottom: 10px;
            font-size: 34px;
        }

        .section-description {
            color: #607593;
            font-size: 17px;
            line-height: 1.6;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 22px;
            margin-top: 35px;
        }

        .feature-card {
            padding: 28px 20px;
            border: 1px solid #dce8fb;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 5px 20px rgba(30, 75, 140, 0.05);
        }

        .feature-icon {
            font-size: 38px;
        }

        .feature-card h3 {
            margin-bottom: 12px;
            font-size: 19px;
        }

        .feature-card p {
            margin: 0;
            color: #607593;
            line-height: 1.7;
        }

        footer {
            padding: 22px;
            text-align: center;
            color: #536b8c;
            background: #edf5ff;
        }

        @media (max-width: 900px) {
            .hero {
                flex-direction: column;
                align-items: stretch;
                padding: 50px 6%;
            }

            .hero-art {
                font-size: 100px;
            }

            .feature-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .navbar {
                flex-direction: column;
            }

            .nav-links {
                justify-content: center;
            }
        }

        @media (max-width: 520px) {
            .feature-grid {
                grid-template-columns: 1fr;
            }

            .nav-links {
                gap: 14px;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .btn {
                text-align: center;
            }

            .hero-art {
                font-size: 75px;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <header class="navbar">
        <a class="brand" href="index.php">🎓 Online Class Booking</a>

        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="student/login.php">Student Login</a>
            <a href="student/register.php">Register</a>
            <a href="admin/login.php">Admin Login</a>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content">
                <span class="tagline">🎓 Learn · Grow · Succeed</span>

                <h1>
                    Online Class <span>Scheduling</span>
                    and <span>Booking System</span>
                </h1>

                <p>
                    Find upcoming online classes, explore subjects, and
                    reserve your place with a few simple steps. Your learning
                    journey starts here!
                </p>

                <div class="hero-buttons">
                    <a class="btn btn-primary" href="student/login.php">
                        Student Login →
                    </a>

                    <a class="btn" href="student/register.php">
                        Create an Account →
                    </a>

                    <a class="btn" href="admin/login.php">
                        Admin Login →
                    </a>
                </div>
            </div>

            <div class="hero-art" aria-label="Books and online learning" role="img">
                👩‍💻📚
            </div>
        </section>

        <section class="features">
            <h2>Why Choose Our System?</h2>

            <p class="section-description">
                We make it easy to discover, book and manage your online classes.
            </p>

            <div class="feature-grid">
                <article class="feature-card">
                    <div class="feature-icon">📅</div>
                    <h3>Easy Booking</h3>
                    <p>Browse available classes and book your preferred class in a few simple steps.</p>
                </article>

                <article class="feature-card">
                    <div class="feature-icon">📖</div>
                    <h3>Explore Subjects</h3>
                    <p>Discover different subjects and find classes that match your interests.</p>
                </article>

                <article class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Secure Access</h3>
                    <p>Use your student account to access your bookings and manage reservations.</p>
                </article>

                <article class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Student & Admin</h3>
                    <p>Students can book classes while administrators can add classes and review bookings.</p>
                </article>
            </div>
        </section>
    </main>

    <footer>
        © 2026 Online Class Scheduling and Booking System. All rights reserved.
    </footer>

</body>
</html>
```