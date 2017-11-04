<?php

namespace app\modules\admin\models;

use app\models\ObjFactory;
use yii;
use yii\helpers\HtmlPurifier;

/**
 * Basic HTTP Authentication
 * Class BasicAuth
 * @package app\modules\admin\models
 */
class BasicAuth
{
    const SLEEP_BETWEEN_ATTEMPTS = 15;
    const SALT = '_|_|_';
    const REDIS_PREFIX = 'bo_auth_';
    const REDIS_TTL = 86400; // one day
    const COOKIE_NAME = 'bo_auth';
    const COOKIE_EXPIRE = -1;

    /**
     * authorization
     */
    public function auth()
    {
        $login = $this->getLogin();
        $password = $this->getPassword();
        return true;
        /*if (empty($login) || empty($password)) {
            $this->authHead();
        } else {
            $boUser = new BoUser();
            $passwordFromDb = $boUser->issetAdminUser($login, $password);
            if (!$passwordFromDb) {
                sleep(static::SLEEP_BETWEEN_ATTEMPTS);
                $this->authHead();
            }
            $hash = $this->getHash($passwordFromDb);
            $this->setAuthHash($hash);
            $this->setAuthCookie($hash);
            return true;
        }*/
    }

    /**
     * TODO work not good with redirect
     * clear all auth information
     */
    public function clearAuth()
    {
        $this->clearAuthCookie();
        $this->clearAuthServer();
    }

    /**
     * get authorization user or not by hash
     * @return bool
     */
    public function getAuthHash()
    {
        $hash = $this->getAuthCookie();
        if ($hash == false) {
            return false;
        }

        $model = ObjFactory::redis(Yii::$app->params['redis_sessions_and_user_data']);
        return $model->get(static::REDIS_PREFIX . $hash);
    }


    /**
     * Get login (basic auth)
     * @return string
     */
    private function getLogin()
    {
        return HtmlPurifier::process(yii::$app->request->getAuthUser());
    }

    /**
     * Get password (basic auth)
     * @return string
     */
    private function getPassword()
    {
        return HtmlPurifier::process(yii::$app->request->getAuthPassword());
    }

    /**
     * send head for authorization on forum
     */
    private function authHead()
    {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Cancel';
        exit;
    }

    /**
     * hash password
     * @param string $password
     * @return string
     */
    private function getHash($password)
    {
        return md5(md5(static::SALT . $password));
    }

    /**
     * set hash for authorization
     * @param $hash
     */
    private function setAuthHash($hash)
    {
        $model = ObjFactory::redis(Yii::$app->params['redis_sessions_and_user_data']);
        $model->setex(static::REDIS_PREFIX . $hash,true,static::REDIS_TTL);
    }

    /**
     * Set cookie for authorization
     * @param string $hash
     */
    private function setAuthCookie($hash)
    {
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => static::COOKIE_NAME,
            'value' => $hash,
            'expire' => static::COOKIE_EXPIRE,
            'domain' => $this->getDomain()
        ]));
    }

    /**
     * Get authorization hash by cookie
     * @return string
     */
    private function getAuthCookie()
    {
        $cookies = Yii::$app->request->cookies;
        return $cookies->getValue(static::COOKIE_NAME, false);
    }


    /**
     * Clearing auth cookie
     */
    private function clearAuthCookie()
    {
        $cookies = Yii::$app->response->cookies;
        $cookies->remove(static::COOKIE_NAME);
    }

    /**
     * Clearing server info
     */
    private function clearAuthServer()
    {
        unset($_SERVER['PHP_AUTH_USER']);
        unset($_SERVER['PHP_AUTH_PW']);
    }

    /**
     * Get current domain for cookie
     * @return string
     * @throws \Exception
     */
    private function getDomain()
    {
        $host = Yii::$app->request->getHostInfo();
        $urlParts = parse_url($host);
        if (!empty($urlParts['host'])) {
            return $urlParts['host'];
        }
        throw new \Exception('error host');
    }
}
