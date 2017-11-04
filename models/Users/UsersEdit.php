<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\Users;

use app\models\BO\ResampleImages;
use Yii;

class UsersEdit
{
    private $_usersData = [];

    /**
     * UsersEdit constructor.
     * @param $usersData
     */
    public function __construct($usersData)
    {
        //TODO: validate
        $this->_usersData = $usersData;
    }

    /**
     * @throws \Exception
     */
    public function save()
    {
        $users = new UsersPack();
        $users->setBatch($this->_usersData);
        $users->addItem();
        $users->flush();
        return true;
    }

    /**
     * @param string $filePath
     * @param UsersPack $usersPack
     * @return UsersPack
     */
    public static function storeImage($filePath, $usersPack)
    {
        $userId = $usersPack->user_id;
        $imagePath = yii::$app->params['usersPath'].'/'.$userId.'.jpg';
        $lowPath = yii::$app->params['usersPath'].'/'.$userId.'_low.jpg';
        move_uploaded_file ($filePath,
            yii::getAlias('@webroot').$imagePath);

        $resampler = new ResampleImages();

        $currentImage = Yii::getAlias('@webroot').$imagePath;
        $lowImageFile = Yii::getAlias('@webroot').$lowPath;
        $resampler->resample($currentImage, $lowImageFile, 200, 200);
        $usersPack->avatar_low = $lowPath;
        $usersPack->avatar = $imagePath;

        return $usersPack;
    }
}