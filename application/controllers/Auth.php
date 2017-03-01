<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('error_message');
		$this->load->library('tank_auth');
	}

	/**
	 * 根据signed_key获取用户信息接口
	 * Login user
	 *
	 * @return void
	 */
	function get_id_by_signed_key() {
		$signed_key = $this->input->get_post('signed_key');
		if (empty($signed_key) == TRUE) {
			$this->error_message->output(10006);
			return;
		}

		$user_id = $this->tank_auth->get_id_by_signed_key($signed_key);
		if ($user_id == 0) {
			$this->error_message->output($this->tank_auth->get_error_code());
		} else {
			$result = array(
				'uid' => $user_id,
			);
			$this->error_message->output(10000, $result);
		}
	}
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
