<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/*
|--------------------------------------------------------------------------
| Security settings
|
| The library uses PasswordHash library for operating with hashed passwords.
| 'phpass_hash_portable' = Can passwords be dumped and exported to another server. If set to FALSE then you won't be able to use this database on another server.
| 'phpass_hash_strength' = Password hash strength.
|--------------------------------------------------------------------------
 */
$config['phpass_hash_portable'] = TRUE;
$config['phpass_hash_strength'] = 8;

/*
|--------------------------------------------------------------------------
| Login settings
|
| 'login_by_username' = Username can be used to login.
| 'login_by_email' = Email can be used to login.
| You have to set at least one of 2 settings above to TRUE.
| 'login_by_username' makes sense only when 'use_username' is TRUE.
|
| 'login_record_ip' = Save in database user IP address on user login.
| 'login_record_time' = Save in database current time on user login.
|
| 'login_count_attempts' = Count failed login attempts.
| 'login_max_attempts' = Number of failed login attempts before CAPTCHA will be shown.
| 'login_attempt_expire' = Time to live for every attempt to login. Default is 24 hours (60*60*24).
|--------------------------------------------------------------------------
 */

$config['login_by_guest_public_key'] = '5bbed0325e01a3bb1856b1ad59867f30';
$config['login_by_guest_prefix'] = 'guest_';
$config['login_by_guest_username_prefix'] = 'ySGEGRBQlXVWIsOK';
$config['login_by_guest_key'] = 'InuLTr2IkJHUW8iy';

/* End of file auth.php */
/* Location: ./application/config/auth.php */