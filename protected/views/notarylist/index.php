<?php
/**
 * Выводит список всех записей
 *
 * @var $model Cash
 * @var $this CashController
 * @var string $grid_id ИД таблицы
 * @var CActiveDataProvider $grid_data_provider поставщик данных для таблицы
 * @var array $filter фильтр для таблицы
 * @var array $columns колонки таблицы
 */
$this->breadcrumbs = array(
    'Список',
);
$this->pageTitle = "Список - " . Yii::app()->name;
?>
<div class="row">
    <h1 class="col-md-2">Список</h1>

    <div class="col-md-10 text-right">

        <?php

        //if (Yii::app()->getUser()->role=='admin')) {
        $this->widget(
            "booster.widgets.TbButton",
            array(
                'id' => 'notary__show_create_modal_button',
                'label' => 'Добавить запись',
                'context' => 'success',
				'icon'=>'plus'
            )
        );
        // }
        ?>
    </div>
</div>
<?php

$this->renderPartial(
    '_index_grid',
    array(
        'model' => $model,
        'grid_id' => $grid_id,
        'grid_data_provider' => $grid_data_provider,
        'filter' => $filter,
        'columns' => $columns
    )
);
?>
