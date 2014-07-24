<?php
/**
 * Выводит таблицу всех записей, в меганавороченном гриде...
 *
 * @var $model Cash
 * @var $this NotaryListController
 * @var string $grid_id ИД таблицы
 * @var CActiveDataProvider $grid_data_provider поставщик данных для таблицы
 * @var array $filter фильтр для таблицы
 * @var array $columns колонки таблицы
 */

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