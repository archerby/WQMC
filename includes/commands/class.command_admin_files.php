<?php

/**
 *
 */
class command_admin_files extends command {
    /**
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $user = new user();
        if ($user->is_user_valid()) {
            if ($user->get_permission('have_admin_access') && ($user->get_permission('can_upload_file') || $user->get_permission('can_delete_file'))) {
                if (isset($_FILES['file']) && is_array($_FILES['file']) && $user->get_permission('can_upload_file')) {
                    $uploader = new uploader(config::get('base_path').config::get('uploads_path'), 'file');
                    if ($uploader->upload_file()) {

                    }
                    else {

                    }
                    app::redirect('?cmd=admin_files');
                }
                $view = new view_admin_files();
                $view->set_user($user)->set_dir(config::get('uploads_path'))->set_files(scandir(config::get('base_path').DIRECTORY_SEPARATOR.config::get('uploads_path')))->render($request);
            }
        }
    }
}