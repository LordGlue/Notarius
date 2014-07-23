<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    /**
     * @return boolean whether authentication succeeds.
     */
    private $_id;

    /**
     * @var
     */
    public $role_name;

    /**
     * @return bool
     */
    public function authenticate()
    {
        /**
         * @var $user Users
         */
        $user = Users::model()->find('LOWER(username)=?', array(strtolower($this->username)));

        if ($user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif ($user->role == 'blocked') {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if ($this->authLdap()) {
            $this->_id = $user->id;
            $password = Yii::app()->controller->encrypt($this->password);
            $this->setState('password', $password);
            $this->errorCode = self::ERROR_NONE;
        } else {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        }

        return $this->errorCode == self::ERROR_NONE;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

}