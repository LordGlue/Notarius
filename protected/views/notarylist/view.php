<?php
/**
 * Страница просмотра кассы и операций по ней
 *
 * @var $model Cash
 * @var $this CashController
 * @var $operation CashOperation
 * @var string $grid_id ИД таблицы
 * @var CActiveDataProvider $grid_data_provider поставщик данных для таблицы
 * @var array $filter фильтр для таблицы
 * @var array $columns колонки таблицы
 */
$title = "Касса {$model->name}";
$this->breadcrumbs = array(
    'Кассы' => '/cash/',
    $title,
);
$this->pageTitle = $title . Yii::app()->name;
?>
<?php $this->renderPartial(
    'cash.views.operation._index_grid',
    array(
        'model' => $operation,
        'cash' => $model,
        'grid_id' => $grid_id,
        'grid_data_provider' => $grid_data_provider,
        'filter' => $filter,
        'columns' => $columns
    )
);

// Чтобы поля не заполнялись автоматически гридом
$operation->unsetAttributes();
$operation->cash_id = $model->id;
$this->renderPartial(
    'cash.views.operation._create_modal',
    array(
        'model' => $operation,
    )
);