<?PHP
/**
 *
 */
class uploader {

    /**
     * @type string
     * @access private
     */
    private $_upload_dir;
    /**
     * @type string
     * @access private
     */
    private $_file_array_index;
    /**
     * @type string
     * @access private
     */
    private $_file_name;
    /**
     * @param string $dir папка, в которую сохранять файлы
     * @param string $index идентификатор файла в массиве $_FILES
     * @access public
     */
    public function __construct($dir, $index) {
        echo $dir;
        $this->_upload_dir = $dir;
        $this->_file_array_index = $index;
    }
    /**
     * Переопределение индекса массива $_FILES
     * @access public
     * @param string $index индекс
     * @return null
     */
    public function set_file_array_index($index) {
        if (is_string($index)) {
            $this->_file_array_index = $index;
        }
    }
    /**
     * Возвращает индекс массива $_FILES
     * @access public
     * @return string
     */
    public function get_file_array_index() { return $this->_file_array_index; }
    /**
     * Проверка директории, в которую будет перемещен загружаемый файл
     * @access private
     * @return bool
     */
    private function _check_upload_dir() {
        if (!is_dir($this->_upload_dir)) return false;
        if (!is_writable($this->_upload_dir)) return false;
        return true;
    }
    /**
     * Проверка загружаемого файла
     * @access private
     * @return bool
     */
    private function _check_file() {
        $this->_file_name = basename($_FILES[$this->_file_array_index]['name']);
        if (trim($this->_file_name) === '') {
            return false;
        }
        if ($_FILES[$this->_file_array_index]['size'] <= 0) {
            return false;
        }
        return true;
    }
    /**
     * Перемещение файла из временного каталога в $this->_upload_dir
     * @access public
     * @return bool
     */
    public function upload_file()
    {
        if (!$this->_check_file()) {
            return false;
        }
        if (!$this->_check_upload_dir()) {
            return false;
        }
        if (!move_uploaded_file($_FILES[$this->_file_array_index]['tmp_name'], $this->_upload_dir.DIRECTORY_SEPARATOR.preg_replace('/\s+/','_',$this->_file_name))) {
            return false;
        }
        return true;
    }
}

