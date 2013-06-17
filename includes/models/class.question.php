<?php
/**
 * Class-model for question in the quest. Contains data like level id, task, answer, order id, quest id etc.
 * Can load data from DB or from provided array
 *
 * @uses db
 * @package KQC
 * @author KronuS
 * @version 1.0
 */
class question {
    const CREATING_CANT_CHECK_QUESTION_EXISTING = 100;
    const CREATING_CANT_ADD_QUESTION = 300;
    const CREATING_COMPLETE = 400;
	/**
     * Level id
     * @acces private
	 * @var int
	 */
	private $_l_id;

	/**
     * Level task
     * @access private
	 * @var string
	 */
	private $_task;

	/**
     * Level answer
     * @access private
	 * @var string
	 */
	private $_answer;

	/**
     * qid of quest where this level is
     * @access private
	 * @var int
	 */
	private $_quest_id;

	/**
     * Level order id in the quest
     * @access private
	 * @var int
	 */
	private $_order_id;

	/**
     * Hint for level
     * @access private
	 * @var string
	 */
	private $_hint;

    /**
     * Is question valid or not
     * @access private
     * @var bool
     */
    private $_is_valid;

    /**
     * Set level task
     * @access public
     * @param string $task
     */
    public function set_task($task) {
		$this->_task = (string)$task;
	}

    /**
     * Get level task
     * @access public
     * @return string
     */
    public function get_task() {
		return $this->_task;
	}

    /**
     * Get short part of the level task
     * @access public
     * @return string
     */
    public function get_short_task() {
        $task = strip_tags($this->get_task());
        return substr($task, 0, strpos($task, ' ', 150));
    }

    /**
     * Set level answer
     * @access public
     * @param string $answer
     */
    public function set_answer($answer) {
		$this->_answer = (string)$answer;
	}

    /**
     * Get level answer
     * @access public
     * @return string
     */
    public function get_answer() {
		return $this->_answer;
	}

    /**
     * Get level hint
     * @access public
     * @return string
     */
    public function get_hint() {
		return $this->_hint;
	}

    /**
     * Set level hint
     * @access public
     * @param string $hint
     */
    public function set_hint($hint) {
		$this->_hint = (string)$hint;
	}

    /**
     * Get level quest id
     * @access public
     * @return int
     */
    public function get_qid() {
		return $this->_quest_id;
	}

    /**
     * Set level quest id
     * @access public
     * @param int $qid
     */
    public function set_qid($qid) {
		$this->_quest_id = intval($qid);
	}

    /**
     * Get level order id
     * @access public
     * @return int
     */
    public function get_oid() {
		return $this->_order_id;	
	}

    /**
     * Set level order id
     * @access public
     * @param int $oid
     */
    public function set_oid($oid) {
		$this->_order_id = intval($oid);
	}

    /**
     * Get level id
     * @access public
     * @return int
     */
    public function get_lid() {
		return $this->_l_id;	
	}

    /**
     * Get question status - valid or not (this flag is based on loaded question data by its lid)
     * @access public
     * @return bool
     */
    public function is_valid() {
        return $this->_is_valid;
    }

    /**
     * Set question status - valid or not
     * @access public
     * @param bool $v
     */
    public function set_valid($v) {
        $this->_is_valid = (bool)$v;
    }

    /**
     * Basic construct, based on level id
     * @access public
     * @param int $l_id level id
     */
    public function __construct($l_id) {
		$this->_l_id = intval($l_id);
	}

    /**
     * Verify provided answer with level answer
     * If its not valid, save it to db
     * @access public
     * @param string $answer
     * @param int $uid
     * @return bool true - valid answer, false - not valid
     */
    public function validate_answer($answer, $uid) {
        $is_valid = (strtolower($this->get_answer()) === strtolower($answer));
        if (!$is_valid) {
            // Save invalid answer
            $db = db::obtain();
            $data = array(
                'uid' => intval($uid),
                'qid' => $this->get_qid(),
                'lid' => $this->get_lid(),
                'answer' => $answer
            );
            $db->insert_pdo(db::real_tablename('provided_answers'), $data);
        }
        return $is_valid;
    }
	/**
	 * Save level info
	 */
	public function save() {
        $db = db::obtain();
        $data = $this->fields_to_array();
        unset($data['lid']);
        $where = array(
            'lid' => $this->get_lid()
        );
        $r = $db->update_pdo(db::real_tablename('levels'), $data, $where);
        echo $db->get_error();
        return $r;
	}
	/**
	 * Load level data from DB
     * @access public
	 */
	public function load() {
		$db = db::obtain();
		$query = "SELECT * FROM ".db::real_tablename('levels')." WHERE lid=?";
		$w = array(
			'lid' => $this->_l_id
		);
		$r = $db->query_first_pdo($query, $w);
		if ($r === false) {
            $this->set_valid(false);
			return false;
		}
        $this->set_valid(true);
		$this->set_data($r);
        return true;
	}
	/**
	 * Set level data
	 * @access public
	 * @param array $data Array with level info (qid, task, hint, answer, oid)
	 */
	public function set_data(array $data) {
        if (!is_null($data['qid'])) $this->set_qid($data['qid']);
        if (!is_null($data['task'])) $this->set_task($data['task']);
		if (!is_null($data['hint'])) $this->set_hint($data['hint']);
		if (!is_null($data['answer'])) $this->set_answer($data['answer']);
		if (!is_null($data['oid'])) $this->set_oid($data['oid']);
	}

    /**
     * Implode object fields to assoc array
     * @access public
     * @return array
     */
    public function fields_to_array() {
        return array(
            'qid' => $this->get_qid(),
            'lid' => $this->get_lid(),
            'oid' => $this->get_oid(),
            'task' => $this->get_task(),
            'answer' => $this->get_answer(),
            'hint' => $this->get_hint()
        );
    }
}