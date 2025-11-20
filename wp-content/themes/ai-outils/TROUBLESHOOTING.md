# AI Outils Theme - Troubleshooting Guide

## Issue: CSS Not Loading / Theme Not Working

If you're seeing unstyled content (no CSS), follow these steps:

### 1. Activate the Theme

The most common issue is that the theme hasn't been activated yet.

**Steps:**
1. Log in to your WordPress admin panel (usually `http://yoursite.com/wp-admin`)
2. Go to **Appearance > Themes**
3. Find "AI Outils" theme
4. Click **Activate**

### 2. Check WordPress Site URL

If CSS still doesn't load, your WordPress site URL might be misconfigured.

**Steps:**
1. Go to **Settings > General** in wp-admin
2. Verify that "WordPress Address (URL)" and "Site Address (URL)" match your actual site URL
3. They should both be something like `http://yoursite.com` (without trailing slash)
4. Save changes if you made any modifications

### 3. Clear Browser Cache

**Steps:**
- Hard refresh your browser: `Ctrl+F5` (Windows) or `Cmd+Shift+R` (Mac)
- Or clear your browser cache completely

### 4. Check File Permissions

Ensure the theme files are readable by the web server:

```bash
chmod -R 755 /path/to/wordpress/wp-content/themes/ai-outils
find /path/to/wordpress/wp-content/themes/ai-outils -type f -exec chmod 644 {} \;
```

### 5. Check .htaccess File

If using Apache, ensure your `.htaccess` file in the WordPress root directory has the correct rules:

```apache
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
```

### 6. Enable WordPress Debug Mode

To see if there are any PHP errors:

1. Edit `wp-config.php`
2. Find the line with `define( 'WP_DEBUG', false );`
3. Replace it with:
```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```
4. Check `/wp-content/debug.log` for any errors

### 7. Check Web Server

Make sure your web server (Apache/Nginx) is running and serving WordPress correctly.

**For Apache:**
```bash
sudo systemctl status apache2
# or
sudo systemctl status httpd
```

**For Nginx:**
```bash
sudo systemctl status nginx
```

### 8. Verify WordPress is Installed

Test if WordPress is working at all by visiting:
- `http://yoursite.com/wp-admin` - Should show login page
- `http://yoursite.com` - Should show your site (even if unstyled)

### 9. Check Browser Console

1. Open browser developer tools (F12)
2. Go to the Console tab
3. Look for any errors (especially 404 errors for CSS files)
4. Go to the Network tab
5. Reload the page
6. Check if `style.css` is loading successfully

### 10. Manual CSS Test

If all else fails, try viewing the CSS file directly:
- Visit: `http://yoursite.com/wp-content/themes/ai-outils/style.css`
- You should see CSS code, not a 404 error

## Still Having Issues?

### Check These Common Problems:

1. **Wrong directory structure**: Ensure the theme is at `wp-content/themes/ai-outils/` (not in a subdirectory)

2. **PHP version**: Ensure you're running PHP 7.4 or higher:
   ```bash
   php -v
   ```

3. **WordPress version**: This theme requires WordPress 6.0+

4. **Plugin conflicts**: Temporarily deactivate all plugins to see if one is causing issues

5. **Theme switch**: Try switching to a default WordPress theme (Twenty Twenty-Three), then back to AI Outils

## Required Files Check

Ensure these essential files exist:
- ✓ `style.css`
- ✓ `functions.php`
- ✓ `header.php`
- ✓ `footer.php`
- ✓ `index.php`

## Getting Help

If you've tried all the above and still have issues:

1. Check the WordPress debug log: `wp-content/debug.log`
2. Check your web server error log
3. Verify database connection is working
4. Ensure all WordPress core files are present and not corrupted

## Recent Fixes Applied

The theme has been updated with:
- ✅ Improved asset enqueuing
- ✅ Critical inline CSS for failsafe rendering
- ✅ File existence checks before loading JavaScript
- ✅ Proper priority for enqueue hooks

These changes ensure that even if external CSS fails to load, you'll still see basic styling through inline CSS.
