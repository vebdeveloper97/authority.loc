<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%goods}}`.
 */
class m200302_194900_add_model_var_column_to_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('goods','model_var', $this->integer());
        $this->addColumn('tikuv_doc','is_change_model', $this->smallInteger(1)->defaultValue(1));

        // creates index for column `model_var`
        $this->createIndex(
            '{{%idx-goods-model_var}}',
            '{{%goods}}',
            'model_var'
        );
        // add foreign key for table `{{%models_variations}}`
        $this->addForeignKey(
            '{{%fk-goods-model_var}}',
            '{{%goods}}',
            'model_var',
            '{{%models_variations}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%product}}`
        $this->dropForeignKey(
            '{{%fk-goods-model_var}}',
            '{{%goods}}'
        );
        // drops index for column `model_var`
        $this->dropIndex(
            '{{%idx-goods-model_var}}',
            '{{%goods}}'
        );
        $this->dropColumn('tikuv_doc','is_change_model');
        $this->dropColumn('goods','model_var');
    }
}
