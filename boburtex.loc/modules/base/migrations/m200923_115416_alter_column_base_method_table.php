<?php

use yii\db\Migration;

/**
 * Class m200923_115416_alter_column_base_method_table
 */
class m200923_115416_alter_column_base_method_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('base_method', 'doc_number', $this->char(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('base_method', 'doc_number', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200923_115416_alter_column_base_method_table cannot be reverted.\n";

        return false;
    }
    */
}
