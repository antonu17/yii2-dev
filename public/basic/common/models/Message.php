<?php
/**
 * SMS Message model class
 * @author Anton Usyuzhanin <antonu17@gmail.com>
 * @version 0.1
 */

namespace common\models;


class Message
{
    /**
     * @var string Date of sms message
     */
    public $date;

    /**
     * @var string Message owner
     */
    public $userId;

    /**
     * @var string Recipient phone number
     */
    public $tel;

    /**
     * @var string Message content
     */
    public $text;

    /**
     * @var string Message sender identification
     */
    public $sender;

    /**
     * @var string Message status
     * Possible values:
     * accepted                     Сообщение принято сервисом
     * invalid mobile phone         Неверно задан номер тефона (формат 71234567890)
     * text is empty                Отсутствует текст
     * sender address invalid       Неверная (незарегистрированная) подпись отправителя
     * wapurl invalid               Неправильный формат wap-push ссылки
     * invalid schedule time format Неверный формат даты отложенной отправки сообщения
     * invalid status queue name    Неверное название очереди статусов сообщений
     * not enough credits           Баланс пуст (проверьте баланс)
     */
    public $status;

    /**
     * @var string Iqsms Message id
     */
    public $smsId;

    /**
     * Message constructor.
     * @param string $tel
     * @param string $text
     */
    public function __construct($tel, $text)
    {
        $this->tel = $tel;
        $this->text = $text;
    }


}