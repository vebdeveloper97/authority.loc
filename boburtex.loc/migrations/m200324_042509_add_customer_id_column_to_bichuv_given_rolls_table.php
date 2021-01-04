<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_given_rolls}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%musteri}}`
 */
class m200324_042509_add_customer_id_column_to_bichuv_given_rolls_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_given_rolls}}', 'customer_id', $this->bigInteger());

        // creates index for column `customer_id`
        $this->createIndex(
            '{{%idx-bichuv_given_rolls-customer_id}}',
            '{{%bichuv_given_rolls}}',
            'customer_id'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-bichuv_given_rolls-customer_id}}',
            '{{%bichuv_given_rolls}}',
            'customer_id',
            '{{%musteri}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_given_rolls-customer_id}}',
            '{{%bichuv_given_rolls}}'
        );

        // drops index for column `customer_id`
        $this->dropIndex(
            '{{%idx-bichuv_given_rolls-customer_id}}',
            '{{%bichuv_given_rolls}}'
        );

        $this->dropColumn('{{%bichuv_given_rolls}}', 'customer_id');
    }
}
