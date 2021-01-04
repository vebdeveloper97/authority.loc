<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%company_categories}}`.
 */
class m191128_073445_create_company_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%company_categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'order' => $this->integer(),
            'type' => $this->smallInteger()->defaultValue(1),
            'token' => $this->string(30),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
        $this->upsert('{{%company_categories}}', ['name'=>'Toquv','order'=>1,'token'=>'TOQUV','type'=>1,'created_at'=>1574928000],true);
        $this->upsert('{{%company_categories}}', ['name'=>"Bo'yoq",'order'=>2,'token'=>'BOYOQ','type'=>2,'created_at'=>1574928000],true);
        $this->upsert('{{%company_categories}}', ['name'=>'Bichuv','order'=>3,'token'=>'BICHUV','type'=>3,'created_at'=>1574928000],true);
        $this->upsert('{{%company_categories}}', ['name'=>'Tikuv','order'=>4,'token'=>'TIKUV','type'=>4,'created_at'=>1574928000],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%company_categories}}');
    }
}
