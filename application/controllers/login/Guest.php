<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Guest extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('error_message');
		$this->load->library('tank_auth');
	}

	/**
	 * 免账号登陆
	 * @return [type] [description]
	 */
	public function index() {
		$guest = $this->input->get_post('guest');
		$key = $this->input->get_post('key');
		$vertify = $this->input->get_post('vertify');

		$user_name = $this->config->item('login_by_guest_prefix', 'auth') . md5($guest . $this->config->item('login_by_guest_username_prefix', 'auth'));
		$password = md5($user_name . $this->config->item('login_by_guest_key', 'auth'));

		if (empty($guest)) {
			$this->error_message->output(10010);
			return;
		}

		// 创建一个匿名账号
		$this->tank_auth->create_user($user_name, $password);

		$user_id = $this->tank_auth->login(
			$user_name,
			$password);
		if ($user_id !== NULL) {
			// success login
			$result = array('signed_key' => $this->tank_auth->get_user_signed_key($user_id));
			$this->error_message->output(10000, $result);
		} else {
			// login fail
			$this->error_message->output($this->tank_auth->get_error_code());
		}
	}
}

/* End of file Guest.php */
/* Location: ./application/controllers/Guest.php */