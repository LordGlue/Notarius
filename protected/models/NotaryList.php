<?php

/**
 * Это класс модели для таблицы "notary_list".
 *
 * Ниже описаны доступные поля для таблицы 'notary_list':
 *
 * @property integer $id ПК
 * @property integer $notary_id Нотариус
 * @property integer $vrio_id ВРИО
 * @property string $date_start Дата начала
 * @property string $date_end Дата конца
 * @property string $filename Документ
 * @property integer $status_id Статус
 * @property string $created Дата создания
 *
 *
 * Ниже описаны доступные для модели зависимости:
 * @property Notary $notary
 * @property Notary $vrio
 * @property NotaryListStatus $status
 *
 *
 * @method NotaryList   find()        find($condition = '', $params = array())
 * @method NotaryList   findByPk()    findByPk($pk, $condition = '', $params = array())
 * @method NotaryList   findByAttributes() findByAttributes($attributes, $condition = '', $params = array())
 * @method NotaryList[] findAllByPk() findAllByPk($pk, $condition = '', $params = array())
 * @method NotaryList[] findAllByAttributes() findAllByAttributes($attributes, $condition = '', $params = array())
 * @method NotaryList[] findAll()     findAll($condition = '', $params = array())
 */
class NotaryList extends ActiveRecord
{

    /**
     * @var array $dates_for_convert - тут перечислены
     * атрибуты, типа DATE, TIMESTAMP.
     * Даты будут конвертироваться методами afterFind() и beforeSave()
     * в классе {@link ActiveRecord}
     *
     */
    public $dates_for_convert = array(
        'date_start',
        'date_end',
        'created',

    );

    /**
     * @var array $grid_multi_selects - тут перечислены
     * все атрибуты модели, которые должны иметь фильтр select2
     * Важно!!! Для каждого из перечисленных атрибутов должны быть
     * данные в методе getMultiSelectsData($attribute)
     */
    public $grid_multi_selects = array('status_id','notary_id','vrio_id');

    /**
     * @return string возвращает сроку привязанной к модели таблицы
     */
    public function tableName()
    {
        return 'notary_list';
    }

    /**
     * @return array правила валидации для атрибутов модели
     */
    public function rules()
    {
        // NOTE: вам нужно лишь защитить атрибуты, которые будет вводить пользователь
        // можете удалить лишнее
        return array(
            array('created', 'required'),
            array('notary_id, vrio_id, status_id', 'numerical', 'integerOnly' => true),
            array('filename', 'length', 'max' => 255),
            array('date_start, date_end', 'safe'),
            // Следующее правило будет использовано в search().
            // @todo Пожалуйста удалите атрибуты, которые не должны экранироваться в поиске
            array(
                'id, notary_id, vrio_id, date_start, date_end, filename, status_id, created',
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
        // NOTE: возможно вам нужно настроить эти зависимости.
        // Классы для зависимостей были сгенерированы автоматически! Проверьте их наличие.
        return array(
            'notary' => array(self::BELONGS_TO, 'Notary', 'notary_id'),
            'vrio' => array(self::BELONGS_TO, 'Notary', 'vrio_id'),
            'status' => array(self::BELONGS_TO, 'NotaryListStatus', 'status_id'),

        );
    }

    /**
     * @return array подписи атрибутов (атрибут=>подпись)
     */
    public function attributeLabels()
    {
        return array(
            'functions' => 'Функции',
            'id' => 'ПК',
            'notary_id' => 'Нотариус',
            'vrio_id' => 'ВРИО',
            'date_start' => 'Дата начала',
            'date_end' => 'Дата конца',
            'filename' => 'Документ',
            'status_id' => 'Статус',
            'created' => 'Дата создания',

        );
    }

    /**
     * @return array дефолтные атрибуты, выводящиеся в гриде
     */
    public function attributeDefault()
    {
        return array(
            'id',
            'notary_id',
            'vrio_id',
            'date_start',
            'date_end',
            'filename',
            'status_id',
            'created',
            'functions',

        );
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
        // Тут пример кода
        // TODO Удалите эти строки, если вам не нужны мультиселекты в таблице
        switch ($attribute) {
            case 'status_id':
                $data = CHtml::listData(NotaryListStatus::model()->findAll(),'id','name');
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
        $criteria->compare('notary_id', $this->notary_id);
        $criteria->compare('vrio_id', $this->vrio_id);
        $criteria->addSearchCondition('filename', $this->filename, true, 'AND', 'ILIKE');
        $criteria->compare('status_id', $this->status_id);


        // Автоматическое добавление поиска по датам на основе
        // массива $this->dates_for_convert
        foreach ($this->dates_for_convert as $attribute) {
            $criteria = $this->getSearchDate($criteria, $attribute);
        }

        /* Пример превращения текстового ввода в идентифкиатор
        if (isset($this->creator_id)) {
            $user_criteria = new CDbCriteria();
            // Разбиваем пришедший нам запрос на ключевые слова
            $keywords = explode(' ', $this->creator_id);
            foreach ($keywords as $keyword) {
                // И ищем каждое ключевое слово в фамилии имени или отчестве пользователя
                $user_criteria->addSearchCondition('firstname', $keyword, true, "OR");
                $user_criteria->addSearchCondition('lastname', $keyword, true, "OR");
                $user_criteria->addSearchCondition('surename', $keyword, true, "OR");
            }
            $users = User::model()->findAll($criteria, array('select' => 'id'));
            if ($users) {
                $user_ids = array();
                foreach ($users as $user) {
                    $user_ids[] = $user->id;
                }
                $criteria->compare('creator_id', $user_ids);
            }
        }
        */


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
     * @return NotaryList статический класс модели
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    /**
     * Пишет данные для ряда фильтр котороего является
     * мультиселектом.
     *
     * @param string $row - атрибут модели
     * @return bool
     */
    public function getMultiSelectsRowValue($row)
    {
        $row_data = $this->getMultiSelectsData($row);
        if (!empty($row_data) && count($row_data) && isset($row_data[$this->$row])) {
            return $row_data[$this->$row];
        }

        return false;
    }

    /**
     * Рисует кнопки функций таблицы
     *
     * @return string
     */
    public function getGridFunctionsButtons()
    {
        $id = $this->id;
        $view_button = "<div class='btn btn-small notarylist___view' title='Редактировать' data-id='{$id}' ><i  class='icon icon-pencil'></i></div>";
        $delete_button = "<div class='btn btn-small notarylist___delete' title='Удалить' data-id='{$id}'><i class='icon icon-trash text-error' ></i></div>";

        return $view_button . ' ' . $delete_button;

        // TODO выберите из двух вариантов наиболее подходящий для вас
        //		$view_button = CHtml::link('Просмотр', '#', array(
        //			'class' => 'dashed notarylist___view',
        //			'data-id' => $this->id,
        //		));
        //		$delete_button = CHtml::link('Удалить', '#', array(
        //			'class' => 'dashed text-error notarylist___delete',
        //			'data-id' => $this->id,
        //		));

        //		return $view_button . '<br/>' . $delete_button;

    }


    /**
     * Колонки для грида
     *
     * @param array $columns
     * @return array
     */
    public function columnsGrid($columns)
    {
        $result = array();
        foreach ($columns as $row) {
            switch ($row) {

                case 'id':
                    $result[] = array(
                        'name' => 'id',
                        'header' => '#',
                        'htmlOptions' => array(
                            'class' => 'grid-id-column'
                        )
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
        return array(
            'required' => array(
                'id',
                'functions'
            )
        );
    }

    /**
     * @return array Колонки фильтра
     */
    public function getFilter()
    {
        return array(
            'main' => array(
                'label' => 'Основные',
                'childs' => array(
                    'functions',
                    'id',
                    'notary_id',
                    'vrio_id',
                    'date_start',
                    'date_end',
                    'filename',
                    'status_id',
                    'created',

                )
            ),
            'other' => array(
                'label' => 'Прочие',
                'childs' => array()
            )
        );
    }

}
