<?php


namespace app\modules\api\controllers;


use app\modules\api\resources\NewsResources;
use Throwable;
use Yii;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

class NewsController extends BaseApiController
{
    public $modelClass = NewsResources::class;

    /**
     * @return array|ActiveRecord[]
     * @var IndexAction
     *
     */
    public function actionIndex()
    {
        /* Yangiliklarni barchasini ko'rish */
        $model = NewsResources::find()
            ->all();

        if (!empty($model)) {
            foreach ($model as $key => $item) {
                $author = $item->getUsers()->select('username')->one();
                unset($item['author_id']);
                $item['author'] = $author ? $author->username : '';
            }
            return $model;
        }

        return [
            'success' => false,
            'message' => 'Malumot topilmadi!'
        ];
    }

    /**
     * @return NewsResources|array
     * @var CreateAction
     *
     */
    public function actionCreate()
    {
        $model = new NewsResources();
        $app = Yii::$app;

        /* Controller va Action olish uchun */
        $controller = self::getController();
        $action = self::getAction();

        if ($model->load($app->request->post(), '') && $model->save()) {

            $success = 'Id raqami ' . $model->id . ' bolgan malumot saqlandi';
            Yii::info($success, 'save');
            return $model;
        }


        if (!empty($model->errors)) {
            return [
                'success' => false,
                'message' => 'message',
                'errors'  => $model->errors
            ];
        }

        Yii::info($controller . ' - ' . $action . ' malumotlar yuklanmadi!', 'save');
        $app->response->statusCode = 400;
        return [
            'success' => false,
            'message' => 'Malumotlar yuklanmadi!'
        ];

    }

    /**
     * @return NewsResources|array|null
     * @var UpdateAction
     *
     */
    public function actionUpdate($id)
    {
        $controller = self::getController();
        $action = self::getAction();

        if (!empty($id)) {
            $model = NewsResources::findOne($id);
            if (empty($model)) {
                Yii::error($controller . '/' . $action . ' da malumotlar topilmadi!', 'save');
                Yii::$app->response->statusCode = 404;
                return [
                    'success' => false,
                    'message' => 'Malumot topilmadi'
                ];
            }
            $post_data = Yii::$app->request->post();
            if (empty($post_data)) {
                Yii::error($controller . '/' . $action . ' da malumotlar kelmadi', 'save');
                Yii::$app->response->statusCode = 404;
                return [
                    'success' => false,
                    'message' => 'Parametrlar mavjud emas!'
                ];
            }
            $model->setAttributes($post_data);
            if ($model->save()) {
                Yii::info($controller . '/' . $action . ' da malumotlar yangilandi!', 'save');
                return $model;
            }

            Yii::error($controller . '/' . $action . ' da malumotlarni yangilab bolmadi!', 'save');
            Yii::$app->response->statusCode = 401;
            return [
                'success' => false,
                'message' => $model->errors
            ];
        }

        Yii::error($controller . '/' . $action . ' parametr mavjud emas!', 'save');
        Yii::$app->response->statusCode = 404;
        return [
            'success' => false,
            'message' => 'Id paramteri mavjud emas'
        ];
    }

    /**
     *
     * @return array
     * @return array
     * @throws Throwable
     * @throws StaleObjectException
     * @var DeleteAction
     */
    public function actionDelete($id): array
    {
        $controller = self::getController();
        $action = self::getAction();

        if (!empty($id)) {
            $model = NewsResources::findOne($id);
            if (!empty($model)) {
                if ($model->delete()) {
                    $success = 'Id nomeri = ' . $id . ' \n bolgan raqamdagi malumot ochirildi!';
                    Yii::info($controller . '/' . $action . ' da ' . $success, 'save');
                    return [
                        'success' => true,
                        'message' => $success
                    ];
                }

                $success = 'Id nomeri = ' . $id . ' bolgan raqamdagi malumot ochirilmadi!';
                Yii::error($controller . '/' . $action . ' da.' . $success, 'save');
                return [
                    'success' => false,
                    'message' => $success
                ];
            }

            $success = 'Id nomeri = ' . $id . ' bolgan raqamdagi malumot topilmadi';
            Yii::error($controller . '/' . $action . ' da.' . $success, 'save');
            return [
                'success' => false,
                'message' => $success
            ];
        }

        Yii::$app->response->statusCode = 404;
        $success = 'Id Parametr mavjud emas!';
        Yii::error($controller . '/' . $action . ' da.' . $success, 'save');
        return [
            'success' => false,
            'message' => $success
        ];
    }

}