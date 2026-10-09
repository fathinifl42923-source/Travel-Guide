<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'TravelGuide - Discover Your Next Adventure'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Color Palette - Modern & Vibrant */
            --primary: hsl(200, 98%, 39%);
            --primary-dark: hsl(200, 98%, 32%);
            --primary-light: hsl(200, 98%, 85%);
            --secondary: hsl(340, 82%, 62%);
            --accent: hsl(35, 100%, 60%);
            --success: hsl(142, 71%, 45%);
            --warning: hsl(38, 92%, 50%);
            --danger: hsl(0, 72%, 59%);
            
            /* Neutrals */
            --white: hsl(0, 0%, 100%);
            --gray-50: hsl(210, 20%, 98%);
            --gray-100: hsl(210, 17%, 95%);
            --gray-200: hsl(210, 16%, 88%);
            --gray-300: hsl(210, 14%, 83%);
            --gray-400: hsl(210, 14%, 66%);
            --gray-500: hsl(210, 11%, 51%);
            --gray-600: hsl(210, 12%, 42%);
            --gray-700: hsl(210, 15%, 28%);
            --gray-800: hsl(210, 18%, 20%);
            --gray-900: hsl(210, 20%, 12%);
            
            /* Typography */
            --font-body: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --font-heading: 'Poppins', sans-serif;
            
            /* Spacing Scale */
            --space-xs: 0.25rem;
            --space-sm: 0.5rem;
            --space-md: 1rem;
            --space-lg: 1.5rem;
            --space-xl: 2rem;
            --space-2xl: 3rem;
            --space-3xl: 4rem;
            
            /* Border Radius */
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 20px;
            --radius-full: 9999px;
            
            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.12);
            --shadow-xl: 0 20px 50px rgba(0, 0, 0, 0.15);
            
            /* Transitions */
            --transition-fast: 150ms ease;
            --transition-base: 250ms ease;
            --transition-slow: 350ms ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: var(--font-body);
            background: var(--gray-50);
            color: var(--gray-800);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 var(--space-lg);
        }
        
        /* Header & Navigation */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--gray-200);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow-sm);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--space-lg) 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            text-decoration: none;
            color: var(--primary);
            font-weight: 700;
            font-size: 1.5rem;
            font-family: var(--font-heading);
            transition: transform var(--transition-base);
        }
        
        .logo:hover {
            transform: scale(1.05);
        }
        
        .logo-icon {
            font-size: 2rem;
            filter: drop-shadow(0 2px 4px rgba(0, 119, 182, 0.3));
        }
        
        nav {
            display: flex;
            gap: var(--space-xl);
            align-items: center;
            flex-wrap: wrap;
        }
        
        nav a {
            text-decoration: none;
            color: var(--gray-700);
            font-weight: 500;
            transition: color var(--transition-base);
            position: relative;
        }
        
        nav a:not(.btn):hover {
            color: var(--primary);
        }
        
        nav a:not(.btn)::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width var(--transition-base);
        }
        
        nav a:not(.btn):hover::after {
            width: 100%;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            color: var(--gray-600);
            font-weight: 500;
        }
        
        /* Buttons */
        .btn {
            padding: 0.65rem 1.5rem;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: var(--radius-full);
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-sm);
            font-weight: 600;
            font-family: var(--font-body);
            font-size: 0.95rem;
            transition: all var(--transition-base);
            box-shadow: 0 2px 8px rgba(0, 119, 182, 0.25);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 119, 182, 0.35);
            background: var(--primary-dark);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .btn-sm {
            padding: 0.5rem 1.2rem;
            font-size: 0.875rem;
        }
        
        .btn-secondary {
            background: var(--secondary);
            box-shadow: 0 2px 8px rgba(244, 63, 94, 0.25);
        }
        
        .btn-secondary:hover {
            box-shadow: 0 6px 16px rgba(244, 63, 94, 0.35);
            background: hsl(340, 82%, 56%);
        }
        
        .btn-danger {
            background: var(--danger);
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            box-shadow: none;
        }
        
        .btn-outline:hover {
            background: var(--primary);
            color: var(--white);
        }
        
        /* Hero Section */
        .hero {
            position: relative;
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: var(--white);
            background: linear-gradient(135deg, hsl(200, 98%, 39%) 0%, hsl(220, 90%, 56%) 100%);
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,133.3C960,128,1056,96,1152,85.3C1248,75,1344,85,1392,90.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') bottom/cover no-repeat;
            opacity: 0.6;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 900px;
            padding: var(--space-3xl) var(--space-lg);
        }
        
        .hero h1 {
            font-family: var(--font-heading);
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 800;
            margin-bottom: var(--space-lg);
            line-height: 1.2;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.8s ease;
        }
        
        .hero p {
            font-size: clamp(1.1rem, 2vw, 1.4rem);
            margin-bottom: var(--space-xl);
            opacity: 0.95;
            animation: fadeInUp 0.8s ease 0.2s backwards;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Search Bar in Hero */
        .hero-search {
            max-width: 600px;
            margin: 0 auto;
            animation: fadeInUp 0.8s ease 0.4s backwards;
        }
        
        .search-box {
            display: flex;
            background: var(--white);
            border-radius: var(--radius-full);
            padding: var(--space-xs);
            box-shadow: var(--shadow-xl);
        }
        
        .search-box input {
            flex: 1;
            border: none;
            padding: var(--space-md) var(--space-lg);
            font-size: 1rem;
            font-family: var(--font-body);
            outline: none;
            border-radius: var(--radius-full);
        }
        
        .search-box button {
            padding: var(--space-md) var(--space-xl);
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: var(--radius-full);
            font-weight: 600;
            cursor: pointer;
            transition: background var(--transition-base);
        }
        
        .search-box button:hover {
            background: var(--primary-dark);
        }
        
        /* Sections */
        .section {
            padding: var(--space-3xl) 0;
        }
        
        .section-title {
            text-align: center;
            font-family: var(--font-heading);
            font-size: clamp(2rem, 4vw, 2.75rem);
            font-weight: 700;
            margin-bottom: var(--space-2xl);
            color: var(--gray-900);
        }
        
        .section-subtitle {
            text-align: center;
            font-size: 1.125rem;
            color: var(--gray-600);
            margin-top: calc(-1 * var(--space-lg));
            margin-bottom: var(--space-2xl);
        }
        
        /* Grid Layout */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: var(--space-xl);
        }
        
        .grid-2 {
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        }
        
        .grid-4 {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }
        
        /* Cards */
        .card {
            background: var(--white);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all var(--transition-base);
            position: relative;
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }
        
        .card-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            background: linear-gradient(135deg, var(--primary) 0%, hsl(220, 90%, 56%) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: bold;
            font-size: 1.2rem;
            text-align: center;
            position: relative;
        }
        
        .card-img::before {
            content: '📷';
            font-size: 3rem;
            opacity: 0.3;
            position: absolute;
        }
        
        .card-img::after {
            content: attr(alt);
            z-index: 1;
        }
        
        .card-body {
            padding: var(--space-lg);
        }
        
        .card-title {
            font-family: var(--font-heading);
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: var(--space-sm);
            color: var(--gray-900);
        }
        
        .card-text {
            color: var(--gray-600);
            margin-bottom: var(--space-md);
            line-height: 1.6;
        }
        
        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: var(--space-md);
            border-top: 1px solid var(--gray-200);
        }
        
        /* Rating Stars */
        .rating {
            display: flex;
            align-items: center;
            gap: var(--space-xs);
            color: var(--accent);
            font-weight: 600;
        }
        
        .stars {
            letter-spacing: 2px;
        }
        
        /* Favorite Button */
        .favorite-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            transition: transform var(--transition-fast);
            color: var(--gray-400);
        }
        
        .favorite-btn:hover {
            transform: scale(1.2);
        }
        
        .favorite-btn.active {
            color: var(--danger);
        }
        
        /* Forms */
        .form-container {
            max-width: 550px;
            margin: var(--space-2xl) auto;
            background: var(--white);
            padding: var(--space-2xl);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
        }
        
        .form-group {
            margin-bottom: var(--space-lg);
        }
        
        .form-group label {
            display: block;
            margin-bottom: var(--space-sm);
            font-weight: 600;
            color: var(--gray-700);
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: var(--space-md);
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-family: var(--font-body);
            transition: border-color var(--transition-base);
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        /* Alerts */
        .alert {
            padding: var(--space-md);
            border-radius: var(--radius-md);
            margin-bottom: var(--space-lg);
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-success {
            background: hsl(142, 71%, 92%);
            color: hsl(142, 71%, 25%);
            border: 2px solid hsl(142, 71%, 35%);
        }
        
        .alert-error {
            background: hsl(0, 72%, 94%);
            color: hsl(0, 72%, 30%);
            border: 2px solid hsl(0, 72%, 50%);
        }
        
        /* Tables */
        table {
            width: 100%;
            background: var(--white);
            border-collapse: collapse;
            box-shadow: var(--shadow-md);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }
        
        th, td {
            padding: var(--space-md);
            text-align: left;
            border-bottom: 1px solid var(--gray-200);
        }
        
        th {
            background: var(--primary);
            color: var(--white);
            font-weight: 600;
        }
        
        tr:hover {
            background: var(--gray-50);
        }
        
        img.thumbnail {
            max-width: 80px;
            height: auto;
            border-radius: var(--radius-sm);
        }
        
        /* Footer */
        footer {
            background: var(--gray-900);
            color: var(--gray-300);
            padding: var(--space-3xl) 0 var(--space-lg);
            margin-top: var(--space-3xl);
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--space-2xl);
            margin-bottom: var(--space-2xl);
        }
        
        .footer-section h3 {
            color: var(--white);
            font-family: var(--font-heading);
            margin-bottom: var(--space-md);
            font-size: 1.25rem;
        }
        
        .footer-section ul {
            list-style: none;
        }
        
        .footer-section ul li {
            margin-bottom: var(--space-sm);
        }
        
        .footer-section a {
            color: var(--gray-400);
            text-decoration: none;
            transition: color var(--transition-base);
        }
        
        .footer-section a:hover {
            color: var(--white);
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: var(--space-lg);
            border-top: 1px solid var(--gray-800);
            color: var(--gray-500);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }
            
            nav {
                gap: var(--space-md);
            }
            
            .grid, .grid-2, .grid-4 {
                grid-template-columns: 1fr;
            }
            
            .header-content {
                flex-direction: column;
                gap: var(--space-md);
            }
            
            .search-box {
                flex-direction: column;
            }
            
            .search-box button {
                width: 100%;
            }
        }
        
        /* Utility Classes */
        .text-center { text-align: center; }
        .mt-1 { margin-top: var(--space-md); }
        .mt-2 { margin-top: var(--space-lg); }
        .mb-1 { margin-bottom: var(--space-md); }
        .mb-2 { margin-bottom: var(--space-lg); }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-1 { gap: var(--space-md); }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <a href="index.php" class="logo">
                    <span class="logo-icon">✈️</span>
                    <span>TravelGuide</span>
                </a>
                <nav>
                    <a href="index.php">Home</a>
                    <a href="places.php">Destinations</a>
                    <a href="gallery.php">Gallery</a>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="favorites.php">❤️ Favorites</a>
                        <a href="contact.php">Contact</a>
                        <a href="profile.php" class="user-info">
                            👤 Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <a href="logout.php" class="btn btn-sm btn-secondary">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-sm">Login</a>
                        <a href="register.php" class="btn btn-sm btn-secondary">Register</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>