app/components/behaviors papkasiga arxivdan chiqariladi

Migratsiyani ishlatish      : yii migrate --migrationPath=@app/components/behaviors/log/migration
Migratsiyani bekor qilish   : yii migrate/down --migrationPath=@app/components/behaviors/log/migration

Modelda ignor qilinadigan attributlar yozilsa, ular logda yozlimaydi:

public $logIgnoredAttributes = ['created_at','updated_at','created_by'];

Modelda behaviors yozish kerak bo'ladi:

public function behaviors()
{
    return array_merge(parent::behaviors(),[
        app\components\behaviors\log\LogBehavior::className()
    ]);
}