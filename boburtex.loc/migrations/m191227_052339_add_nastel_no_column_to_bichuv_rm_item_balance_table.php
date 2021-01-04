<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_rm_item_balance}}`.
 */
class m191227_052339_add_nastel_no_column_to_bichuv_rm_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_rm_item_balance','nastel_no', $this->string(20));
        $this->addColumn('bichuv_rm_item_balance','bichuv_given_roll_id', $this->integer());
        $this->addColumn('bichuv_given_roll_items','model_id', $this->smallInteger(6));

        // creates index for column `model_id`
        $this->createIndex(
            '{{%idx-bichuv_given_roll_items-model_id}}',
            '{{%bichuv_given_roll_items}}',
            'model_id'
        );

        // add foreign key for table `{{%model_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_given_roll_items-model_id}}',
            '{{%bichuv_given_roll_items}}',
            'model_id',
            '{{%product}}',
            'id'
        );

        // creates index for column `bichuv_given_roll_id`
        $this->createIndex(
            '{{%idx-bichuv_rm_item_balance-bichuv_given_roll_id}}',
            '{{%bichuv_rm_item_balance}}',
            'bichuv_given_roll_id'
        );

        // add foreign key for table `{{%bichuv_given_roll_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_rm_item_balance-bichuv_given_roll_id}}',
            '{{%bichuv_rm_item_balance}}',
            'bichuv_given_roll_id',
            '{{%bichuv_given_rolls}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_given_roll_items-model_id}}',
            '{{%bichuv_given_roll_items}}'
        );

        // drops index for column `model_id`
        $this->dropIndex(
            '{{%idx-bichuv_given_roll_items-model_id}}',
            '{{%bichuv_given_roll_items}}'
        );

        // drops foreign key for table `{{%bichuv_given_roll_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_rm_item_balance-bichuv_given_roll_id}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops index for column `bichuv_given_roll_id`
        $this->dropIndex(
            '{{%idx-bichuv_rm_item_balance-bichuv_given_roll_id}}',
            '{{%bichuv_rm_item_balance}}'
        );
        $this->dropColumn('bichuv_rm_item_balance','bichuv_given_roll_id');
        $this->dropColumn('bichuv_rm_item_balance','nastel_no');
        $this->dropColumn('bichuv_given_roll_items','model_id');
    }
}
