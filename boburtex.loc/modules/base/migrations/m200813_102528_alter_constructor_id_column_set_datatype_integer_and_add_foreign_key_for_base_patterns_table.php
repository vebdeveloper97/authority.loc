<?php

use yii\db\Migration;

/**
 * Class m200813_102528_alter_constructor_id_column_set_datatype_integer_and_add_foreign_key_for_base_patterns_table
 */
class m200813_102528_alter_constructor_id_column_set_datatype_integer_and_add_foreign_key_for_base_patterns_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%base_patterns}}', 'constructor_id', $this->integer());

        $this->createIndex('idx-base_patterns-constructor_id', '{{%base_patterns}}', 'constructor_id');
        $this->addForeignKey(
            'fk-base_patterns-constructor_id',
            '{{%base_patterns}}',
            'constructor_id',
            'hr_employee',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-base_patterns-constructor_id', '{{%base_patterns}}');
        $this->dropIndex('idx-base_patterns-constructor_id', '{{%base_patterns}}');
        $this->alterColumn('{{%base_patterns}}', 'constructor_id', $this->bigInteger());
    }
}
