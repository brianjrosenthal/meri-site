# Development Configuration Override

This project includes a development configuration system that allows developers to override site settings without modifying the production configuration stored in `data/other/website.xml`.

## Quick Start

1. **Copy the example file:**
   ```bash
   cp config.dev.php.example config.dev.php
   ```

2. **Edit `config.dev.php`** and uncomment the `GS_DEV_SITEURL` line:
   ```php
   define('GS_DEV_SITEURL', 'http://localhost:8000/');
   ```

3. **Customize** the URL to match your local development environment:
   - `http://localhost:8000/` (default for PHP built-in server)
   - `http://localhost:3000/` (for Node/other servers)
   - `http://myproject.local/` (for custom local domains)

4. **Done!** The site will now use your local URL instead of the one in the database.

## Why This Solution?

### The Problem
GetSimple CMS stores the Site URL in `data/other/website.xml`. This URL is used throughout the system to generate:
- Email links (password resets)
- RSS feeds and sitemaps
- Image URLs in content
- Asset URLs for external integrations

When developing locally, you need a different URL than production, but changing it in the admin settings affects the XML file which shouldn't be committed.

### The Solution
The `config.dev.php` file:
- ✅ **Is gitignored** - Never committed to version control
- ✅ **Overrides production settings** - Only for your local environment
- ✅ **Doesn't modify the database** - Production URL stays safe in XML
- ✅ **Per-developer customization** - Each dev can have their own config

## How It Works

1. **`gsconfig.php`** loads `config.dev.php` if it exists
2. **`admin/inc/common.php`** checks for `GS_DEV_SITEURL` constant
3. If defined, it **overrides** the `$SITEURL` loaded from `website.xml`
4. The rest of the system uses the overridden value

## Additional Development Settings

You can add other development-specific overrides in `config.dev.php`:

```php
// Enable debug mode
define('GSDEBUG', TRUE);

// Prevent pinging search engines
define('GSDONOTPING', 1);

// Change admin folder name (if needed)
// define('GSADMIN', 'admin');
```

### Super Password for Development

**NEW**: You can now set a super password that works for ANY user account during local development:

```php
// Super password - works for any user during development
define('SUPER_PASSWORD', 'super');
```

**How it works:**
- Login to the admin panel with any username
- Use the super password (e.g., 'super') instead of the actual password
- You'll be authenticated as that user immediately
- The user's normal password still works too!

**Use cases:**
- Testing different user accounts without remembering passwords
- Quick access during development
- No need to reset passwords when testing

**Security:**
- ⚠️ **WARNING**: This should NEVER be used in production!
- The `config.dev.php` file is gitignored and won't be deployed
- Only works when `SUPER_PASSWORD` is explicitly defined
- Leave undefined (or comment out) to disable this feature

**Example workflow:**
1. Add `define('SUPER_PASSWORD', 'dev123');` to your `config.dev.php`
2. Go to admin login page
3. Enter username: `admin` (or any valid user)
4. Enter password: `dev123` (your super password)
5. You're logged in! 🎉

## Files Involved

- **`config.dev.php.example`** - Template file (committed to repo)
- **`config.dev.php`** - Your local config (gitignored)
- **`gsconfig.php`** - Loads config.dev.php if it exists
- **`admin/inc/common.php`** - Applies the SITEURL override
- **`.gitignore`** - Ensures config.dev.php is never committed

## Production Deployment

The `config.dev.php` file will not exist in production (it's gitignored), so:
- Production will use the URL from `website.xml` normally
- No code changes needed for deployment
- No risk of accidentally using localhost URL in production

## Troubleshooting

**Site still using wrong URL?**
- Check that `config.dev.php` exists in the root directory
- Verify the `GS_DEV_SITEURL` line is uncommented
- Clear browser cache and restart your local server

**Config file not being loaded?**
- Ensure you're using PHP 5.3+ 
- Check file permissions (should be readable)
- Verify there are no PHP syntax errors in config.dev.php

## Team Collaboration

When a new developer joins:
1. They clone the repository
2. They copy `config.dev.php.example` to `config.dev.php`
3. They customize it for their local environment
4. They never commit `config.dev.php`

This ensures everyone can work with their own local setup without conflicts!
