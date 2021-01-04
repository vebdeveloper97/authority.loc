<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_given_rolls}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 */
class m200424_085600_add_nastel_user_id_column_to_bichuv_given_rolls_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_given_rolls}}', 'nastel_user_id', $this->bigInteger());

        // creates index for column `nastel_user_id`
        $this->createIndex(
            '{{%idx-bichuv_given_rolls-nastel_user_id}}',
            '{{%bichuv_given_rolls}}',
            'nastel_user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-bichuv_given_rolls-nastel_user_id}}',
            '{{%bichuv_given_rolls}}',
            'nastel_user_id',
            '{{%users}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_given_rolls-nastel_user_id}}',
            '{{%bichuv_given_rolls}}'
        );

        // drops index for column `nastel_user_id`
        $this->dropIndex(
            '{{%idx-bichuv_given_rolls-nastel_user_id}}',
            '{{%bichuv_given_rolls}}'
        );

        $this->dropColumn('{{%bichuv_given_rolls}}', 'nastel_user_id');
    }
}
