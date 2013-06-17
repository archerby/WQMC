<?php
/**
 * User factory for registration, deleting and creating new users
 *
 * @uses db
 * @package KQC
 * @author KronuS
 * @version 1.0
 */
class user_factory {
	public function __construct() {}
	/**
	 * Create new user
	 * @access public
	 * @return user
	 */
	public function create_user() {
		return new user();
	}
	/**
	 * New user registration
	 * @access public
	 * @param string $login user login
	 * @param string $password user password
	 * @return int registration status
	 */
	public function reg_new_user($login, $password) {
		if(is_null($login) || is_null($password)) return user::REG_CANT_CHECK_USER_EXISTING;
		$login = (string)$login;
		$password = (string)$password;
        if (trim($login) === '' || trim($password) === '') return user::REG_CANT_CHECK_USER_EXISTING;
        $db = db::obtain();
		$params = array(
			$login
		);
		$query = "SELECT * FROM ".db::real_tablename('users')." WHERE login=?"; 
		$r = $db->query_first_pdo($query, $params);
		// Such login already exists
		if ($r !== false && $r['login'] === $login) {
			return user::REG_USER_ALREADY_EXISTS;
		}
		$salt = user::gen_salt();
		$data = array(
			'login' => $login,
			'password' => user::gen_hash($password, $salt),
			'salt' => $salt
		);
		$tmp_int_last_id = $db->insert_pdo(db::real_tablename('users'), $data);
		// Insert error
		if ($tmp_int_last_id === false) {
			return user::REG_CANT_ADD_USER;
		}
		return user::REG_COMPLETE;
	}
	/**
	 * Delete user from database by id (delete data from sessions and users tables)
	 * @access public
	 * @param int $uid
	 * @return mixed
	 */
	public function delete_user($uid) {
		$uid = intval($uid);
		$db = db::obtain();
		$where = array(
			'uid' => $uid
		);
		return $db->delete_pdo(db::real_tablename('sessions'), $where) && $db->delete_pdo(db::real_tablename('users'), $where);
	}
}