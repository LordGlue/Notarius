<?php
/**
 * Модальное окно с формой создания новой записи
 *
 * @var $model Cash
 * @var $form TbActiveForm
 * @var $this CashController
 */

$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'cash__create_form',
        'type' => 'horizontal',
        'action' => '/cash/create',
        'method' => 'post',
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'afterValidate' => 'js:function(form,data,hasError){
							if(hasError) {
								return false;
							}

							ajaxForm("cash__create_form", function () {
								showMessage("success", this.message);
								$("#cash__create_modal").modal("hide");
								gridUpdate("cash__index_grid");
							});
							return false;

                        }'
        )
    )
);


$this->beginWidget(
    'bootstrap.widgets.TbModal',
    array(
        'id' => 'cash__create_modal',
        'htmlOptions' => array('class' => 'small_modal')
    )
);

?>

    <div class="modal-header">
        <a class="close" onclick="closeModal(this)">&times;</a>
        <h4>Добавить кассу</h4>
    </div>

    <div class="modal-body">
        <?php echo $form->textFieldRow($model, 'name'); ?>
        <?php echo $form->dropDownListRow($model, 'user_id', $model->getMultiSelectsData('user_id')); ?>
        <?php echo $form->dropDownListRow($model, 'currency_id', $model->getCurrencies()); ?>
        <?php echo $form->textAreaRow(
            $model,
            'comment',
            array(
                'rows' => 6,
                'cols' => 50,
            )
        ); ?>
    </div>

    <div class="modal-footer">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'type' => 'primary',
                'label' => 'Добавить',
                'buttonType' => 'submit',
            )
        ); ?>
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => 'Отмена',
                'url' => '#',
                'htmlOptions' => array('onclick' => 'closeModal(this);return false;'),
            )
        ); ?>
    </div>
<?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>