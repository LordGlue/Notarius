<?php
/**
 * Выводит таблицу всех записей, в меганавороченном гриде...
 *
 * @var $model Cash
 * @var $this CashController
 * @var string $grid_id ИД таблицы
 * @var CActiveDataProvider $grid_data_provider поставщик данных для таблицы
 * @var array $filter фильтр для таблицы
 * @var array $columns колонки таблицы
 */
/*
$this->widget("booster.widgets.TbJsonGridView", array(
	"id" => $grid_id,
	"dataProvider" => $grid_data_provider,
	"filter" => $model,
	'toolbarOptions' => array(
		'columns_filter' => array(
			'model' => $model,
			'current_columns' => $filter,
		),
	),
	"summaryText" => "Кассы {start} &#151; {end} из {count}",

	'selectionChanged' => 'js:function(id){
			var cash_id = parseInt(jQuery.fn.yiiGridView.getSelection(id));
			if(!isNaN(cash_id)){
				window.location.href = "/cash/" + cash_id
			}
		}',

	"columns" => $columns,


));*/

$this->widget(
    'booster.widgets.TbJsonGridView',
    array(
        'dataProvider' => $model->search(),
        'filter' => $model,
        'type' => 'striped bordered condensed',
        'summaryText' => false,
        'cacheTTL' => 10, // cache will be stored 10 seconds (see cacheTTLType)
        'cacheTTLType' => 's', // type can be of seconds, minutes or hours
        'columns' => $columns
    )
);