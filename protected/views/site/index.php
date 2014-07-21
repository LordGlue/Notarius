<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
?>

    <h1><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

    <p>Congratulations! You have successfully created your Yii application.</p>
<?php
$this->widget(
    'booster.widgets.TbButton',
    array(
        'label' => 'Common',
    )
);
echo ' ';
$this->widget(
    'booster.widgets.TbButton',
    array(
        'label' => 'Primary',
        'context' => 'primary',
    )
);
echo ' ';
$this->widget(
    'booster.widgets.TbButton',
    array(
        'label' => 'Success',
        'context' => 'success',
    )
);
echo ' ';

$this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'myModal')
); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Modal header</h4>
    </div>

    <div class="modal-body">
        <p>One fine body...</p>
    </div>

    <div class="modal-footer">
        <?php $this->widget(
            'booster.widgets.TbButton',
            array(
                'context' => 'primary',
                'label' => 'Save changes',
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
        <?php $this->widget(
            'booster.widgets.TbButton',
            array(
                'label' => 'Close',
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
    </div>

<?php $this->endWidget(); ?>
<?php $this->widget(
    'booster.widgets.TbButton',
    array(
        'label' => 'Click me',
        'context' => 'primary',
        'htmlOptions' => array(
            'data-toggle' => 'modal',
            'data-target' => '#myModal',
        ),
    )
);