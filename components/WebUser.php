<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 10/29/2017
 * Time: 7:09 PM
 */

namespace app\components;


use app\models\ObjFactory;
use app\models\Session\Session;
use app\models\Session\SessionPack;
use app\models\Users\UsersCollection;
use app\models\Users\UsersPack;
use yii\base\Component;
use yii\web\Cookie;
use yii;

class WebUser extends Component
{

    const COOKIE_NAME = 'auth';

    const EXPIRES = 604800;// 60 * 60 * 24 * 7;

    /**
     * @var UsersPack
     */
    private $_userInfo = null;

    /**
     * @var SessionPack
     */
    private $_session = null;

    public function init()
    {
        $this->_userInfo = new UsersPack();
        $this->_session = new SessionPack();
        $cookies = ObjFactory::request()->cookies;
        $sessionId = $cookies->get(WebUser::COOKIE_NAME);

        if (!empty($sessionId)) {
            $this->_session = Session::getUser($sessionId);
            $this->_userInfo = $this->_session->users;
        }

        if (!$this->_userInfo->current())
        {
            $this->_clearSession();
        }
    }

    public function getUserData()
    {
        return $this->_userInfo;
    }

    public function authorise()
    {
        $usersCollection = new UsersCollection();

        if ($this->_userInfo->email !== null)
        {
            $usersCollection->setEmails($this->_userInfo->email);
        } elseif ($this->_userInfo->phone !== null)
        {
            $usersCollection->setPhones($this->_userInfo->phone);
        } else {
            return false;
        }
        $this->_userInfo = $usersCollection->getAll();

        if ($this->_userInfo->current())
        {
            $session = Session::newSession($this->_userInfo->user_id);
            $cookie = new Cookie([
                'name' => WebUser::COOKIE_NAME,
                'value' => $session->session,
                'expire' => $session->expires]);
            $cookies = ObjFactory::response()->cookies;
            $cookies->add($cookie);
            $this->_session = $session;
        }

        return $this->_userInfo->current();
    }

    /**
     * @return bool
     */
    public function logout()
    {
        return $this->_clearSession();
    }

    public function encryptPassword($password)
    {
        return md5($password.yii::$app->params['secret']);
    }

    /**
     * @return bool
     */
    private function _clearSession()
    {
        $cookies = ObjFactory::response()->cookies;
        $cookies->remove("auth");
        $this->_userInfo->clear();
        $this->_session->clear();
        return true;
    }

}