<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_item_balance}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%wms_department_area}}`
 * - `{{%wms_department_area}}`
 * - `{{%wms_department_area}}`
 */
class m200630_092640_add_dep_area_and_to_area_and_from_area_columns_to_bichuv_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_item_balance}}', 'dep_area', $this->integer());
        $this->addColumn('{{%bichuv_item_balance}}', 'to_area', $this->integer());
        $this->addColumn('{{%bichuv_item_balance}}', 'from_area', $this->integer());

        // creates index for column `dep_area`
        $this->createIndex(
            '{{%idx-bichuv_item_balance-dep_area}}',
            '{{%bichuv_item_balance}}',
            'dep_area'
        );

        // add foreign key for table `{{%wms_department_area}}`
        $this->addForeignKey(
            '{{%fk-bichuv_item_balance-dep_area}}',
            '{{%bichuv_item_balance}}',
            'dep_area',
            '{{%wms_department_area}}',
            'id'
        );

        // creates index for column `to_area`
        $this->createIndex(
            '{{%idx-bichuv_item_balance-to_area}}',
            '{{%bichuv_item_balance}}',
            'to_area'
        );

        // add foreign key for table `{{%wms_department_area}}`
        $this->addForeignKey(
            '{{%fk-bichuv_item_balance-to_area}}',
            '{{%bichuv_item_balance}}',
            'to_area',
            '{{%wms_department_area}}',
            'id'
        );

        // creates index for column `from_area`
        $this->createIndex(
            '{{%idx-bichuv_item_balance-from_area}}',
            '{{%bichuv_item_balance}}',
            'from_area'
        );

        // add foreign key for table `{{%wms_department_area}}`
        $this->addForeignKey(
            '{{%fk-bichuv_item_balance-from_area}}',
            '{{%bichuv_item_balance}}',
            'from_area',
            '{{%wms_department_area}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%wms_department_area}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_item_balance-dep_area}}',
            '{{%bichuv_item_balance}}'
        );

        // drops index for column `dep_area`
        $this->dropIndex(
            '{{%idx-bichuv_item_balance-dep_area}}',
            '{{%bichuv_item_balance}}'
        );

        // drops foreign key for table `{{%wms_department_area}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_item_balance-to_area}}',
            '{{%bichuv_item_balance}}'
        );

        // drops index for column `to_area`
        $this->dropIndex(
            '{{%idx-bichuv_item_balance-to_area}}',
            '{{%bichuv_item_balance}}'
        );

        // drops foreign key for table `{{%wms_department_area}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_item_balance-from_area}}',
            '{{%bichuv_item_balance}}'
        );

        // drops index for column `from_area`
        $this->dropIndex(
            '{{%idx-bichuv_item_balance-from_area}}',
            '{{%bichuv_item_balance}}'
        );

        $this->dropColumn('{{%bichuv_item_balance}}', 'dep_area');
        $this->dropColumn('{{%bichuv_item_balance}}', 'to_area');
        $this->dropColumn('{{%bichuv_item_balance}}', 'from_area');
    }
}
