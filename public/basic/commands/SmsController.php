<?php

namespace app\commands;

use common\models\Message;
use Yii;
use yii\console\Controller;

/**
 */
class SmsController extends Controller
{
    public function actionIndex($tel = '+77057624197', $text = 'Привет!')
    {
        $sms = Yii::$app->sms->send($tel, $text);
        var_dump($sms);

        // Обновить статус
        Yii::$app->sms->status($sms);
        var_dump($sms);
    }
}
