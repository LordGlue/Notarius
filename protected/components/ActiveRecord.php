<?php

/**
 * Class ActiveRecord
 */
class ActiveRecord extends CActiveRecord
{

    // Время кеширования страницы
    /**
     *
     */
    const CACHE_DURATION = 3000;


    /**
     *
     */
    protected function beforeFind()
    {
        parent::beforeFind();
    }

    /**
     *
     */
    protected function afterSave()
    {
        parent::afterSave();
    }


    /**
     *
     */
    protected function afterDelete()
    {
        parent::afterDelete();
    }


    /**
     *
     */
    protected function afterFind()
    {
        parent::afterFind();
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        return parent::beforeValidate();
    }

    /**
     * @return bool
     */
    protected function beforeSave()
    {
        return parent::beforeSave();
    }


	/**
	 * Создает фильтр для выбора периода дат
	 *
	 * @param $attribute
	 * @return string
	 */
	public function getMultiSelectFilter($attribute)
	{
		return Yii::app()->controller->widget(
			'booster.widgets.TbSelect2',
			array(
				'model' => $this,
				'attribute' => $attribute,
				'data' => $this->getMultiSelectsData($attribute),
				'val' => isset($this->$attribute) ? $this->$attribute : '',
				'htmlOptions' => array(
					'multiple' => 'multiple',
					'id' => $attribute . '_multiselect'
				)
			),
			true
		);
	}

	/**
	 * Обрабатывает пришедший период дат
	 * (строку вида "05.10.2012 - 08.12.2013")
	 * в валидную CDbCriteria
	 *
	 * @param CDbCriteria $criteria - передаем текущую критерию
	 * @param $attribute
	 * @internal param string $attibute - имя атрибута модели
	 * @return CDbCriteria - критерию
	 */
	public function getSearchDate($criteria, $attribute)
	{
		// Разбиваем пришедшую строку на 2 элемента
		$data = explode('-', $this->$attribute);
		$timestamp_date = Yii::app()->params['timestamp_date'];
		// Если нам пришел период
		if (count($data) == 2) {
			// Преобразуем критерию в период
			$from = date($timestamp_date, strtotime($data[0]));
			$to = date($timestamp_date, strtotime($data[1]));
			$criteria->addCondition('t.' . $attribute . " >= '" . $from . " 00:00:00'");
			$criteria->addCondition('t.' . $attribute . " <= '" . $to . " 23:59:59'");
		} else if (count($data) > 0 && !empty($data[0])) {

			// Если простая дата - делаем простой период в рамках одной даты
			$day = date($timestamp_date, strtotime($data[0]));
			$criteria->addCondition('t.' . $attribute . " >= '" . $day . " 00:00:00'");
			$criteria->addCondition('t.' . $attribute . " <= '" . $day . " 23:59:59'");
		}

		return $criteria;
	}

	/**
	 * Создает фильтр для выбора периода дат
	 *
	 * @param $attribute
	 * @return string
	 */
	public function getPeriodFilter($attribute)
	{
		return Yii::app()->controller->widget(
			'booster.widgets.TbDateRangePicker',
			array(
				'model' => $this,
				'attribute' => $attribute,
				'callback' => 'js:function(start, end){
					console.log($($(this)[0].element[0]).trigger("change"));
				}',
				'options' => array(
					//				'id' => $attribute.'_range',
					'format' => 'DD.MM.YYYY',
					//'locale' =>'ru'
				),
				'htmlOptions' => array(
					'id' => $attribute . '_range',
				)
			),
			true
		);
	}
}