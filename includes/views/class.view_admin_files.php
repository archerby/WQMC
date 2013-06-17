<?php
/**
 *
 */
class view_admin_files extends view_admins {
    /**
     * @var array
     */
    protected $_files;
    /**
     * @var string
     */
    protected $_dir;
    /**
     * @param array $files
     * @return view_admin_files
     */
    public function set_files(array $files) {
        $this->_files = $files;
        return $this;
    }

    /**
     * @param string $dir
     * @return view_admin_files
     */
    public function set_dir($dir) {
        $this->_dir = (string)$dir;
        return $this;
    }
    /**
     * @param request $request
     * @return mixed|void
     */
    public function _do_render(request $request) {
        $this->_title = 'Files';
        if (is_null($this->_user)) {
            echo __CLASS__.' user not set.';
            return;
        }
        if  ($this->_user->get_permission('can_upload_file')) {
            require_once('forms/upload.php');
        }
        echo '<ul>';
        foreach($this->_files as $file) {
            if ($file === '.' || $file === '..' || $file === 'index.html') {
                continue;
            }
            echo '<li><a href="'.ltrim(str_replace('\\', '/', $this->_dir), '/').$file.'">'.$file.'</a>';
            if ($this->_user->get_permission('can_delete_file')) {
                echo ' (<a onclick="return confirm(\'Realy do this?\');" href="?cmd=admin_delete_file&amp;file='.$file.'">Delete</a>)';
            }
            echo '</li>';
        }
        echo '</ul>';
    }
}