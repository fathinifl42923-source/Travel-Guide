# 🌍 Travel Guide Web Application

> Travel Guide is a modern full-stack web application built with PHP and MySQL. It empowers travelers to explore top destinations, filter by categories, bookmark favorites, and share reviews with ratings. Featuring user authentication, photo galleries, responsive UI, and a powerful admin dashboard, it delivers a seamless travel discovery experience.

---

## ✨ Features

- **🏖️ Destination Discovery:** Browse featured destinations with ratings, descriptions, and high-resolution photo views.
- **🔍 Search & Filter:** Filter places by custom categories (Beach, Mountain, City, Historical, Adventure, Nature, Cultural, Relaxation) or search by keyword.
- **⭐ User Reviews & Ratings:** Registered travelers can rate places from 1 to 5 stars and post detailed travel reviews.
- **❤️ Favorites / Bookmarks:** Save favorite destinations for quick access on your profile.
- **📸 Interactive Gallery:** Community and destination photo showcase.
- **👤 User Profiles:** Personalized profile page with avatar upload and saved favorites management.
- **🛡️ Admin Dashboard:** Complete administrative portal to manage places, categories, galleries, and read visitor contact messages.
- **📬 Newsletter & Contact:** Working contact forms and newsletter subscription system.

---

## 🛠️ Tech Stack

- **Frontend:** HTML5, CSS3 (Modern Responsive UI), JavaScript (Vanilla JS)
- **Backend:** PHP (PDO for secure MySQL queries, Session Management)
- **Database:** MySQL / MariaDB (InnoDB relational schema)
- **Server:** Apache (XAMPP / WampServer / LAMP)

---

## 📁 Project Structure

```
travel_guide/
├── admin/                  # Admin portal (dashboard, management, auth)
├── api/                    # Asynchronous backend endpoints (favorites, reviews, etc.)
├── js/                     # Client-side JavaScript
├── uploads/
│   ├── gallery/            # Gallery photo uploads
│   └── places/             # Destination images
├── contact.php             # Contact page
├── db.php                  # PDO database connection configuration
├── favorites.php           # User favorites list
├── footer.php              # Shared page footer
├── gallery.php             # Photo gallery page
├── header.php              # Shared navbar and header
├── index.php               # Homepage / Hero / Featured places
├── login.php               # User login
├── logout.php              # Session logout
├── place-detail.php        # Detailed view for a specific destination
├── places.php              # Destinations list & category filtering
├── profile.php             # User profile management
├── register.php            # User registration
├── schema.sql              # Complete database tables & sample data
└── SETUP_GUIDE.md          # Step-by-step setup walkthrough
```

---

## 🚀 Quick Setup & Installation

### 1. Prerequisites
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL with PHP 7.4+)

### 2. Clone the Repository
```bash
git clone https://github.com/fathinifl42923-source/Travel-Guide.git
```
Move the folder to your web server root (e.g., `C:/xampp/htdocs/travel_guide` or `D:/xampp/htdocs/travel_guide`).

### 3. Database Setup
1. Open **phpMyAdmin** at `http://localhost/phpmyadmin`.
2. Create a database named `travel_guide`.
3. Select the `travel_guide` database, go to the **Import** tab (or **SQL** tab), and run the contents of [`schema.sql`](schema.sql).
4. Verify database credentials in [`db.php`](db.php):
   ```php
   $host = 'localhost';
   $dbname = 'travel_guide';
   $username = 'root';
   $password = '';
   ```

### 4. Run the Application
- **Frontend:** `http://localhost/travel_guide/`
- **Admin Panel:** `http://localhost/travel_guide/admin/login.php`
  - Default Admin Username: `admin`
  - Default Admin Password: `admin123`

---

## 📄 License
This project is open source and available under the [MIT License](LICENSE).
