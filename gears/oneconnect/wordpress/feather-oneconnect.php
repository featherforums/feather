<?php
/**
 * Plugin Name: Feather OneConnect for WordPress
 * Plugin URI: http://featherforums.com
 * Description: OneConnect allows users to authenticate themselves through WordPress and be authenticated on to a Feather installation as well.
 * Version: 1.0.0
 * Author: Jason Lewis
 * Author URI: http://featherforums.com
 */

if(!class_exists('Feather_OneConnect_Plugin'))
{
	class Feather_OneConnect_Plugin {

		/**
		 * Name of the cookie used by Feather. If you've changed this in application/session.php
		 * then you'll need to change it here as well.
		 * 
		 * @var string
		 */
		protected $cookie = 'laravel_session';

		/**
		 * Runs the authenticated user method if the request matches our option. Sets up
		 * activation and deactivation scripts as well as admin menu options.
		 * 
		 * @return void
		 */
		public function __construct()
		{
			$authenticate_url = get_option('feather_oneconnect_authenticate');

			if($authenticate_url and ($authenticate_url == trim($_SERVER['PATH_INFO'], '/') or ltrim($authenticate_url, '?') == $_SERVER['QUERY_STRING'] or $authenticate_url == substr(trim($_SERVER['REQUEST_URI'], '/'), 0, strlen($authenticate_url))))
			{
				$this->authenticated();
			}

			// Create the admin menu interface for our plugin.
			if(is_admin())
			{
				add_action('admin_menu', array($this, 'menu'));
			}

			add_action('clear_auth_cookie', array($this, 'logout'));

			register_activation_hook(__FILE__, array($this, 'activate'));
			register_deactivation_hook(__FILE__, array($this, 'deactivate'));
		}

		/**
		 * Logs the user out of Feather as well.
		 *
		 * @return void
		 */
		public function logout()
		{
			setcookie($this->cookie, ' ', time() - 31536000, COOKIEPATH, COOKIE_DOMAIN);
		}

		/**
		 * Adds the plugin to the plugins menu.
		 * 
		 * @return void
		 */
		public function menu()
		{
			add_plugins_page(
				'Feather OneConnect',
				'Feather OneConnect',
				'administrator',
				'oneconnect',
				array($this, 'options')
			);
		}

		/**
		 * The options page for the OneConnect plugin.
		 * 
		 * @return string
		 */
		public function options()
		{
			if(isset($_POST['save'])) {
				if(function_exists('current_user_can') && !current_user_can('manage_options'))
				{
					die(__('Permission Denied'));
				}

				$authenticate_url = str_replace('{token}', $this->token(), $_POST['feather_oneconnect_authenticate']);
			
				update_option('feather_oneconnect_authenticate', $authenticate_url);
			}
			else
			{
				$authenticate_url = get_option('feather_oneconnect_authenticate');
			}

			echo '<div class="wrap">';
			echo '<div id="icon-options-general" class="icon32"><br /></div>';
			echo '<h2>';
			echo _e('Feather OneConnect for Wordpress Configuration');
			echo '</h2>';
			echo '<p>The authenticate URL is set by default, however you can change this URL if the default is already in use. Use the <strong>{token}</strong> placeholder to generate a random token string.</p>';
			echo '<form method="post">';
			echo '<table class="form-table">';
			echo '<tr>';
			echo '<th>Authenticate URL</th>';
			echo '<td><input class="regular-text" type="text" name="feather_oneconnect_authenticate" value="' . $authenticate_url . '" /></td>';
			echo '</tr>';
			echo '</table>';
			echo '<p class="submit"><input type="submit" name="save" value="';
			echo _e('Save &raquo;');
			echo '" /></p>';
			echo '</form>';

			echo '<h3>';
			echo _e('Feather OneConnect for Wordpress Information');
			echo '</h3>';
			echo '<p>The following information needs to be copied and pasted over to the OneConnect options on your Feather installation.';
			echo '<br />Remember that if you changed the Authenticate URL above you need to save it first to get the updated URL.</p>';
			echo '<table class="form-table">';
			echo '<tr>';
			echo '<th>Authenticate URL</th>';
			echo '<td><span class="description">' . site_url(get_option('feather_oneconnect_authenticate'), 'oneconnect-feather-authenticate') . '</span></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th>Registration URL</th>';
			echo '<td><span class="description">' . site_url('wp-login.php?action=register', 'login') . '</span></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th>Login URL</th>';
			echo '<td><span class="description">' . wp_login_url() . '?redirect_to={current}</span></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th>Logout URL</th>';
			echo '<td><span class="description">' . add_query_arg(array('action' => 'logout', '_wpnonce' => '{token}', 'redirect_to' => '{current}'), site_url('wp-login.php', 'login')) . '</span></td>';
			echo '</tr>';
			echo '</table>';
			echo '</div>';
		}

		/**
		 * Shows the authenticated user details in a JSON encoded array.
		 * 
		 * @return string
		 */
		protected function authenticated()
		{
			global $current_user;

			if(!function_exists('get_currentuserinfo'))
			{
				require (ABSPATH . WPINC . '/pluggable.php');
			}
			
      		get_currentuserinfo();

      		$credentials = array(
      			'id'       => null,
      			'username' => null,
      			'email'	   => null,
      			'token'	   => null
      		);

			if($current_user->ID)
			{
				$credentials = array(
					'id'	   => $current_user->ID,
					'username' => $current_user->user_login,
					'email'	   => $current_user->user_email,
					'token'	   => wp_create_nonce('log-out')
				);
			}

			die(json_encode($credentials));
		}

		/**
		 * Runs when the plugin is activated, adds options.
		 * 
		 * @return void
		 */
		public function activate()
		{
			add_option('feather_oneconnect_authenticate', '?oneconnect=' . $this->token());
		}

		/**
		 * Runs when the plugin is deactivated, deletes options.
		 * 
		 * @return void
		 */
		public function deactivate()
		{
			delete_option('feather_oneconnect_authenticate');
		}

		/**
		 * Generates a random token.
		 * 
		 * @return string
		 */
		protected function token()
		{
			return md5(uniqid());
		}

	}

	// Instantiate the plugin! This is way nicer then a bunch of crummy functions in the global space!
	// Code pretty! Code with Laravel! :)
	$oneconnect = new Feather_OneConnect_Plugin;
}