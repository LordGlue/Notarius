<?php
/**
 * Модальное окно с формой создания новой записи
 *
 * @var ModelClass $model
 * @var TbActiveForm $form
 * @var ControllerClass $this
 */

$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'LowerMClass__create_form',
        'type' => 'horizontal',
        'action' => '/LowerMClass/create',
        'method' => 'post',
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'afterValidate' => 'js:function(form,data,hasError){
							if(hasError) {
								return false;
							}

							ajaxForm("LowerMClass__create_form", function () {
								showMessage("success", this.message);
								$("#LowerMClass__create_modal").modal("hide");
								gridUpdate("LowerMClass__index_grid");
							});
							return false;

                        }'
        )
    )
);

$this->beginWidget(
    'bootstrap.widgets.TbModal',
    array(
        'id' => 'LowerMClass__create_modal',
        'htmlOptions' => array('class' => 'middle_modal')
    )
); ?>

    <div class="modal-header">
        <a class="close" onclick="closeModal(this)">&times;</a>
        <h4>Добавить запись</h4>
    </div>

    <div class="modal-body">
        <div class="row-fluid">

            BootstrapForm

        </div>
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