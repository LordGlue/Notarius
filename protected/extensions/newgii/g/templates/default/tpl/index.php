<?php
/**
 * Выводит список всех записей
 *
 * @var ModelClass $model модель
 * @var ControllerClass $this контроллер
 * @var string $grid_id ИД таблицы
 * @var CActiveDataProvider $grid_data_provider поставщик данных для таблицы
 * @var array $filter фильтр для таблицы
 * @var array $columns колонки таблицы
 */
$this->breadcrumbs = array(
    'To google' => 'http://google.com/',
    "ModelClass",
);
$this->pageTitle = "ModelClass - " . Yii::app()->name;

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
<div id="LowerMClass__create_modal_wrapper">
    <?php
    $this->renderPartial(
        '_create_modal',
        array(
            'model' => $model,
        )
    );
    ?>
</div>
<div id="LowerMClass__view_modal_wrapper"></div>
<script type="text/javascript">
    // TODO: создайте нормальные ассеты перенесите код ниже в туда
    $(function () {

        /**
         * Кнопка показать модал добавления записи
         */
        setEvent('click', '#LowerMClass__show_create_modal_button', function () {
            $('#LowerMClass__create_form').trigger('reset');
            $('#LowerMClass__create_modal').modal('show');
            return false;
        });

        /**
         * Просмотр записи
         */
        setEvent('click', '.LowerMClass___view', function () {
            ajaxModal('LowerMClass__view_modal', '/LowerMClass/view/' + $(this).data('id'));
            return false;
        });

        /**
         * Удаление записи
         */
        setEvent('click', '.LowerMClass___delete', function () {
            var button = $(this);
            if (confirm('Вы уверены, что хотите удалить ModelClass?')) {
                simpleGetPOST('/LowerMClass/delete/' + button.data('id'), {}, function (message) {
                    showMessage('success', message);
                    if ($('#LowerMClass__view_modal').length) {
                        $('#LowerMClass__view_modal').modal('hide');
                    }
                    $('#LowerMClass__index_grid').find('[data-id="' + button.data('id') + '"]').closest('tr').remove();
                });
            }
            return false;
        });
    });
</script>