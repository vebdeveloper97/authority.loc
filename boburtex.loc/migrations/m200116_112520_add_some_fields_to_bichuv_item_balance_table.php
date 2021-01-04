<?php

use yii\db\Migration;

/**
 * Class m200116_112520_add_some_fields_to_bichuv_item_balance_table
 */
class m200116_112520_add_some_fields_to_bichuv_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_item_balance','from_department', $this->integer());

        // creates index for column `from_department`
        $this->createIndex(
            '{{%idx-bichuv_item_balance-from_department}}',
            '{{%bichuv_item_balance}}',
            'from_department'
        );

        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-bichuv_item_balance-from_department}}',
            '{{%bichuv_item_balance}}',
            'from_department',
            '{{%toquv_departments}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%from_department}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_item_balance-from_department}}',
            '{{%bichuv_item_balance}}'
        );

        // drops index for column `from_department`
        $this->dropIndex(
            '{{%idx-bichuv_item_balance-from_department}}',
            '{{%bichuv_item_balance}}'
        );
        $this->dropColumn('bichuv_item_balance','from_department');
    }


}
