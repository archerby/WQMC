<?php
/**
 * Class user contains info about current user like uid, login, password, sid etc.
 * Allows to load/save info about user, validate user login/logout, registration new user etc.
 *
 * @uses db
 * @package KQC
 * @author KronuS
 * @version 1.0
 */
class user {
	const REG_CANT_CHECK_USER_EXISTING = 100;
	const REG_USER_ALREADY_EXISTS = 200;
	const REG_CANT_ADD_USER = 300;
	const REG_COMPLETE = 400;
	/**
	 * 
	 * @access private
	 * @var string 
	 */
	private $_uid;
	private $_login;
	private $_password;
	private $_salt;
	private $_sid;
	private $_is_user_valid;
    private $_is_winner;
    private $_signed_quests;
    private $_token;
    private $_new_token;
    private $_current_qid;
    private $_permissions;
    private $_stats;
	
	public function get_uid() {
		return $this->_uid;
	}
	public function set_uid($uid) {
		$this->_uid = intval($uid);
	}
	
	public function get_login() {
		return $this->_login;
	}
	public function set_login($login) {
		$this->_login = (string)$login;
	}
	
	public function get_password() {
		return $this->_password;
	}
	public function set_password($pass) {
        if (!$this->is_user_valid()) {
            return;
        }
		$this->_password = (string)$pass;
	}

    public function get_signed_quests() {
        if (!is_array($this->_signed_quests)) {
            return $this->load_signed_quests();
        }
        return $this->_signed_quests;
    }

	public function get_salt() {
		return $this->_salt;
	}
	public function set_salt($salt) {
		$this->_salt = $salt;
	}
	
	public function is_user_valid() {
		return $this->_is_user_valid;
	}
	public function set_user_valid($fl) {
		$this->_is_user_valid = (bool)$fl;
	}
	
	public function get_sid() {
		return $this->_sid;
	}
	public function set_sid($sid) {
		$this->_sid = $sid;
	}

    public function get_current_qid() {
        return $this->_current_qid;
    }
    public function set_current_qid($qid) {
        $this->_current_qid = intval($qid);
        if ($this->get_signed_quests()) {
            $this->set_is_winner(($this->_signed_quests[$qid]['current_level'] == -1) ? true : false);
        }
    }

    public function get_is_winner() {
        return $this->_is_winner;
    }
    public function set_is_winner($f) {
        $this->_is_winner = (bool)$f;
        if ($f) {
            if (!is_null($this->get_current_qid()) && $this->get_signed_quests()) {
                $this->_signed_quests[$this->get_current_qid()]['current_level'] = -1;
            }
        }
    }

    protected function set_permissions(array $data) {
        $this->_permissions = $data;
    }
    public function get_permissions() {
        return $this->_permissions;
    }
    public function get_permission($key) {
        $key = (string)$key;
        if (!is_array($this->_permissions)) {
            return false;
        }
        if (!isset($this->_permissions[$key])) {
            return false;
        }
        return $this->_permissions[$key];
    }

    public function get_token() {
        return $this->_token;
    }
    public function set_token($token) {
        $this->_token = (string)$token;
    }
    public function get_new_token() {
        if (is_null($this->_new_token)) {
            $this->_new_token = self::gen_token();
        }
        return $this->_new_token;
    }
    public function set_new_token($new_token) {
        $this->_new_token = (string)$new_token;
    }

    public function get_stats() {
        if (!is_array($this->_stats)) {
            $this->_stats = $this->load_quests_stats();
        }
        return $this->_stats;
    }

    public function __construct($load_by_sid = true) {
        if (is_null($this->_is_user_valid)) {
            $this->set_sid(session_id());
            $this->load();
        }
	}

	/**
	 * Load info about user by session_id
	 * @access public
	 */
	public function load() {
        if (is_null($this->get_sid())) {
            $this->set_user_valid(false);
            return;
        }
		$db = db::obtain();
		$params = array(
			$this->get_sid()
			//sha1(user::get_ip())
		);
		$query = "SELECT `uid` FROM ".db::real_tablename('sessions')." WHERE sid=?"/*." AND ip_hash=?"*/;
		$r = $db->query_first_pdo($query, $params);
		if ($r === false) {
			$this->set_user_valid(false);
			return;
		}
		$this->set_uid($r['uid']);
		$query = "SELECT u.*, ur.*, us.token FROM ".db::real_tablename('users')." AS u, ".db::real_tablename('user_roles')." AS ur, ".db::real_tablename('sessions')." AS us WHERE u.uid=? AND u.rid = ur.rid AND us.uid = u.uid";

		$params = array(
			$this->get_uid()
		);
		$r = $db->query_first_pdo($query, $params);
		if ($r === false) {
			$this->set_user_valid(false);
			return;
		}
		$this->set_user_valid(true);
        $this->_set_fields($r);
	}
	/**
	 * User auth
	 * @param string $login
     * @param string $pass
	 * @return bool true - load complete, false - load failed
	 */
	public function auth($login, $pass) {
		$login = (string)$login;
        $pass = (string)$pass;
		$db = db::obtain();
		$params = array(
            $login
        );
		$query = "SELECT uid, login, password, salt FROM ".db::real_tablename('users')." WHERE login=?";
		$r = $db->query_first_pdo($query, $params);
		// Such login doesn't exist
		if ($r === false) {
			$this->set_user_valid(false);
			return false;
		}
        if ($r['password'] !== self::gen_hash($pass, $r['salt'])) {
            $this->set_user_valid(false);
            return false;
        }
        $this->_set_fields($r);
		$this->set_user_valid(true);
        $data = array(
            'uid' => $this->get_uid(),
            'sid' => $this->get_sid(),
            'ip_hash' => self::get_ip(),
            'token' => self::gen_token()
        );
        $db->delete_pdo(db::real_tablename('sessions'), array('uid'=>$this->get_uid()), 0);
        $db->insert_pdo(db::real_tablename('sessions'), $data);
        return true;
    }

    /**
     * Logout user
     * @access public
     * @return mixed int - valid logout, false - fail
     */
    public function logout() {
        $db = db::obtain();
        return $db->delete_pdo(db::real_tablename('sessions'), array('uid'=>$this->get_uid()), 0);
    }

    /**
     * Save user info into database
     * @access public
     * @return mixed
     */
    public function save() {
        if (!$this->is_user_valid()) {
            return;
        }
		$db = db::obtain();
        $data = array(
          'login' => $this->get_login(),
          'password' => $this->get_password()
        );
        $where = array(
            'uid' => $this->get_uid()
        );
        $db->update_pdo(db::real_tablename('users'), $data, $where);
        if (!is_null($this->get_current_qid())) {
            $signed_quests = $this->get_signed_quests();
            $c = $this->get_is_winner()? -1 : $signed_quests[$this->get_current_qid()]['current_level'];
            $data = array(
                'current_level' => $c
            );
            $where = array(
                'qid' => $this->get_current_qid(),
                'uid' => $this->get_uid()
            );
            $db->update_pdo(db::real_tablename('users_on_quests'), $data, $where);
        }
        $this->save_new_token();
    }

    /**
     * Generate and save new token for current user
     * @access public
     */
    public function save_new_token() {
        $db = db::obtain();
        if ($this->is_user_valid()) {
            $data = array(
                'token' => $this->get_new_token()
            );
            $where = array(
                'uid' => $this->get_uid()
            );
            $db->update_pdo(db::real_tablename('sessions'), $data, $where);
        }
    }

    /**
     * Validate provided token
     * @param $token string
     * @return bool true - valid, false - invalid
     */
    public function validate_token($token) {
        return !is_null($token) && (string)$token === $this->get_token();
    }

    /**
     * Set model field to values provided in the array
     * @access private
     * @param array $data
     */
    private function _set_fields(array $data) {
        if (isset($data['uid'])) {
            $this->set_uid($data['uid']);
            unset($data['uid']);
        }
        if (isset($data['login'])) {
            $this->set_login($data['login']);
            unset($data['login']);
        }
        if (isset($data['password'])) {
            $this->set_password($data['password']);
            unset($data['password']);
        }
        if (isset($data['salt'])) {
            $this->set_salt($data['salt']);
            unset($data['salt']);
        }
        if (isset($data['token'])) {
            $this->set_token($data['token']);
            unset($data['token']);
        }
        if (count($data) > 0) {
            $this->set_permissions($data);
        }
    }

    /**
     * @param $qid int quest id
     * @return bool - true - signed, false - not signed
     */
    public function is_signed_to_quest($qid) {
        $s = $this->get_signed_quests();
        foreach($s as $q_id=>$q_info) {
            if ($q_id === $qid) {
                return true;
            }
        }
        return false;
    }

    /**
     * Sign current user to quest
     * @param $qid int quest id
     * @return mixed
     */
    public function sign_to_quest($qid) {
        $qid = intval($qid);
        $data = array(
            'qid' => $qid,
            'uid' => $this->get_uid(),
            'current_level' => 1
        );
        $db = db::obtain();
        return $db->insert_pdo(db::real_tablename('users_on_quests'), $data);
    }

    /**
     * Get array of quests that user is signed (quest id, current level for each quest and complete time if user finish quest)
     * @access public
     * @return array
     */
    public function load_signed_quests() {
        $db = db::obtain();
        $tmp = $db->fetch_array_pdo('SELECT * FROM '.db::real_tablename('users_on_quests').' WHERE uid=?', array('uid' => $this->get_uid()));
        $this->_signed_quests = array();
        foreach($tmp as $v) {
            $this->_signed_quests[$v['qid']] = $v;
        }
        return $this->_signed_quests;
    }

    /**
     * Load current user quests stats
     * @access public
     * @return array
     */
    public function load_quests_stats() {
        $db = db::obtain();
        $tmp = $db->fetch_array_pdo('SELECT uoql.qid, uoql.lid, uoql.uid, uoql.date, q.name FROM '.db::real_tablename('users_on_quests_log').' AS uoql, '.db::real_tablename('quests').' AS q WHERE uoql.uid = '.$this->get_uid().' AND q.qid = uoql.qid ORDER BY qid DESC, lid ASC');
        $ret = array();
        if ($tmp) {
            foreach($tmp as $row) {
                if (!isset($ret[$row['qid']])) {
                    $ret[$row['qid']] = array();
                    $ret[$row['qid']]['name'] = $row['name'];
                }
                $ret[$row['qid']][$row['lid']] = $row['date'];
            }
        }
        return $ret;
    }

    /**
     * Set next level to user
     * @param $lid int level id
     * @access public
     */
    public function inc_current_level($lid) {
        $this->_signed_quests[$this->get_current_qid()]['current_level']++;
        $db = db::obtain();
        $data = array(
            'uid' => $this->get_uid(),
            'qid' => $this->get_current_qid(),
            'lid' => intval($lid)
        );
        $db->insert_pdo(db::real_tablename('users_on_quests_log'), $data);
        echo $db->get_error();
    }
	/**
	 * Generate random 10 symbols salt
	 * @access public
     * @static
	 * @return string salt
	 */
	public static function gen_salt() {
        return self::_gen_rand(10);
	}

    /**
     * Generate random 16 symbols token
     * @access public
     * @static
     * @return string token
     */
    public static function gen_token() {
        return self::_gen_rand(16);
    }

    /**
     * Generate random string
     * @access private
     * @param int $l length
     * @static
     * @return string
     */
    private static function _gen_rand($l) {
        $l = intval($l);
        $random = '';
        srand((double)microtime()*1000000);
        $char_list = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $char_list .= 'abcdefghijklmnopqrstuvwxyz';
        $char_list .= '1234567890';
        for($i = 0; $i < $l; $i++) {
            $random .= substr($char_list,(rand()%(strlen($char_list))), 1);
        }
        return $random;
    }
	/**
	 * Generate password 40 symbols hash
	 * @access private
	 * @param string $pass user password
	 * @param string $salt salt
	 * @return string hash
	 */
	public static function gen_hash($pass, $salt) {
		$pass = (string)$pass;
		$salt = (string)$salt;
		return sha1(sha1($pass).$salt);
	}
	/**
	 * Get user IP address
	 * @access public
	 * @static
	 * @return string 
	 */
	public static function get_ip() {
		foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
			if (array_key_exists($key, $_SERVER) === true) {
				foreach (explode(',', $_SERVER[$key]) as $ip) {
					if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
						return $ip;
					}
				}
			}
		}
		return '';
	}
}