<?php

use yii\db\Migration;

/**
 * Class m200806_152200_add_nastel_no_column_to_model_rel_production
 */
class m200806_152200_add_nastel_no_column_to_model_rel_production extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_rel_production}}', 'nastel_no', $this->string(30));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200806_152200_add_nastel_no_column_to_model_rel_production cannot be reverted.\n";

        return false;
    }
    */
}
