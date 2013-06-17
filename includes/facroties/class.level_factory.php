<?php
/**
 * Quest factory for deleting and creating new quests
 *
 * @uses db
 * @package KQC
 * @author KronuS
 * @version 1.0
 */
class level_factory {
    public function __construct() {}

    /**
     * @param $qid
     * @param $task
     * @param $hint
     * @param $answer
     * @param $oid
     * @return int
     */
    public function create_new_level($qid, $task, $hint, $answer, $oid) {
        if(is_null($qid) || is_null($task) || is_null($hint) || is_null($answer) || is_null($oid)) return question::CREATING_CANT_CHECK_QUESTION_EXISTING;
        $db = db::obtain();
        $data = array(
            'qid' => intval($qid),
            'task' => (string)$task,
            'hint' => (string)$hint,
            'answer' => (string)$answer,
            'oid' => intval($oid)
        );
        $tmp_int_last_id = $db->insert_pdo(db::real_tablename('levels'), $data);
        // Insert error
        if ($tmp_int_last_id === false) {
            return question::CREATING_CANT_ADD_QUESTION;
        }
        return question::CREATING_COMPLETE;
    }
    /**
     * Delete question from database by id (delete data from levels table)
     * @access public
     * @param int $lid
     * @return mixed
     */
    public function delete_level($lid) {
        $lid = intval($lid);
        $db = db::obtain();
        $where = array(
            'lid' => $lid
        );
        return $db->delete_pdo(db::real_tablename('levels'), $where);
    }
}