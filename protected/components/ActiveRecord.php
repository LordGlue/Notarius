<?php

/**
 * Class ActiveRecord
 */
class ActiveRecord extends CActiveRecord
{

    // Время кеширования страницы
    /**
     *
     */
    const CACHE_DURATION = 3000;


    /**
     *
     */
    protected function beforeFind()
    {
        parent::beforeFind();
    }

    /**
     *
     */
    protected function afterSave()
    {
        parent::afterSave();
    }


    /**
     *
     */
    protected function afterDelete()
    {
        parent::afterDelete();
    }


    /**
     *
     */
    protected function afterFind()
    {
        parent::afterFind();
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        return parent::beforeValidate();
    }

    /**
     * @return bool
     */
    protected function beforeSave()
    {
        return parent::beforeSave();
    }

}