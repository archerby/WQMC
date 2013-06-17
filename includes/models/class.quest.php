<?php
/**
 * Class-model for quest. Contains main properties like qid, name, list of levels, theme, list of winners etc.
 * Used lazy load for winners and levels access.
 * Can load data from DB or array of data.
 *
 * @uses db
 * @package KQC
 * @author KronuS
 * @version 1.0
 */
class quest {
    const CREATING_CANT_CHECK_QUEST_EXISTING = 100;
    const CREATING_CANT_ADD_QUEST = 300;
    const CREATING_COMPLETE = 400;
	/**
	 * Quest ID
     * @access private
	 * @var int
	 */
	private $_qid;
	/**
	 * Quest name
     * @access private
	 * @var string
	 */
	private $_name;
	/**
	 * Is quest open or closed
     * @access private
	 * @var bool
	 */
	private $_is_open;
	/**
	 * Array of quest's levels
     * @access private
	 * @var array
	 */
	private $_levels;
    /**
     * Quest theme (dirname)
     * @access private
     * @var string
     */
    private $_theme;
	/**
	 * Quest's intro
     * @access private
	 * @var string
	 */
	private $_intro;
	/**
	 * Quest's outro
     * @access private
	 * @var string
	 */
	private $_outro;
	/**
	 * Is quest valid or not
     * @access private
	 * @var bool
	 */
	private $_is_valid;
    /**
     * Information about winners
     * @access private
     * @var array
     */
    private $_winners;

    /**
     * Count of users in the each level
     * @access private
     * @var array
     */
    private $_users;

    /**
     * Get quest ID
     * @access public
     * @return int
     */
    public function get_qid() {
		return $this->_qid;
	}

    /**
     * Set quest ID
     * @access public
     * @param int $qid
     */
    public function set_qid($qid) {
		$this->_qid = intval($qid);
	}

    /**
     * Get quest name
     * @access public
     * @return string
     */
    public function get_name() {
		return $this->_name;
	}

    /**
     * Set quest name
     * @access public
     * @param string $qname
     */
    public function set_name($qname) {
		$this->_name = (string)$qname;
	}

    /**
     * Check if quest is open for users
     * @access public
     * @return bool
     */
    public function get_is_open() {
		return $this->_is_open;
	}

    /**
     * Set flag for open quest for users
     * @access private
     * @param bool $qisopen
     */
    private function _set_is_open($qisopen) {
		$this->_is_open = (bool)$qisopen;
	}

    /**
     * Open quest for users
     * @access public
     */
    public function open_quest() {
		$this->_set_is_open(true);
	}

    /**
     * Close quest for users
     * @access public
     */
    public function close_quest() {
		$this->_set_is_open(false);
	}

    /**
     * Get quest intro
     * @access public
     * @return string
     */
    public function get_intro() {
		return $this->_intro;
	}

    /**
     * Set quest intro
     * @access public
     * @param string $intro
     */
    public function set_intro($intro) {
		$this->_intro = (string)$intro;
	}

    /**
     * Get quest outro
     * @access public
     * @return string
     */
    public function get_outro() {
		return $this->_outro;
	}

    /**
     * Set quest outro
     * @access public
     * @param string $outro
     */
    public function set_outro($outro) {
		$this->_outro = (string)$outro;
	}

    /**
     * Set quest theme (theme name is a dirname of theme in folder "themes")
     * @access public
     * @param string $theme
     */
    public function set_theme($theme) {
        $this->_theme = (string)$theme;
    }

    /**
     * Get theme name
     * @access public
     * @return string
     */
    public function get_theme() {
        return $this->_theme;
    }

    /**
     * Get array of users that finished quest
     * @access public
     * @return array
     */
    public function get_winners() {
        if (is_null($this->_winners)) {
            $this->_winners = $this->_load_winners();
        }
        return $this->_winners;
    }

    /**
     * Get array of users (level => count)
     * @access public
     * @return array
     */
    public function get_users() {
        if (is_null($this->_users)) {
            $this->_users = $this->_load_users();
        }
        return $this->_users;
    }

    /**
     * Get quest status - valid or not (this flag is based on loaded quest data by its qid)
     * @access public
     * @return bool
     */
    public function is_valid() {
		return $this->_is_valid;
	}

    /**
     * Set quest status - valid or not
     * @access public
     * @param bool $v
     */
    public function set_valid($v) {
		$this->_is_valid = (bool)$v;
	}
	/**
	 * Create empty quest
	 * @param int $qid quest id
	 */
	public function __construct($qid) {
		$this->set_qid(intval($qid));
	}

    /**
     * Set all quest data from provided array
     * Except: name, theme, intro, outro, is_open flag
     * @access public
     * @param array $data
     */
    public function set_all_data(array $data) {
        if (isset($data['name'])) {
            $this->set_name($data['name']);
        }
        if (isset($data['intro'])) {
            $this->set_intro($data['intro']);
        }
        if (isset($data['outro'])) {
            $this->set_outro($data['outro']);
        }
        if (isset($data['is_open'])) {
            if (strlen($data['is_open']) == 0) {
                $this->_set_is_open(1);
            }
            else {
                $this->_set_is_open($data['is_open']);
            }
        }
        else {
            $this->_set_is_open(0);
        }
        if (isset($data['theme'])) {
            $this->set_theme($data['theme']);
        }
    }

	/**
	 * Load quest data by its id
	 * @access public
	 */
	public function load() {
		$db = db::obtain();
		$params = array(
			$this->_qid
		);
		$query = "SELECT * FROM ".db::real_tablename('quests')." WHERE qid=?";
		$r = $db->query_first_pdo($query, $params);
		if ($r === false) {
			$this->set_valid(false);
			return;
		}
		$this->set_valid(true);
		$this->set_intro($r['intro']);
		$this->set_outro($r['outro']);
		$this->_set_is_open((bool)$r['is_open']);
		$this->set_name($r['name']);
        $this->set_theme($r['theme']);
		$this->_load_levels();
	}
	/**
	 * Save quest data (not levels) to database
	 * @access public
	 * @return bool
	 */
	public function save() {
		$db = db::obtain();
		$data = $this->fields_to_array();
		unset($data['qid']);
		$where = array(
			'qid' => $this->get_qid()
		);
        return $db->update_pdo(db::real_tablename('quests'), $data, $where);
	}
	/**
	 * Load info about quest winners
	 * @access private
	 * @return array
	 */
	private function _load_winners() {
		$db = db::obtain();
		$where = array(
			'qid' => $this->get_qid(),
			'current_level' => -1
		);
		$query = "SELECT u.uid AS uid, u.login AS name, uoq.qid AS qid, uoq.complete_timestamp AS complete_timestamp FROM ".db::real_tablename('users_on_quests')." AS uoq, ".db::real_tablename('users')." AS u WHERE qid=? AND current_level=? AND u.uid = uoq.uid ORDER BY uoq.complete_timestamp";
        return $db->fetch_array_pdo($query, $where);
	}

    /**
     * Load count of users for each quest's level
     * @access private
     * @return array
     */
    private function _load_users() {
        $db = db::obtain();
        $where = array(
            'qid' => $this->get_qid()
        );
        $query = "SELECT current_level, COUNT(*) AS count FROM ".db::real_tablename('users_on_quests')." WHERE qid=? GROUP BY current_level ORDER BY current_level";
        $r = $db->fetch_array_pdo($query, $where);
        $ret = array();
        foreach ($r as $l) {
            $ret[$l['current_level']] = $l['count'];
        }
        return $ret;
    }
	/**
	 * Check if quest is set by its id
	 * @access public
	 * @static
	 * @param int $id quest id
	 * @return bool true - quest is set, false - quest not set
	 */
	public static function isset_quest($id) {
		$id = intval($id);
		$db = db::obtain();
		$query = "SELECT name FROM ".db::real_tablename('quests')." WHERE qid=?";
		$w = array(
			'qid' => $id
		);
		$r = $db->query_first_pdo($query, $w);
		return (bool)$r;
	}
	/**
	 * Get level by its id 
	 * @param int $level_id level id
	 * @return mixed question - if level exists, false - if level doesn't exist
	 */
	public function get_level($level_id) {
		$this->_load_levels();
		if (!isset($this->_levels[$level_id])) {
			return false;
		}
		return $this->_levels[$level_id];
	}
	/**
	 * Check if level with such id exists in current quest
	 * @param int $level_id level id
	 * @return bool true - level exists, false - level doesn't exist
	 */
	public function isset_level($level_id) {
		$this->_load_levels();
		$level_id = intval($level_id);
		foreach ($this->_levels as $k=>$level) {
			if ($level->get_lid() == $level_id) {
				return true;
			}
		}
		return false;
	}
	/**
	 * Load quest's levels
	 * @access private
	 * @return bool true - load successful, false - load failed
	 */
	private function _load_levels() {
		if (!is_null($this->_levels)) {
			return true;
		}
		$db = db::obtain();
		$query = "SELECT * FROM ".db::real_tablename('levels')." WHERE qid=? ORDER BY oid";
		$params = array(
			$this->get_qid()
		);
		$r = $db->fetch_array_pdo($query, $params);
		if ($r === false) {
			return false;
		}
		$this->_levels = array();
		foreach($r as $k=>$l) {
			$this->_levels[$l['lid']] = new question($l['lid']);
			$this->_levels[$l['lid']]->set_data($l);
		}
		return true;
	}
	/**
	 * Get level data by its order count (NOT oid!)
	 * @access public
	 * @param $offset int
	 * @return question
	 */
	public function get_level_by_offset($offset) {
		$offset = intval($offset);
		$count = count($this->_levels);
		if ($offset < 1 || $offset > $count) {
			return null;
		}
		for ($i = 1; $i <= $count; $i++) {
			if ($i === $offset) {
				return current($this->_levels);
			}
			next($this->_levels);
		}
		return null;
	}
	/**
	 * Get array of quest's levels
	 * @access public
	 * @return array
	 */
	public function get_levels() {
		return $this->_levels;
	}
	/**
	 * Get levels count in current quest
	 * @return int levels count
	 */
	public function get_levels_count() {
		$this->_load_levels();
		return count($this->_levels);
	}
	/**
	 * Implode object fields to assoc array
	 * @access public
	 * @return array
	 */
	public function fields_to_array() {
		return array(
			'qid' => $this->get_qid(),
			'name' => $this->get_name(),
			'intro' => $this->get_intro(),
			'outro' => $this->get_outro(),
			'is_open' => intval($this->get_is_open()),
            'theme' => $this->get_theme()
		);
	}
}