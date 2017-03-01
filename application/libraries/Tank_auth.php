<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

require_once 'phpass-0.1/PasswordHash.php';

define('STATUS_ACTIVATED', '1');
define('STATUS_NOT_ACTIVATED', '0');

/**
 * Tank_auth
 *
 * Authentication library for Code Igniter.
 *
 * @package		Tank_auth
 * @author		Ilya Konyukhov (http://konyukhov.com/soft/)
 * @version		1.0.9
 * @based on	DX Auth by Dexcell (http://dexcell.shinsengumiteam.com/dx_auth)
 * @license		MIT License Copyright (c) 2008 Erick Hartanto
 */
class Tank_auth {
	private $error_code = 0;

	function __construct() {
		$this->ci = &get_instance();

		$this->ci->load->config('auth', TRUE);

		$this->ci->load->database();
		$this->ci->load->model('users');
		$this->ci->load->model('sessions');
	}

	/**
	 * Login user on the site. Return TRUE if login is successful
	 * (user exists and activated, password is correct), otherwise FALSE.
	 *
	 * @param	string	(username or email or both depending on settings in config file)
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function login($username, $password) {
		if ((strlen($username) <= 0) OR (strlen($password) <= 0)) {
			return NULL;
		}

		if (is_null($user = $this->ci->users->get_user_by_username($username))) {
			// fail - wrong username
			$this->error_code = 10002;
			return NULL;
		}

		// Does password match hash in database?
		$hasher = new PasswordHash(
			$this->ci->config->item('phpass_hash_strength', 'auth'),
			$this->ci->config->item('phpass_hash_portable', 'auth'));
		if ($hasher->CheckPassword($password, $user->password)) {
			// password ok
			$this->ci->users->update_login_info($user->id);

			$this->ci->sessions->update_session_info($user->id);
			return $user->id;
		} else {
			// fail - wrong password
			$this->error_code = 10001;
			return NULL;
		}
	}

	/**
	 * Create new user on the site and return some data about it:
	 * user_id, username, password, email, new_email_key (if any).
	 *
	 * @param	string
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	array
	 */
	function create_user($username, $password) {
		if ((strlen($username) > 0) AND !$this->ci->users->is_username_available($username)) {
			$this->error_code = 10004;
		} else {
			// Hash password using phpass
			$hasher = new PasswordHash(
				$this->ci->config->item('phpass_hash_strength', 'auth'),
				$this->ci->config->item('phpass_hash_portable', 'auth'));
			$hashed_password = $hasher->HashPassword($password);

			$data = array(
				'username' => $username,
				'password' => $hashed_password,
				'last_ip' => $this->ci->input->ip_address(),
			);

			if (!is_null($res = $this->ci->users->create_user($data))) {
				$data['user_id'] = $res['user_id'];
				$data['password'] = $password;
				unset($data['last_ip']);
				return $data;
			}
		}
		return NULL;
	}

	/**
	 * Get user signed_key
	 *
	 * @param	string
	 * @return	object
	 */
	function get_id_by_signed_key($signed_key) {
		$session = $this->ci->sessions->get_session_by_signed_key($signed_key);
		if ($session != NULL) {
			return $session->user_id;
		}
		$this->error_code = 10005;
		return NULL;
	}

	/**
	 * Get user signed_key
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_signed_key($user_id) {
		return $this->ci->sessions->get_user_signed_key($user_id);
	}

	/**
	 * Check if username available for registering.
	 * Can be called for instant form validation.
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_username_available($username) {
		return ((strlen($username) > 0) AND $this->ci->users->is_username_available($username));
	}

	/**
	 * Get error message.
	 * Can be invoked after any failed operation such as login or register.
	 *
	 * @return	string
	 */
	function get_error_code() {
		return $this->error_code;
	}
}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */