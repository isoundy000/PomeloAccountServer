<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * Sessions
 *
 * This model represents user authentication data. It operates the following tables:
 * - session account data,
 *
 * @package	Tank_auth
 * @author	Ilya Konyukhov (http://konyukhov.com/soft/)
 */
class Sessions extends CI_Model {
	private $table_name = 'sessions'; // user accounts

	function __construct() {
		parent::__construct();

		$ci = &get_instance();
	}

	/**
	 * Get user record by user_id
	 *
	 * @param	user_id
	 * @return	object
	 */
	function get_record($user_id) {
		$this->db->where('LOWER(user_id)=', strtolower($user_id));

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			return NULL;
		}
	}

	/**
	 * Get user signed_key
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_signed_key($user_id) {
		$record = $this->get_record($user_id);
		if ($record == NULL) {
			return NULL;
		} else {
			return $record->signed_key;
		}
	}

	/**
	 * 根据signed_key获取sq用户id
	 * get user id by signed_key
	 *
	 * @return user_id
	 */
	function get_session_by_signed_key($signed_key) {
		$this->db->where('LOWER(signed_key)=', strtolower($signed_key));
		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			return NULL;
		}
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
	function update_session_info($user_id) {
		if ($this->get_record($user_id) == NULL) {
			$this->create_session($user_id);
		}
		$this->db->set('signed_key', md5($user_id . rand() . microtime()));

		$this->db->where('user_id', $user_id);
		$this->db->update($this->table_name);
	}

	/**
	 * 根据user_id 创建session
	 *
	 * @return void
	 */
	function create_session($user_id) {
		$data['user_id'] = $user_id;
		$this->db->insert($this->table_name, $data);
	}
}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */
