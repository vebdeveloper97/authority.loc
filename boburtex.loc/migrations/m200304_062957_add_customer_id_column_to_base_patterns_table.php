<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%base_patterns}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%customer}}`
 */
class m200304_062957_add_customer_id_column_to_base_patterns_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%base_patterns}}', 'customer_id', $this->bigInteger());

        $this->addColumn('{{%base_patterns}}','counter', $this->integer());
        // creates index for column `customer_id`
        $this->createIndex(
            '{{%idx-base_patterns-customer_id}}',
            '{{%base_patterns}}',
            'customer_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-base_patterns-customer_id}}',
            '{{%base_patterns}}',
            'customer_id',
            '{{%users}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-base_patterns-customer_id}}',
            '{{%base_patterns}}'
        );

        // drops index for column `customer_id`
        $this->dropIndex(
            '{{%idx-base_patterns-customer_id}}',
            '{{%base_patterns}}'
        );

        $this->dropColumn('{{%base_patterns}}', 'customer_id');
        $this->dropColumn('{{%base_patterns}}', 'counter');
    }
}
