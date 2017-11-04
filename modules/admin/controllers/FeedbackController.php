<?php

namespace app\modules\admin\controllers;

use app\models\BO\ResampleImages;
use app\models\Category\Categories;
use app\models\Category\CategoryCollection;
use app\models\Category\CategoryDelete;
use app\models\Category\CategoryEdit;
use app\models\Category\CategoryPack;
use app\models\Feedback\Feedback;
use app\models\Feedback\FeedbackCollection;
use app\models\Feedback\FeedbackDelete;
use app\models\Feedback\FeedbackEdit;
use app\models\Feedback\FeedbackPack;
use app\models\ObjFactory;
use Yii;
use app\modules\admin\models;

/**
 * Description of DefaultController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class FeedbackController extends AdminController
{
    /**
     * init all url in admin
     * @param int $offset
     * @return null
     */
    public function actionIndex($offset = 0)
    {
        $keyword = yii::$app->request->post('keyword', "");
        $new = yii::$app->request->post('new', 0);

        $collection = new FeedbackCollection();
        
        if ($new) {
            $collection->setNew($new);
        }

        if (!empty($keyword)) {
            $collection->setKeyWord($keyword);
        }

        $feedback = $collection->getItems($offset);
        $newOffset = $collection->getOffset();

        return $this->render('index', [
            'new' => $new,
            'keyword' => $keyword,
            'items' => $feedback,
            'newOffset' => $newOffset
        ]);
    }

    public function actionFeedback($feedbackId = 0)
    {
        if (!empty($feedbackId))
        {
            $feedback = new Feedback();
            $ids = ['feedback_id' => [$feedbackId]];
            $items = $feedback->getItemsByIds($ids);
            $items->moveToItem($feedbackId);
            $items->new = 0;
            $feedback = $items->flush();
            $feedback->first();
            return $this->render('feedback', ['feedback' => $feedback]);
        }
        return $this->actionIndex();
    }

    public function actionSave()
    {
        $mark = yii::$app->request->post('mark', []);
        $action = yii::$app->request->post('action', []);
        $ids = array_keys($mark);
        $feedback = new Feedback();
        $result = $feedback->getItemsByIds(['feedback_id' => $ids]);
        for($result->first(); $result->current(); $result->next())
        {
            $result->new = $action == 'read' ? 0 : 1;
        }
        $result->flush();

        return $this->actionIndex();
    }

    public function actionDelete()
    {
        $feedbackIds = ObjFactory::request()->post('mark', []);
        $model = new FeedbackDelete(['feedback_id' => array_keys($feedbackIds)]);
        $model->delete();

        return $this->actionIndex();
    }
}