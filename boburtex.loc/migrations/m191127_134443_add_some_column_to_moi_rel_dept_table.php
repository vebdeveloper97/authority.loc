<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%moi_rel_dept}}`.
 */
class m191127_134443_add_some_column_to_moi_rel_dept_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('moi_rel_dept','quantity', $this->decimal(20,3));
        $this->addColumn('moi_rel_dept','unit_id', $this->integer()->defaultValue(2));
        $this->addColumn('moi_rel_dept','company_categories_id', $this->integer()->after('model_orders_items_id'));
        $this->addColumn('moi_rel_dept','musteri_id', $this->integer()->after('model_orders_items_id'));
        $this->addColumn('moi_rel_dept','model_orders_planning_id', $this->integer());
        $this->addColumn('moi_rel_dept','is_own', $this->smallInteger()->defaultValue(1));
        $this->addColumn('moi_rel_dept','status', $this->smallInteger(6)->defaultValue(1));
        $this->addColumn('moi_rel_dept','created_by', $this->integer());
        $this->addColumn('moi_rel_dept','created_at', $this->integer());
        $this->addColumn('moi_rel_dept','updated_at', $this->integer());
        // creates index for column `model_orders_planning_id`
        $this->createIndex(
            '{{%idx-moi_rel_dept-model_orders_planning_id}}',
            '{{%moi_rel_dept}}',
            'model_orders_planning_id'
        );

        // add foreign key for table `{{%moi_rel_dept}}`
        $this->addForeignKey(
            '{{%fk-moi_rel_dept-model_orders_planning_id}}',
            '{{%moi_rel_dept}}',
            'model_orders_planning_id',
            '{{%model_orders_planning}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%moi_rel_dept}}`
        $this->dropForeignKey(
            '{{%fk-moi_rel_dept-model_orders_planning_id}}',
            '{{%moi_rel_dept}}'
        );

        // drops index for column `model_orders_planning_id`
        $this->dropIndex(
            '{{%idx-moi_rel_dept-model_orders_planning_id}}',
            '{{%moi_rel_dept}}'
        );
        $this->dropColumn('moi_rel_dept','model_orders_planning_id');
        $this->dropColumn('moi_rel_dept','quantity');
        $this->dropColumn('moi_rel_dept','unit_id');
        $this->dropColumn('moi_rel_dept','musteri_id');
        $this->dropColumn('moi_rel_dept','is_own');
        $this->dropColumn('moi_rel_dept','company_categories_id');
        $this->dropColumn('moi_rel_dept','status');
        $this->dropColumn('moi_rel_dept','created_by');
        $this->dropColumn('moi_rel_dept','created_at');
        $this->dropColumn('moi_rel_dept','updated_at');
    }
}
