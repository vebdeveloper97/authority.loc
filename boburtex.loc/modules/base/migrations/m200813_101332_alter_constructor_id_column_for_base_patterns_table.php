<?php

use yii\db\Migration;

/**
 * Class m200813_101332_alter_constructor_id_column_for_base_patterns_table
 */
class m200813_101332_alter_constructor_id_column_for_base_patterns_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-base_patterns-constructor_id', '{{%base_patterns}}');
        $this->dropIndex('idx-base_patterns-constructor_id', '{{%base_patterns}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createIndex('idx-base_patterns-constructor_id', '{{%base_patterns}}', 'constructor_id');
        $this->addForeignKey(
            'fk-base_patterns-constructor_id',
            '{{%base_patterns}}',
            'constructor_id',
            'users',
            'id'
        );
    }
}
