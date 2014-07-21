<?php
/** @var Controller $this
 * @var string $content
 */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css"
          media="print"/>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css"/>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<?php
$this->widget(
    'booster.widgets.TbNavbar',
    [
        'brand' => Yii::app()->name,
        'fixed' => false,
        'fluid' => false,
        'items' => [
            array(
                'class' => 'booster.widgets.TbMenu',
                'type' => 'navbar',
                'items' => array(
                    array('label' => 'Home', 'url' => array('/site/index')),
                    array('label' => 'About', 'url' => array('/site/page', 'view' => 'about')),
                    array('label' => 'Contact', 'url' => array('/site/contact')),
                    array('label' => 'Login', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                    array(
                        'label' => 'Logout (' . Yii::app()->user->name . ')',
                        'url' => array('/site/logout'),
                        'visible' => !Yii::app()->user->isGuest
                    )
                ),
            )
        ]
    ]
);

?>

<div class="container">

    <?php if (isset($this->breadcrumbs)):
        $this->widget('booster.widgets.TbBreadcrumbs', ['links' => $this->breadcrumbs,]);
    endif ?>

    <?php echo $content; ?>
</div>
<!-- /.container -->


<!-- Placed at the end of the document so the pages load faster -->
</body>
</html>