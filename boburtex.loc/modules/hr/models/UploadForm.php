<?php


namespace app\modules\hr\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    const SCENARIO_UPLOAD_IMAGE = 'upload-image';
    const SCENARIO_UPLOAD_DOCUMENT = 'upload-document';
    const SCENARIO_UPLOAD_FILE = 'upload-file';

    const TYPE_IMAGE = 'image';
    const TYPE_DOCUMENT = 'document';

    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * @var string
     */
    public $fileDir;

    /**
     * @var integer
     */
    public $maxFileSize = 1024000; //1024 * 1000 -> 1 megabyte

    public function init()
    {
        parent::init();
        $this->fileDir = Yii::getAlias('@webroot/uploads/files');
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attachments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            ['fileDir', 'string'],
            ['maxFileSize', 'integer'],

            // image validation
            [
                'file',
                'file',
                'mimeTypes' => [
                    'image/png',
                    'image/jpeg',
                    'image/gif',
                ],
                'skipOnEmpty' => true,
                'maxSize' => $this->maxFileSize,
                'on' => [self::SCENARIO_UPLOAD_IMAGE]
            ],

            // document validation
            [
                'file',
                'file',
                'mimeTypes' => [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ],
                'skipOnEmpty' => true,
                'maxSize' => $this->maxFileSize,
                'on' => [self::SCENARIO_UPLOAD_DOCUMENT]
            ],

            // file validation
            [
                'file',
                'file',
                'skipOnEmpty' => true,
                'maxSize' => $this->maxFileSize,
                'on' => [self::SCENARIO_UPLOAD_FILE]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'file' => Yii::t('app', 'Faylni tanlang'),
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_UPLOAD_IMAGE] = ['file', 'filePath', 'maxFileSize'];

        return $scenarios;
    }

    public function upload() {
        $fileName = null;

        if (!$this->validate())
        {
            return false;
        }

        $fileName = ($md5_hash = md5_file($this->file->tempName)) . '.' . $this->file->extension;

        $newDirectory = $this->fileDir
            . '/'
            . substr($fileName, 0, 2)
            . '/'
            . substr($fileName, 2, 2)
            . '/';

        if ( !FileHelper::createDirectory($newDirectory) )
        {
            return false;
        }


        if (is_file($newDirectory . $fileName))
        {
            Yii::debug('File not saved! Because file already exists.');

            return $fileName;
        }

        if (!$this->file->saveAs($newDirectory . $fileName)) {
            return false;
        }

        Yii::debug('File saved');

        return $this->file;
    }

    public static function getMd5FilePath (string $imageName) {
        return '/' . substr($imageName, 0 , 2) . '/' . substr($imageName, 2, 2) . '/' . $imageName;
    }
}