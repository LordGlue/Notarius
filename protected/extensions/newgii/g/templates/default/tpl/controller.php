<?php

/**
 * Class ControllerClass
 */
class ControllerClass extends BaseControllerClass
{

    /**
     * @return string название контроллера
     * TODO переименуйте это свойсво контроллера на то, которое считаете нужным
     */
    public function getName()
    {
        return 'ModelClass';
    }

    /**
     * Отображает модель
     *
     * @param integer $id идентификатор модели
     */
    public function actionView($id)
    {
        //$this->checkAccess('viewModelClass');
        $model = $this->loadModel($id);
        $this->renderPartial(
            '_view_modal',
            array(
                'model' => $model,
            ),
            false,
            true
        );
    }

    /**
     * Создает новую запись в базе данных
     * Если запишь прошла успешно - перенаправляет пользователя на страницу просмотра
     * вновь созданной записи.
     */
    public function actionCreate()
    {
        // Проверяем можно ли совершать это действие
        //$this->checkAccess('createModelClass');
        // Проверяем - пришли ли данные
        $this->checkRequiredData(
            array(
                'ModelClass',
            )
        );
        // Если нам переданы данные - начинаем обработку
        $model = new ModelClass;

        // Перегружаем свойства модели пришедшими данными и
        $model->attributes = $_POST['ModelClass'];

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'LowerMClass__create_form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // пытаемся сохранить запись в базе данных
        if ($model->save()) {
            $this->showMessage('ModelClass был успешно создан');
        } else {
            // Если что-то пошло не так - выдаем пользователю сообщение об ошибке.
            $this->throwException(CHtml::errorSummary($model), 402);
        }
    }

    /**
     * Обновляет данные модели
     *
     * @param integer $id идентификатор модели
     */
    public function actionUpdate($id)
    {
        //$this->checkAccess('updateModelClass');
        // Проверяем - пришли ли данные
        $this->checkRequiredData(
            array(
                'ModelClass',
            )
        );

        $model = $this->loadModel($id);

        $model->attributes = $_POST['ModelClass'];

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'LowerMClass__update_form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if ($model->save()) {
            $this->showMessage('ModelClass успешно изменен!');
        } else {
            $this->throwException(CHtml::errorSummary($model), 500);
        }
    }


    /**
     * Удаляет
     *
     * @param integer $id
     */
    public function actionDelete($id = 0)
    {
        //$this->checkAccess('deleteModelClass');
        try {
            // Если нам пришло много идентификаторов
            if (isset($_REQUEST['ids']) //
                && is_array($_REQUEST['ids']) //
                && count($_REQUEST['ids'])
            ) {
                // Удаляем всех
                ModelClass::model()->deleteAllByAttributes(array('id' => $_REQUEST['ids']));
                $this->showMessage('ModelClass успешно удалены');
            } else if ($id !== 0) {
                $this->loadModel($id)->delete();
                $this->showMessage('ModelClass успешно удален');
            } else {
                throw new Exception('Переданы не все параметры!', 402);
            }
        } catch (Exception $e) {
            $this->throwException('Не удалось удалить ModelClass: ' . $e->getMessage(), $e->getCode());
        }
    }


    /**
     * Выводит список всех записей
     */
    public function actionIndex()
    {
        //$this->checkAccess('indexModelClass');
        $model = new ModelClass('search');
        $grid_id = "LowerMClass__index_grid";
        $model->unsetAttributes(); // чистим дефолтные значения
        if (isset($_GET['ModelClass'])) {
            $model->attributes = $_GET['ModelClass'];
        } else {
            if (isset($_COOKIE[$grid_id])) {
                $model->attributes = CJSON::decode($_COOKIE[$grid_id]);
            }
        }

        // Получаем список колонок
        if (isset($_COOKIE[$grid_id . '_columns'])) {
            $columns_filter = explode(',', $_COOKIE[$grid_id . '_columns']);
        } else {
            $columns_filter = $model->attributeDefault();
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
     * Возвращает данные модели, найденные по ключу
     * если данные модели не найдены - выдает исключение.
     *
     * @param integer $id идентификатор модели
     * @return ModelClass
     */
    public function loadModel($id)
    {
        $model = ModelClass::model()->findByPk($id);
        if ($model === null) {
            $this->throwException('Модель класса ModelClass не найдена в базе данных', 404);
        }

        return $model;
    }

}
