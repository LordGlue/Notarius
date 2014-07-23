<?php

/**
 * Это класс модели для таблицы "cash".
 *
 * Ниже описаны доступные поля для таблицы 'cash':
 *
 * @property integer $id ПК кассы
 * @property string $name Название кассы
 * @property integer $user_id Пользователь кассы
 * @property double $balance Текущий баланс
 * @property integer $currency_id Тип валюты кассы
 * @property string $comment Комментарий кассы
 * @property string $created Дата создания
 * @property string $modified Дата изменения
 *
 *
 * Ниже описаны доступные для модели зависимости:
 * @property CashOperation[] $cashOperations
 * @property Users $user
 * *
 * @method Cash   find()        find($condition = '', $params = array())
 * @method Cash   findByPk()    findByPk($pk, $condition = '', $params = array())
 * @method Cash   findByAttributes() findByAttributes($attributes, $condition = '', $params = array())
 * @method Cash[] findAllByPk() findAllByPk($pk, $condition = '', $params = array())
 * @method Cash[] findAllByAttributes() findAllByAttributes($attributes, $condition = '', $params = array())
 * @method Cash[] findAll()     findAll($condition = '', $params = array())
 */
class Cash extends ActiveRecord
{

    /**
     * @var integer Валюта "рубль"
     */
    const CURRENCY_RUB = 0;

    /**
     * @var integer Валюта "литр"
     */
    const CURRENCY_LITRE = 1;


    /**
     * @return array
     */
    public function getCurrencies()
    {
        return array(
            $this::CURRENCY_RUB => 'руб.',
            $this::CURRENCY_LITRE => 'л.',
        );
    }

    /**
     * @return int получает валюту кассы
     */
    public function getCurrency()
    {
        $currencies = $this->getCurrencies();
        if (isset($currencies[$this->currency_id])) {
            return $currencies[$this->currency_id];
        } else {
            return $this->currency_id;
        }
    }

    /**
     * @var array $dates_for_convert - тут перечислены
     * атрибуты, типа DATE, TIMESTAMP.
     * Даты будут конвертироваться методами afterFind() и beforeSave()
     * в классе {@link ActiveRecord}
     *
     */
    public $dates_for_convert = array(
        'created',
        'modified'
    );

    /**
     * @var array $grid_multi_selects - тут перечислены
     * все атрибуты модели, которые должны иметь фильтр select2
     * Важно!!! Для каждого из перечисленных атрибутов должны быть
     * данные в методе getMultiSelectsData($attribute)
     */
    public $grid_multi_selects = array(
        'user_id',
        'currency_id'
    );

    /**
     * @return string возвращает сроку привязанной к модели таблицы
     */
    public function tableName()
    {
        return 'cash';
    }

    /**
     * @return array правила валидации для атрибутов модели
     */
    public function rules()
    {
        // NOTE: вам нужно лишь защитить атрибуты, которые будет вводить пользователь
        // можете удалить лишнее
        return array(
            array(
                'name, user_id, currency_id',
                'required'
            ),
            array(
                'user_id, currency_id',
                'numerical',
                'integerOnly' => true
            ),
            array(
                'balance',
                'numerical'
            ),
            array(
                'comment',
                'safe'
            ),
            array(
                'id, name, user_id, balance, currency_id, comment, created, modified',
                'safe',
                'on' => 'search'
            )

        );
    }

    /**
     * @return array правила зависимостей
     */
    public function relations()
    {
        return array(
            'operations' => array(
                self::HAS_MANY,
                'CashOperation',
                'cash_id'
            ),
            'user' => array(
                self::BELONGS_TO,
                'Users',
                'user_id'
            ),
        );
    }

    /**
     * @return array подписи атрибутов (атрибут=>подпись)
     */
    public function attributeLabels()
    {
        return array(
            'functions' => 'Функции',
            'id' => 'ПК кассы',
            'name' => 'Название кассы',
            'user_id' => 'Пользователь кассы',
            'balance' => 'Текущий баланс',
            'currency_id' => 'Тип валюты кассы',
            'comment' => 'Комментарий кассы',
            'created' => 'Дата создания',
            'modified' => 'Дата изменения'
        );
    }

    /**
     * @return array дефолтные атрибуты, выводящиеся в гриде
     */
    public function attributeDefault()
    {
        $attributes = array(
            'name',
            'balance',
        );
        /**
         * @var Controller $controller
         */
        $controller = Yii::app()->controller;
        if ($controller->allowed('indexAllCash')) {
            $attributes[] = 'user_id';
            $attributes[] = 'functions';
        }

        return $attributes;
    }


    /**
     * Метод, который отдает данные для фильтра типа мультиселект
     *
     * @param string $attribute атрибут модели, для которого нужно получить данные
     * @return boolean|array - возвращает массив данных или false, если не описано
     * получение данных или атрибут не присутствует в массиве $this->grid_multi_selects
     */
    public function getMultiSelectsData($attribute)
    {
        // Если мультиселект не описан - не нужно ничего проверять
        if (!in_array($attribute, $this->grid_multi_selects)) {
            return false;
        }
        switch ($attribute) {
            case 'user_id':
                $data = Users::techUsers();
                break;
            case 'currency_id':
                $data = $this->getCurrencies();
                break;
            default:
                $data = false;
                break;
        }

        return $data;
    }


    /**
     * Пытается найти в базе список моделей, основываясь на атрибутах текущей модели
     *
     * Типичное применение:
     * - Загружите модель данными, которые пришли вам из формы
     * - Вызовите этот метод, он вернет вам модели, которые нашел в базе
     * основываясь на конфигурации загруженной модели
     * - Передайте эту функцию в любой виджет основанный на CGridView
     *
     * @return CActiveDataProvider  - поставщик данных, который возвращает модели
     * основываясь на CDbCriteria, сгенерированной из атрибутов модели
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->addSearchCondition('name', $this->name, true, 'AND', 'ILIKE');
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('balance', $this->balance);
        $criteria->compare('currency_id', $this->currency_id);
        $criteria->addSearchCondition('comment', $this->comment, true, 'AND', 'ILIKE');

        foreach ($this->dates_for_convert as $attribute) {
            $criteria = $this->getSearchDate($criteria, $attribute);
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC'
            )
        ));
    }

    /**
     * Возвращает статическую модель для указанного AR класса.
     * Обратите внимание, что вы должны иметь этот метод во всех ваших CActiveRecord потомках!
     *
     * @param string $className имя класса активной записи.
     * @return Cash статический класс модели
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    /**
     * Пишет данные для ряда фильтр котороего является
     * мультиселектом.
     *
     * @param $row
     * @return null
     */
    public function getMultiSelectsRowValue($row)
    {
        $row_data = $this->getMultiSelectsData($row);
        if (!empty($row_data) && count($row_data) && isset($row_data[$this->$row])) {
            return $row_data[$this->$row];
        }

        return null;
    }

    /**
     * Рисует кнопки функций таблицы
     *
     * @return string
     */
    public function getGridFunctionsButtons()
    {
        $view_button = "<div class='btn btn-small cash___view' title='Редактировать' data-id='{$this->id}' ><i  class='icon icon-pencil'></i></div>";
        $delete_button = "<div class='btn btn-small cash___delete' title='Удалить' data-id='{$this->id}'><i class='icon icon-trash text-error' ></i></div>";

        return $view_button . ' ' . $delete_button;
    }


    /**
     * Колонки для грида
     *
     * @param $columns
     * @return array
     */
    public function columnsGrid($columns)
    {
        $result = array();
        /**
         * @var Controller $controller
         */
        $controller = Yii::app()->controller;
        foreach ($columns as $row) {
            switch ($row) {

                //				case 'id':
                //					$result[] = array(
                //						'name' => 'id',
                //						'header' => '#',
                //						'htmlOptions' => array(
                //							'class' => 'grid-id-column'
                //						)
                //					);
                //					break;
                case 'user_id':
                    if ($controller->allowed('indexAllCash')) {
                        $result[] = array(
                            'name' => $row,
                            'filter' => $this->getMultiSelectFilter($row),
                            //'filter' =>false,
                            'value' => '$data->user ? $data->user->getFamName() : ""'
                        );
                    }
                    break;
                case 'balance':
                    $result[] = array(
                        'name' => $row,
                        'filter' => false,
                        'value' => '$data->balance." ".$data->getCurrency()'
                    );
                    break;
                case 'functions':
                    $result[] = array(
                        'header' => "Действия",
                        'type' => 'raw',
                        'filter' => false,
                        'value' => '$data->getGridFunctionsButtons()'
                    );
                    break;
                default:
                    // Если элемент присутствует в списке дат - делаем ему дейтренж
                    if (in_array($row, $this->dates_for_convert)) {
                        $result[] = array(
                            'name' => $row,
                            'filter' => $this->getPeriodFilter($row),
                        );
                    } // Если элемент присутствует в списке мультиселектов делаем ему мультиселект
                    else if (in_array($row, $this->grid_multi_selects)) {
                        $result[] = array(
                            'name' => $row,
                            'filter' => $this->getMultiSelectFilter($row),
                            'value' => '$data->getMultiSelectsRowValue("' . $row . '");',
                        );
                    } else {
                        $result[] = array(
                            'name' => $row,
                        );
                    }
                    break;
            }
        }

        return $result;
    }

    /**
     * @return array - правила для {@link GridColumnsFilter}
     */
    public function getColumnsRules()
    {
        $attributes = array(
            'required' => array(
                'name',
                'balance',
            ),
        );
        /**
         * @var Controller $controller
         */
        $controller = Yii::app()->controller;

        if ($controller->allowed('indexAllCash')) {
            $attributes['required'][] = 'user_id';
            $attributes['required'][] = 'functions';
        }

        return $attributes;
    }

    /**
     * @return array Колонки фильтра
     */
    public function getFilter()
    {
        /**
         * @var Controller $controller
         */
        $controller = Yii::app()->controller;

        $attributes = array(
            'main' => array(
                'label' => 'Основные',
                'childs' => array(
                    'name',
                    'balance',
                    'currency_id',
                )
            ),
            'other' => array(
                'label' => 'Прочие',
                'childs' => array(
                    'comment',
                    'created',
                    'modified',
                )
            )
        );

        if ($controller->allowed('indexAllCash')) {
            $attributes['main']['childs'][] = 'user_id';
            $attributes['main']['childs'][] = 'functions';
        }

        return $attributes;
    }

}
