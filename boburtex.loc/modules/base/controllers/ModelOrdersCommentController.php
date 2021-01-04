<?php

namespace app\modules\base\controllers;

use app\components\PermissionHelper as P;
use Yii;
use app\modules\base\models\ModelOrdersComment;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ModelOrdersCommentController implements the CRUD actions for ModelOrdersComment model.
 */
class ModelOrdersCommentController extends Controller
{
    /**
     * @param $action
     * @return bool
     * @throws ForbiddenHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (Yii::$app->authManager->getPermission(Yii::$app->controller->id . "/" . Yii::$app->controller->action->id)
                && !Yii::$app->user->can(Yii::$app->controller->id . "/" . Yii::$app->controller->action->id)) {
                throw new ForbiddenHttpException(Yii::t('app', 'Access denied'));
            }
        }

        return true;
    }

    /**
     * Lists all ModelOrdersComment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $queryModelOrdersComment = ModelOrdersComment::find()
            ->addOrderBy('root, lft');


        return $this->render('index', [
            'queryModelOrdersComment' => $queryModelOrdersComment,
        ]);
    }

    /**
     * Finds the ModelOrdersComment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ModelOrdersComment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ModelOrdersComment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
