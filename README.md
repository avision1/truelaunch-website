<<<<<<< HEAD
# True Launch Website

Professional single-page site with a lightweight PHP + MySQL admin CMS for cPanel.

## Structure
- app/ - PHP config, content helpers, and schema
- public/ - web root (index.php, assets, admin, api)
- public/admin/ - content editor
- public/api/contact.php - contact form handler

## Local development
1. Ensure PHP 8+ is available.
2. Run: php -S localhost:8000 -t public
3. Open: http://localhost:8000

## Database setup
1. Create a MySQL database and user in cPanel.
2. Import schema: app/sql/schema.sql
3. Update app/config.php with the DB credentials.

## Admin access
1. Generate a password hash:
   php -r "echo password_hash('YOUR_PASSWORD', PASSWORD_DEFAULT) . PHP_EOL;"
2. Update app/config.php:
   - admin.user
   - admin.pass_hash
3. Visit /admin to sign in.

## cPanel deployment
- Preferred: set document root to the public/ folder.
- If you must use public_html:
  - Move contents of public/ into public_html.
  - Keep app/ outside public_html or protect it with app/.htaccess.
- Update site.base_url in app/config.php if deployed in a subdirectory.

## Assets
- Place your logo at public/assets/images/logo-1.png

## Contact form
- Uses PHP mail(). Update mail.to and mail.from in app/config.php.
=======
# truelaunch-website
>>>>>>> 6df84d0e86ea05da633306d408b9366041ab33c7
