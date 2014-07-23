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
    'Нотариусы',
);
$this->pageTitle = "Нотариусы - " . Yii::app()->name;

?>
<div class="row">
    <h1 class="col-md-2">Нотариусы</h1>

    <div class="col-md-10">

        <?php

        //if (Yii::app()->getUser()->role=='admin')) {
        $this->widget(
            "booster.widgets.TbButton",
            array(
                'id' => 'notary__show_create_modal_button',
                'label' => 'Добавить нотариуса',
                'buttonType' => 'success'
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
