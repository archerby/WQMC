<?php
/**
 * Admin section. 'Delete file' controller
 *
 * @author KronuS
 * @version 1.0
 */
class command_admin_delete_file extends command {
    /**
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $user = new user();
        if ($user->is_user_valid()) {
            if ($user->get_permission('have_admin_access') && $user->get_permission('can_delete_file')) {
                $file = $request->get('file');
                if (is_string($file)) {
                    $file = str_replace(array('..', '\\', '/'), '', $file);
                    $file = config::get('base_path').config::get('uploads_path').$file;
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }
        }
        app::redirect('?cmd=admin_files');
    }
}