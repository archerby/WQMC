<?php
/**
 * Quest factory for deleting and creating new quests
 *
 * @uses db
 * @package KQC
 * @author KronuS
 * @version 1.0
 */
class quest_factory {
    public function __construct() {}
    /**
     * Create new quest
     * @param string $name
     * @param string $intro
     * @param string $outro
     * @param string $theme
     * @param bool $is_open
     * @return int
     */
    public function create_new_quest($name, $intro, $outro, $theme, $is_open) {
        if(is_null($name) || is_null($intro) || is_null($theme) || is_null($outro) || is_null($is_open)) return quest::CREATING_CANT_CHECK_QUEST_EXISTING;
        $db = db::obtain();
        $data = array(
            'name' => (string)$name,
            'intro' => (string)$intro,
            'outro' => (string)$outro,
            'theme' => (string)$theme,
            'is_open' => (bool)$is_open
        );
        $tmp_int_last_id = $db->insert_pdo(db::real_tablename('quests'), $data);
        // Insert error
        if ($tmp_int_last_id === false) {
            return quest::CREATING_CANT_ADD_QUEST;
        }
        return quest::CREATING_COMPLETE;
    }
    /**
     * Delete quest from database by id (delete data from users_on_quest, questions and quests tables)
     * @access public
     * @param int $qid
     * @return mixed
     */
    public function delete_quest($qid) {
        $qid = intval($qid);
        $db = db::obtain();
        $where = array(
            'qid' => $qid
        );
        return $db->delete_pdo(db::real_tablename('users_on_quests'), $where) && $db->delete_pdo(db::real_tablename('questions'), $where) && $db->delete_pdo(db::real_tablename('quests'), $where);
    }
}