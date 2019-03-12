<?php
namespace app\demo;

use think\Exception;
/**
 * Created by PhpStorm.
 * User: Mikou
 * Date: 2017/12/29 0029
 * Time: 14:32
 */
/**
 * PHPMailer exception handler
 * @package PHPMailer
 */
class PhpmailerException extends Exception
{
    /**
     * Prettify error message output
     * @return string
     */
    public function errorMessage()
    {
        $errorMsg = '<strong>' . $this->getMessage() . "</strong><br />\n";
        return $errorMsg;
    }
}