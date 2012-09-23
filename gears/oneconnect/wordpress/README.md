# Feather OneConnect for WordPress

Feather OneConnect for WordPress enables Single Sign On between your Feather installation and WordPress. Your WordPress user base can sign in and be automatically authenticated when they visit your Feather installation.

### Installation

Installing Feather OneConnect for WordPress is dead easy.

1. Copy and paste the `feather-oneconnect.php` file to your WordPress plugin directory.
2. Enable the plugin in your WordPress administration panel.
3. Define your cookie path and domain in your `wp-config.php` file. Here's an example.

        // The cookie path should be just a slash.
        define('COOKIEPATH', '/');
        
        // The cookie domain should be the same as the one in your Feather installation.
        define('COOKIE_DOMAIN', '.mydomain.com');

4. Enable OneConnect on your Feather installation.
5. Copy the Authenticate, Registration, Login, and Logout URLs from the Feather OneConnect for WordPress configuration screen and paste them into the corresponding boxes on the OneConnect configuration.

When you next login to WordPress you'll become authenticated on your Feather installation. If something isn't working properly try deleting your cookies and starting fresh!

### How It Works

Feather OneConnect for WordPress only works when both WordPress and your Feather installation are on the same domain. This is because cookies are not available across domains.

When a user logs in to WordPress they are given a cookie which is stored on their local machine. When the user visits Feather, OneConnect is able to access all the cookies for the current domain, so this includes the WordPress cookies. OneConnect then makes a request to your WordPress installation, it passes along the cookie information so that WordPress can authenticate the current user. WordPress then returns a couple bits of information about the user to OneConnect.

Once OneConnect has this information it attempts to authorize the user on Feather. If the user has never visited the forum before, OneConnect will attempt to create a new account for the user with the same username and e-mail they have on WordPress. If, for some crazy reason, either of those is taken then OneConnect will show the user a form. The user then has the chance to create a new account, or link themselves to an existing account. Once done the user will be authenticated and can now access the forums. If the e-mail and username are available then the user will be immediately authenticated.

This all happens very quickly and unless the user is prompted to create or link an account they won't even realise it's happening.