<?

/**
 * Class WebUser
 */
class WebUser extends CWebUser
{
    /**
     * @var User
     */
    public $user;

    /**
     * construct webuser
     */
    public function __construct()
    {
        $this->user = User::model()->findByPk($this->id);

        return true;
    }

    //	public $role = null;
    /**
     * Возвращает роль пользователя по новому алгоритму
     *
     * @return string
     */
    public function getRole()
    {
        static $role;
        if (!$role) {
            $user = User::model()->findByPk($this->id);
            if ($user) {
                $role = $user->role;
            }
        }

        return $role;
    }


    /**
     * Возвращает полное имя пользователя
     *
     * @return string
     */
    public function getFullName()
    {
        $model = User::model()->findByPk($this->id);

        return $model->name;
    }

}