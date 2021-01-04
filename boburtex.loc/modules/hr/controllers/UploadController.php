<?php


namespace app\modules\hr\controllers;

use app\modules\hr\models\form\UploadForm;
use Yii;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class UploadController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'upload-image' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Matn redaktori rasmlarini yuklash uchun.
     * Agar rasm muvaffaqiyatli yuklansa, json da rasmga ko'rsatilgan link qaytaradi
     *
     * @return array
     * @throws ServerErrorHttpException
     * @throws BadRequestHttpException
     */
    public function actionUploadImage()
    {
        $uploadForm = new UploadForm(['scenario' => UploadForm::SCENARIO_UPLOAD_IMAGE]);

        if ($uploadForm->file = UploadedFile::getInstanceByName('file')) {
            if ($fileNameWithPath = $uploadForm->uploadFile()) {
                // file uploaded
                Yii::$app->getResponse()->format = Response::FORMAT_JSON;

                return [
                    'location' => Yii::getAlias('@web/uploads/images') . UploadForm::getMd5FilePath($fileNameWithPath),
                ];
            }
            if ($uploadForm->hasErrors('file')) {
                Yii::debug($uploadForm->file->type, 'image-type');
                Yii::debug($uploadForm->file->extension, 'image-extension');
                Yii::error($uploadForm->getErrors(), 'image-save');
                throw new BadRequestHttpException('Invalid extension or file size');
            }
        }

        throw new ServerErrorHttpException();
    }
}