<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * Users
 *
 * This model represents user authentication data. It operates the following tables:
 * - user account data,
 * - user profiles
 *
 * @package	Tank_auth
 * @author	Ilya Konyukhov (http://konyukhov.com/soft/)
 */
class Users extends CI_Model {
	private $table_name = 'users'; // user accounts
	private $profile_table_name = 'user_profiles'; // user profiles

	function __construct() {
		parent::__construct();

		$ci = &get_instance();
		$this->table_name = $ci->config->item('db_table_prefix', 'tank_auth') . $this->table_name;
		$this->profile_table_name = $ci->config->item('db_table_prefix', 'tank_auth') . $this->profile_table_name;
	}

	/**
	 * Get user record by Id
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	function get_user_by_id($user_id) {
		$this->db->where('id', $user_id);

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) {
			return $query->row();
		}

		return NULL;
	}

	/**
	 * Get user record by username
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_username($username) {
		$this->db->where('LOWER(username)=', strtolower($username));

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) {
			return $query->row();
		}

		return NULL;
	}

	/**
	 * Check if username available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_username_available($username) {
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(username)=', strtolower($username));

		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 0;
	}

	/**
	 * Create new user record
	 *
	 * @param	array
	 * @param	bool
	 * @return	array
	 */
	function create_user($data) {
		$data['created'] = date('Y-m-d H:i:s');

		if ($this->db->insert($this->table_name, $data)) {
			$user_id = $this->db->insert_id();
			return array('user_id' => $user_id);
		}
		return NULL;
	}

	/**
	 * Update user login info, such as IP-address or login time, and
	 * clear previously generated (but not activated) passwords.
	 *
	 * @param	int
	 * @param	bool
	 * @param	bool
	 * @return	void
	 */
	function update_login_info($user_id) {
		$this->db->set('last_ip', $this->input->ip_address());
		$this->db->set('last_login', date('Y-m-d H:i:s'));

		$this->db->where('id', $user_id);
		$this->db->update($this->table_name);
	}
}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */