<?php
/**
 * SMS Sender component for yii2
 * @author Anton Usyuzhanin <antonu17@gmail.com>
 * @version 0.1
 */

namespace common\components;


use common\models\Message;
use Yii;
use yii\base\Component;
use yii\base\ErrorException;

class Command
{
    const SEND = 'send';
    const STATUS = 'status';
}

class Sms extends Component
{

    /**
     * @var string SMS Gate host
     */
    public $host = 'gate.iqsms.ru';

    /**
     * @var string SMS Gate port
     */
    public $port = 80;

    /**
     * @var string SMS Gate account login
     */
    public $login;

    /**
     * @var string SMS Gate account password
     */
    public $password;

    /**
     * @var string SMS Gate sender
     */
    public $sender;

    /**
     * Send SMS message
     * @param $tel
     * @param $text
     * @return \common\models\Message $message Message model
     * @throws ErrorException
     * @see http://iqsms.ru/api/api_rest/
     */
    function send($tel, $text)
    {
        $message = new Message($tel, $text);
        $message->date = date("Y-m-d h:i:s");
        $message->sender = $this->sender;
        $message->userId = Yii::$app->user->id;

        $data = "phone=" . rawurlencode($message->tel) .
            "&text=" . rawurlencode($message->text) .
            ($this->sender ? "&sender=" . rawurlencode($this->sender) : "");

        // Получаем результат в формате SMSID=status
        $response = $this->_sendCommand(Command::SEND, $data);
        if (strpos($response, '=') === false) {
            throw new ErrorException("Bad response: " . $response);
        }
        $result = split("=", $response);

        // Сохраняем в модель ID сообщения
        $message->smsId = $result[0];

        // Сохраняем в модель статус сообщения
        $message->status = $result[1];

        return $message;
    }

    /**
     * Get status of SMS
     * @param \common\models\Message $message
     * @return \common\models\Message
     * @throws ErrorException
     * @see http://iqsms.ru/api/api_rest/
     */
    function status($message)
    {
        $data = "id=" . $message->smsId;

        // Получаем результат в формате SMSID=status
        $response = $this->_sendCommand(Command::STATUS, $data);
        if (strpos($response, '=') === false) {
            throw new ErrorException("Bad response: " . $response);
        }
        $result = split("=", $response);

        // Сохраняем в модель статус сообщения
        $message->status = $result[1];

        return $message;
    }

    /**
     * Send iqsms REST API command
     * @param string $cmd iqsms gateway command
     *                    Possible values: (send, status)
     * @param string $data query string for given command
     * @return string iqsms gateway response
     * @see http://iqsms.ru/api/api_rest/
     */
    function _sendCommand($cmd, $data)
    {
        $fp = fsockopen($this->host, $this->port, $errno, $errstr);
        if (!$fp) {
            return "errno: $errno \nerrstr: $errstr\n";
        }

        fwrite($fp, "GET /" . $cmd . "/?" . $data . " HTTP/1.0\n");
        fwrite($fp, "Host: " . $this->host . "\r\n");
        if ($this->login != "") {
            fwrite($fp, "Authorization: Basic " .
                base64_encode($this->login . ":" . $this->password) . "\n");
        }
        fwrite($fp, "\n");
        $response = "";
        while (!feof($fp)) {
            $response .= fread($fp, 1);
        }
        fclose($fp);
        list($other, $responseBody) = explode("\r\n\r\n", $response, 2);
        return $responseBody;
    }
}