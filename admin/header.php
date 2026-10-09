<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Admin Panel'; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f8f9fa; color: #343a40; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        header { background: #005a9c; color: white; padding: 15px 0; }
        .header-content { display: flex; justify-content: space-between; align-items: center; }
        .logo { display: flex; align-items: center; gap: 10px; color: white; text-decoration: none; font-weight: bold; font-size: 1.3rem; }
        nav { display: flex; gap: 20px; align-items: center; }
        nav span { color: white; }
        .btn { padding: 10px 20px; background: #005a9c; color: white; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-block; font-weight: bold; }
        .btn-sm { padding: 6px 12px; font-size: 0.9rem; }
        .btn-danger { background: #dc3545; }
        .admin-nav { background: white; padding: 20px; border-radius: 8px; margin: 30px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .admin-nav a { margin-right: 20px; text-decoration: none; color: #005a9c; font-weight: 500; }
        .admin-nav a:hover { text-decoration: underline; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin: 30px 0; }
        .card { background: white; border-radius: 12px; padding: 30px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-container { max-width: 800px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin: 20px 0; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; }
        .form-group textarea { min-height: 100px; }
        .alert { padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        table { width: 100%; background: white; border-collapse: collapse; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #005a9c; color: white; }
        tr:hover { background: #f8f9fa; }
        img.thumbnail { max-width: 80px; height: auto; border-radius: 4px; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <a href="dashboard.php" class="logo">
                    <span style="font-size: 2rem;">⚙</span>
                    <span>Admin Panel</span>
                </a>
                <nav>
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
                </nav>
            </div>
        </div>
    </header>
    <div class="container">
