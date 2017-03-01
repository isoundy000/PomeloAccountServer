<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * 输出结果类
 */
class Error_message {
	function __construct() {
		$this->ci = &get_instance();

		$this->ci->load->config('error_code', TRUE);
	}

	/**
	 * 输出结果
	 * @param  [错误码] $error_code
	 * @param  [其他参数] $params
	 * @return [none]
	 */
	function output($error_code, $params = array()) {
		$result = array(
			'code' => $error_code,
			'msg' => $this->get_error_msg($error_code),
			'result' => $params);
		echo json_encode($result);
	}

	/**
	 * 根据功能，获取相应的错误码的文字
	 * @param  [string] $area
	 * @param  [int] $error_code
	 * @return [string]
	 */
	function get_error_msg($error_code) {
		$error_array = $this->ci->config->item('error_code');
		if (isset($error_array[$error_code])) {
			return $error_array[$error_code];
		}
		return '';
	}

}

/* End of file Error_message.php */
/* Location: ./application/libraries/Error_message.php */
