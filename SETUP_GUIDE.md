# Travel Guide - Setup Instructions

## 🚀 Getting Started

Follow these steps to set up your newly upgraded Travel Guide application.

### Prerequisites
- XAMPP (Apache + MySQL) installed
- PHP 7.4 or higher
- Modern web browser

### Step 1: Database Setup

1. Start XAMPP and ensure Apache and MySQL are running
2. Open phpMyAdmin at `http://localhost/phpmyadmin`
3. Import the database schema:
   - Click on your `travel_guide` database
   - Go to the "SQL" tab
   - Open `schema.sql` and copy its contents
   - Paste and execute the SQL queries
4. Verify all new tables are created:
   - `categories`
   - `place_categories`
   - `reviews`
   - `favorites`
   - `newsletter_subscribers`

### Step 2: File Structure Verification

Ensure the following directories exist:
```
travel_guide/
├── api/                    # Backend API endpoints
├── admin/                  # Admin panel
├── js/                     # JavaScript files
├── uploads/
│   ├── places/            # Place images
│   └── gallery/           # Gallery images
```

### Step 3: Access the Application

**Frontend:**
- Homepage: `http://localhost/travel_guide/`
- Login: `http://localhost/travel_guide/login.php`
- Register: `http://localhost/travel_guide/register.php`

**Admin Panel:**
- Login: `http://localhost/travel_guide/admin/login.php`
- Dashboard: `http://localhost/travel_guide/admin/dashboard.php`

### Step 4: Initial Data Setup

1. **Add Categories** (Admin Panel):
   - Navigate to Admin → Categories
   - Add categories like:
     - 🏖️ Beach
     - ⛰️ Mountain
     - 🏙️ City
     - 🏛️ Historical

2. **Add Destinations** (Admin Panel):
   - Navigate to Admin → Places
   - Add some destinations with images and descriptions
   - Assign categories to each destination

3. **Test User Features**:
   - Create a user account
   - Browse destinations
   - Add destinations to favorites
   - Write reviews
   - Update profile

## ✨ New Features

### For Users:
1. **Search & Filter** - Find destinations by name, location, or category
2. **Reviews & Ratings** - Rate destinations and read others' experiences
3. **Favorites** - Save destinations for future reference
4. **User Profile** - Manage personal information and view activity
5. **Newsletter** - Subscribe to travel updates
6. **Social Sharing** - Share destinations on Facebook, Twitter, WhatsApp
7. **Image Lightbox** - View gallery images in fullscreen

### For Admins:
1. **Enhanced Dashboard** - Comprehensive statistics and recent activity
2. **Category Management** - Create and manage destination categories
3. **Review Moderation** - View and delete inappropriate reviews
4. **Newsletter Management** - View and export subscriber list
5. **Modern UI** - Improved admin panel with better navigation

## 🎨 Design Highlights

- **Modern Color Scheme** - Vibrant, professional colors
- **Google Fonts** - Inter for body text, Poppins for headings
- **Smooth Animations** - Hover effects, transitions, micro-interactions
- **Fully Responsive** - Works perfectly on mobile, tablet, and desktop
- **Glassmorphism** - Modern frosted glass effects
- **Gradient Accents** - Beautiful color gradients throughout

## 🔧 Customization

### Change Colors:
Edit the CSS variables in `header.php`:
```css
:root {
    --primary: hsl(200, 98%, 39%);
    --secondary: hsl(340, 82%, 62%);
    --accent: hsl(35, 100%, 60%);
}
```

### Add More Categories:
Go to Admin Panel → Categories and add new ones with emojis

### Modify Hero Text:
Edit `index.php` hero section

## 📱 Mobile Responsive

The site is fully optimized for:
- Mobile phones (375px and up)
- Tablets (768px and up)
- Desktops (1024px and up)
- Large screens (1920px and up)

## 🐛 Troubleshooting

**Images not loading:**
- Ensure uploads folder has proper permissions
- Check image paths in database match actual files

**Database errors:**
- Verify all tables are created from schema.sql
- Check database connection in db.php

**JavaScript not working:**
- Clear browser cache
- Check browser console for errors
- Ensure js/main.js is loaded

## 🎯 Next Steps

1. Add real destination data
2. Upload quality images
3. Test all features thoroughly
4. Customize colors to match your brand
5. Add more content to gallery
6. Invite users to test and provide feedback

## 📧 Support

For any issues or questions, use the contact form or check the code comments for guidance.

---

**Congratulations!** Your Travel Guide is now a fully-featured, professional travel platform! 🎉
