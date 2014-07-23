<?php
/**
 * Выводит таблицу всех записей, в меганавороченном гриде...
 *
 * @var ModelClass $model
 * @var ControllerClass $this
 * @var string $grid_id ИД таблицы
 * @var CActiveDataProvider $grid_data_provider поставщик данных для таблицы
 * @var array $filter фильтр для таблицы
 * @var array $columns колонки таблицы
 */
$this->widget(
    "EzvukGrid",
    array(
        "id" => $grid_id,
        "dataProvider" => $grid_data_provider,
        "filter" => $model,
        'toolbarOptions' => array(
            'columns_filter' => array(
                'model' => $model,
                'current_columns' => $filter,
            ),
            "flush_grid_filter" => true,
            'buttons' => array(
                $this->widget(
                    "bootstrap.widgets.TbButton",
                    array(
                        'id' => 'LowerMClass__show_create_modal_button',
                        'label' => 'Добавить запись',
                        'type' => 'primary'
                    ),
                    true
                )
            ),
        ),
        "summaryText" => "ModelClass {start} &#151; {end} из {count}",
        "columns" => $columns,
        // Реинстал дейтпикеров
        "multiselects" => $model->getMultiselectReinstallList(),
        'daterangepickers' => $model->getDatepickerReinstallList(),
    )
);