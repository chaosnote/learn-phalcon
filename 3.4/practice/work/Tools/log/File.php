<?PHP

// 非 Phalcon 框架時使用

namespace Tools\Log ;

/**
 * @property string $dir_path
 */
class File
{
    public function __construct(string $default_dir = "/home/www-data/") {
        $this->dir_path = $default_dir;
    }
    /**
     * 寫入資訊
     * @param string $message
     * @param string $file_name
     * @param string $title
     * @return void
     */
    public function Write($message,$file_name,$title = 'title')
    {
        $parent = date('Y-m-d');
        $dist =  sprintf("%s/%s/%s.txt", $this->dir_path, $parent, $file_name) ;

        $dir_name = dirname($dist); //取出目錄路徑中目錄(不包括後面的檔案)        
        if(!file_exists($dir_name)) {
            $flag = mkdir($dir_name,0777,true);
            if(!$flag) {
                throw new \Exception(sprintf("can't build %s", $dir_name));
            }
        }
        $fp = fopen($dir_name, 'a');
        fwrite($fp, " ---------------- $title ---------------- \n");
        fwrite($fp, " LOG time=>".date('Y-m-d H:i:s',time())." ".substr((string)microtime(true),-4)." \n");
        fwrite($fp, $message."\n");
        fclose($fp);
    }
}