<?php

class NotaryListController extends Controller
{

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $model = new NotaryList('search');
        $grid_id = "notarylist__index_grid";
        $model->unsetAttributes(); // чистим дефолтные значения
        if (isset($_GET['NotaryList'])) {
            $model->attributes = $_GET['NotaryList'];
        }
        $columns_filter = $model->attributeDefault();

        if ($this->isAjax()) {
            header('Content-type: application/json');
            $this->renderPartial(
                '_index_grid',
                array(
                    'model' => $model,
                    'grid_id' => $grid_id,
                    'grid_data_provider' => $model->search(),
                    'filter' => $columns_filter,
                    'columns' => $model->columnsGrid($columns_filter)
                )
            );
            Yii::app()->end();
        }
        $this->render(
            'index',
            array(
                'model' => $model,
                'grid_id' => $grid_id,
                'grid_data_provider' => $model->search(),
                'filter' => $columns_filter,
                'columns' => $model->columnsGrid($columns_filter)
            )
        );
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }

}