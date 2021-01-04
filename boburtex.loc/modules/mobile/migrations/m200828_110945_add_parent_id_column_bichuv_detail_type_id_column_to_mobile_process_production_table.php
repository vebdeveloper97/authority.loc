<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mobile_process_production}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mobile_process_production}}`
 * - `{{%bichuv_detail_types}}`
 */
class m200828_110945_add_parent_id_column_bichuv_detail_type_id_column_to_mobile_process_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%mobile_process_production}}', 'parent_id', $this->integer());
        $this->addColumn('{{%mobile_process_production}}', 'bichuv_detail_type_id', $this->integer());

        // creates index for column `parent_id`
        $this->createIndex(
            '{{%idx-mobile_process_production-parent_id}}',
            '{{%mobile_process_production}}',
            'parent_id'
        );

        // add foreign key for table `{{%mobile_process_production}}`
        $this->addForeignKey(
            '{{%fk-mobile_process_production-parent_id}}',
            '{{%mobile_process_production}}',
            'parent_id',
            '{{%mobile_process_production}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `bichuv_detail_type_id`
        $this->createIndex(
            '{{%idx-mobile_process_production-bichuv_detail_type_id}}',
            '{{%mobile_process_production}}',
            'bichuv_detail_type_id'
        );

        // add foreign key for table `{{%bichuv_detail_types}}`
        $this->addForeignKey(
            '{{%fk-mobile_process_production-bichuv_detail_type_id}}',
            '{{%mobile_process_production}}',
            'bichuv_detail_type_id',
            '{{%bichuv_detail_types}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%mobile_process_production}}`
        $this->dropForeignKey(
            '{{%fk-mobile_process_production-parent_id}}',
            '{{%mobile_process_production}}'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            '{{%idx-mobile_process_production-parent_id}}',
            '{{%mobile_process_production}}'
        );

        // drops foreign key for table `{{%bichuv_detail_types}}`
        $this->dropForeignKey(
            '{{%fk-mobile_process_production-bichuv_detail_type_id}}',
            '{{%mobile_process_production}}'
        );

        // drops index for column `bichuv_detail_type_id`
        $this->dropIndex(
            '{{%idx-mobile_process_production-bichuv_detail_type_id}}',
            '{{%mobile_process_production}}'
        );

        $this->dropColumn('{{%mobile_process_production}}', 'parent_id');
        $this->dropColumn('{{%mobile_process_production}}', 'bichuv_detail_type_id');
    }
}
