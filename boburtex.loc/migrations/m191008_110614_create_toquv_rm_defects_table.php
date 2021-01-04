<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_rm_defects}}`.
 */
class m191008_110614_create_toquv_rm_defects_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_rm_defects}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(150),
        ]);
        $this->upsert('{{%toquv_rm_defects}}',['id'=>1,'name'=>'Teshik'],true);
        $this->upsert('{{%toquv_rm_defects}}',['id'=>2,'name'=>'Yirtiq'],true);
        $this->upsert('{{%toquv_rm_defects}}',['id'=>3,'name'=>'Qochiq'],true);
        $this->upsert('{{%toquv_rm_defects}}',['id'=>4,'name'=>'Likra qochiq'],true);
        $this->upsert('{{%toquv_rm_defects}}',['id'=>5,'name'=>'Igna sinig‘i'],true);
        $this->upsert('{{%toquv_rm_defects}}',['id'=>6,'name'=>'Abraj'],true);
        $this->upsert('{{%toquv_rm_defects}}',['id'=>7,'name'=>'Yog‘ tomchisi'],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%toquv_rm_defects}}');
    }
}
