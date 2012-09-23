## OneConnect

OneConnect is a gear developed for Feather which allows an application on the same domain as Feather to connect and share user information. OneConnect is very easy to get going, with only a few steps required on the developers end.

To use OneConnect you need to have knowledge of how to fetch the logged in users information for your application and creating a page that can display this information.

<div class="alert alert-info" markdown="1">
**Hold up, are you using Laravel?**

If you're developing an application with [Laravel](http://laravel.com) then we suggest you use the **[Harmonize](/auth/single-sign-on/harmonize)** gear instead.
</div>

### WordPress Plugin

A WordPress plugin is provided with OneConnect. 

1. Copy the `feather-oneconnect.php` file from `gears/oneconnct/wordpress` into your WordPress plugin directory.
2. From your WordPress dashboard enable the plugin from the Installed Plugins screen.
3. Select the Feather OneConnect settings in the navigation and take note of the configuration details.
4. Enable OneConnect in your Feather dashboard, and during configuration paste the details that were listed by the WordPress plugin.

Your WordPress users should now be able to login to your WordPress website and be automatically authorized on Feather.

<div class="alert alert-info" markdown="1">
<strong>Just a sec!</strong> Be sure to read the README.md file on setting the cookie domain and path for your WordPress installation.
</div>

### How OneConnect Works

When a user logs in to your application a cookie is set for that user. Your application has access to this cookie, so on subsequent page visits the cookie is picked up and your application will know to keep that user logged in. Let's assume that **Jack** has just logged in to your application. When Jack visits the forum Feather will make a request to a page on your application (yes, the page you need to create). Because Jack is logged in this page shows some information about Jack that Feather grabs. Here's an example.

~~~~
{
	"id":874,
	"username":"Jack",
	"email":"jack@beanstalk.com"
}
~~~~

The response is JSON, this allows Feather to easily parse the users information. Now that Feather has the Jack's information it can attempt to authorize him on the forums. Feather will see that Jack is yet to visit the forums, so an account is created for Jack and it's linked to his account on your application. His experience is seemless and he knows nothing of what's going on in the background.

If Jack has already visited the forums before then he'll be authorized for the forums. Again, Jack's experience is seemless.

The only time Jack will have an interupted experience is when an account already exists with his username or e-mail address. The only time this may occur is if you're forum has been running longer then your application. If Feather is unable to authorize Jack he'll be presented with a screen asking him to create a new account or link to an existing account. If Jack was already signed up on your forums beforehand then he's able to provide his e-mail address on the forum and the corrosponding password and link his account. If Jack does not have an account then he's able to create a new account.

OneConnect is actually quite a simple process, however for it to work there are a few things that you, as the developer, need to address on your application.

### Application Cookies

OneConnect requires that your applications cookies be set on the same domain as Feather.

~~~~
setcookie("TestCookie", $value, time() + 3600, "/", ".yourapp.com");
~~~~

You can see that the cookie path and domain have been set to the entire domain, this means that OneConnect has access to your applications cookies.

<div class="alert alert-info" markdown="1">
Note that the leading **.** is required for older browsers still implementing the deprecated [RFC 2109](http://www.faqs.org/rfcs/rfc2109).
</div>

### User Information

When setting up OneConnect you'll be required to enter an **Authentication URL**. This URL is where the JSON encoded logged in users information is displayed. The following fields are required for authorizing the user.

~~~~
{
	"id":1,
	"username":"Username",
	"email":"E-mail Address"
}
~~~~

<div class="alert alert-info" markdown="1">
When a user is not logged in an empty JSON encoded array or blank page should be returned.
</div>

### Conclusion

You should now have OneConnect configured and working correctly. When users from your application login they'll be automatically authoried on Feather. If you have any troubles try clearing your cookies first and then trying again.