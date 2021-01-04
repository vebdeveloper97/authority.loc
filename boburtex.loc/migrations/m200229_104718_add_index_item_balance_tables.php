<?php

use yii\db\Migration;

/**
 * Class m200229_104718_add_index_item_balance_tables
 */
class m200229_104718_add_index_item_balance_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // creates index for column `entity_id`
        $this->createIndex(
            '{{%idx-tikuv_slice_item_balance-entity_id}}',
            '{{%tikuv_slice_item_balance}}',
            'entity_id'
        );

        // creates index for column `entity_id`
        $this->createIndex(
            '{{%idx-bichuv_rm_item_balance-entity_id}}',
            '{{%bichuv_rm_item_balance}}',
            'entity_id'
        );

        // creates index for column `entity_id`
        $this->createIndex(
            '{{%idx-toquv_item_balance-entity_id}}',
            '{{%toquv_item_balance}}',
            'entity_id'
        );

        // creates index for column `entity_id`
        $this->createIndex(
            '{{%idx-bichuv_item_balance-entity_id}}',
            '{{%bichuv_item_balance}}',
            'entity_id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `entity_id`
        $this->dropIndex(
            '{{%idx-tikuv_slice_item_balance-entity_id}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops index for column `entity_id`
        $this->dropIndex(
            '{{%idx-bichuv_item_balance-entity_id}}',
            '{{%bichuv_item_balance}}'
        );

        // drops index for column `entity_id`
        $this->dropIndex(
            '{{%idx-bichuv_rm_item_balance-entity_id}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops index for column `entity_id`
        $this->dropIndex(
            '{{%idx-toquv_item_balance-entity_id}}',
            '{{%toquv_item_balance}}'
        );
    }

}
