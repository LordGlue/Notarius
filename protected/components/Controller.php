<?php

/**
 * Оснвной контроллер
 * Все контроллеры наследуются от него
 *
 */
class Controller extends CController
{

    /**
     * Настройки layout
     * Используемые свойства:
     * <code>
     * $layout_options['show_tree'] = true; - включаем отображение дерева
     * $layout_options['fluid'] = true; -  включаем резину
     * </code>
     */
    public $layout_options = array();
    /**
     * @var int
     */
    public $pageSize = 10;

    /**
     * @var array
     */
    public $menu = array();
    /**
     * @var array
     */
    public $breadcrumbs = array();


    /**
     *
     */
    public function getAuth()
    {
        if (Yii::app()->user->isGuest) {
            Yii::app()->request->redirect(Yii::app()->urlManager->createUrl('/site/login'));
        }
    }

    /**
     * Проверяет переданы ли необходимые данные.
     * Если не переданы - выдает ошибку
     *
     * @param array $data массив имен ключей массива $_REQUEST, которые нужно проверить
     */
    public function checkRequiredData($data = array())
    {
        if (is_array($data)) {
            foreach ($data as $element) {
                if (!isset($_REQUEST[$element])) {
                    $this->throwException('Не переданы данные ' . $element, 500);
                }
            }
        }
    }


    /* Проверяет запрос на "Аяксовость"
    *
    * @param bool $message - сообщение пользователю заплутавшему в системе и решившему пошалить в строке браузера (необязательно)
    * @throws CHttpException - эксепшн гласящий - "Вали отсюда по добру по здорову и возвращайся с АЯКСОМ!!!"
    */
    public function checkAjax($message = false)
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException(403, $message ? $message : 'Доступ открыт только для AJAX запросов');
        }
    }

    /**
     * Проверяет запрос на "Аяксовость"
     */
    public function isAjax()
    {
        return Yii::app()->request->isAjaxRequest;
    }

    /**
     * Кидает пользователю исключение.
     * Если запрос аяксовый - кидает аяксовое исключение,
     * Если не аякс - кидает простое исключение...
     *
     * @param string $message - сообщение об ошибке
     * @param int $code - код ошибки
     * @throws CHttpException
     */
    public function throwException($message = 'Что-то сломалось...', $code = 500)
    {
        // Если это аяксовый запрос - кидает json
        if (Yii::app()->getRequest()->isAjaxRequest) {
            echo CJSON::encode(
                array(
                    'status' => $code,
                    'message' => $message
                )
            );
            Yii::app()->end();
        } else {
            // Ну а если обычный - кидаем эксепшн
            throw new CHttpException($code, $message, $code);
        }
    }


    /**
     * Показывает пользователю сообщение системы.
     *
     * @param string $message
     * @param array $additional_data
     * @param string $type
     */
    public function showMessage($message = 'Операция прошла успешно', $additional_data = array(), $type = 'message')
    {
        $data = CMap::mergeArray(
            $additional_data,
            array(
                'status' => 200,
                $type => $message
            )
        );
        // Если это аяксовый запрос - кидает json
        if (Yii::app()->getRequest()->isAjaxRequest) {
            echo CJSON::encode($data);
            Yii::app()->end();
        } else {
            // Ну а если обычный - кидаем флеш
            Yii::app()->user->setFlash('success', $message);
        }
    }

    /**
     * Сохраняет переданную модель в базу,
     * с выводом сообщения об ошибке, если что-то не так
     * Если задано сообщение, то заканчивает скрипт выводом сообщения
     *
     * @param $model ActiveRecord модель для сохранения
     * @param string $message [optional] сообщение для вывода пользователю
     * @param array $additional_data [optional] дополнительные данные для передачи их вместе с сообщением
     */
    public function saveModel($model, $message = "", $additional_data = array())
    {
        if ($model->save()) {
            if (!empty($message)) {
                $this->showMessage($message, $additional_data);
            }
        } else {
            $this->throwException(CHtml::errorSummary($model), 402);
        }
    }


    /**
     * @return array
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }


    /**
     * @return string
     */
    public function getUserRole()
    {
        static $role;
        if (!$role) {
            $role = Yii::app()->user->role;
        }
        return $role;
    }


    /**
     * @param $number
     * @param bool $notMobile
     * @return bool|mixed|string
     */
    public function checkPhoneNumber($number, $notMobile = false)
    {
        /* функции проверки номера сотового телефона */
        $p = '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/';
        if (!preg_match($p, $number)) {
            return false;
        }
        $number = preg_replace("/\D/", '', $number);
        if ($notMobile) {
            return $number;
        }
        $first = substr($number, 0, 1);
        if ($first == 7) {
            $number = substr($number, 1, strlen($number));
        }
        $number = substr($number, strlen($number) - 10);
        if (!preg_match("/^[0-9]{10,10}+$/", $number)) {
            return false;
        }
        // Добавляем семерку к номеру телефону, если мы рассылаем по России.
        $number = "+7" . $number;
        $first = substr($number, "0", 2);
        if ($first != '+7') {
            return false;
        }

        return $number;
    }


    /**
     * Encrypt using 3DES
     *
     * @param string $clear clear text input
     *
     * @return string encrypted text
     */
    public function encrypt($clear)
    {
        if (!$clear) {
            return '';
        }
        $cipher = '';
        /* -
         * Add a single canary byte to the end of the clear text, which
         * will help find out how much of padding will need to be removed
         * upon decryption; see http://php.net/mcrypt_generic#68082
         */
        $clear = pack("a*H2", $clear, "80");
        if (function_exists('des')) {
            define('DES_IV_SIZE', 8);
            $iv = '';
            for ($i = 0; $i < constant('DES_IV_SIZE'); $i++) {
                $iv .= sprintf("%c", mt_rand(0, 255));
            }
            $cipher = $iv . des("sdf345dsf32", $clear, 1, 1, $iv);
        } else {
            $this->throwException(
                "Could not perform decryption; make sure Mcrypt is installed or lib/des.inc is available",
                500
            );
        }

        return base64_encode($cipher);
    }

    /**
     * Decrypt 3DES-encrypted string
     *
     * @param string $cipher encrypted text
     *
     * @return string decrypted text
     */
    public function decrypt($cipher)
    {
        if (!$cipher) {
            return '';
        }
        $clear = "";
        $cipher = base64_decode($cipher);
        if (function_exists('des')) {
            define('DES_IV_SIZE', 8);
            $iv = substr($cipher, 0, constant('DES_IV_SIZE'));
            $cipher = substr($cipher, constant('DES_IV_SIZE'));
            $clear = des("sdf345dsf32", $cipher, 0, 1, $iv);
        } else {
            $this->throwException(
                "Could not perform decryption; make sure Mcrypt is installed or lib/des.inc is available",
                500
            );
        }

        $clear = substr(rtrim($clear, "\0"), 0, -1);

        return $clear;
    }

    /**
     * Отправяет почту
     *
     * @param string $to Кому
     * @param string $layout В каком стиле
     * @param string $view путь к вьюъе
     * @param array $data данные для вьюхи
     * @param string $subject Тема письма
     * @param string $from от кого
     * @return bool отправили или нет
     */
    public function SendEmail($to, $layout, $view, $data, $subject, $from = 'no-reply@ezvuk.ru')
    {

        Yii::app()->mailer->IsSMTP();
        Yii::app()->mailer->Host = 'zimbra.ezvuk.ru';
        Yii::app()->mailer->SMTPDebug = 0;
        Yii::app()->mailer->Username = "no-reply@ezvuk.ru";
        Yii::app()->mailer->Password = "testtest";
        Yii::app()->mailer->CharSet = 'utf-8';
        Yii::app()->mailer->ContentType = 'text/html';
        Yii::app()->mailer->From = $from;
        Yii::app()->mailer->FromName = 'ЕВРОЗВУК';
        if (is_array($to) && count($to)) {
            foreach ($to as $addr) {
                Yii::app()->mailer->AddAddress($addr);
            }
        } else {
            Yii::app()->mailer->AddAddress($to);
        }
        Yii::app()->mailer->Subject = $subject;
        Yii::app()->mailer->GetView($view, $data, $layout, $html = true);
        if (Yii::app()->mailer->Send()) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Инициализация кнотроллера
     */
    public function init()
    {
        parent::init();
    }

}