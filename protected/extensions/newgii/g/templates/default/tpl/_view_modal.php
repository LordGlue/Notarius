<?php
/**
 * Модальное окно с формой создания новой записи
 *
 * @var $model ModelClass
 * @var $form TbActiveForm
 * @var $this ControllerClass
 */

$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'LowerMClass__update_form',
        'type' => 'horizontal',
        'action' => '/LowerMClass/update/' . $model->id,
        'method' => 'post',
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'afterValidate' => 'js:function(form,data,hasError){
							if(hasError) {
								return false;
							}

							ajaxForm("LowerMClass__update_form", function () {
								showMessage("success", this.message);
								$("#LowerMClass__view_modal").modal("hide");
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
        'id' => 'LowerMClass__view_modal',
        'htmlOptions' => array('class' => 'middle_modal')
    )
); ?>

    <div class="modal-header">
        <a class="close" onclick="closeModal(this)">&times;</a>
        <h4>Изменить запись</h4>
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
                'label' => 'Сохранить',
                'buttonType' => 'submit'
            )
        ); ?>
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'type' => 'danger',
                'label' => 'Удалить',
                'url' => '#',
                'htmlOptions' => array(
                    'class' => "LowerMClass___delete",
                    'data-id' => $model->id
                ),
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