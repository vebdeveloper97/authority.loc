<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_planning_boyoq_service}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mop}}`
 * - `{{%boyahane_services}}`
 */
class m200824_165024_create_model_orders_planning_boyoq_service_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_planning_boyoq_service}}', [
            'id' => $this->primaryKey(),
            'mop_id' => $this->integer(),
            'boyahane_services_id' => $this->integer(),
            'status' => $this->tinyInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `mop_id`
        $this->createIndex(
            '{{%idx-model_orders_planning_boyoq_service-mop_id}}',
            '{{%model_orders_planning_boyoq_service}}',
            'mop_id'
        );

        // add foreign key for table `{{%mop}}`
        $this->addForeignKey(
            '{{%fk-model_orders_planning_boyoq_service-mop_id}}',
            '{{%model_orders_planning_boyoq_service}}',
            'mop_id',
            '{{%model_orders_planning}}',
            'id',
            'CASCADE'
        );

        // creates index for column `boyahane_services_id`
        $this->createIndex(
            '{{%idx-model_orders_planning_boyoq_service-boyahane_services_id}}',
            '{{%model_orders_planning_boyoq_service}}',
            'boyahane_services_id'
        );

        // add foreign key for table `{{%boyahane_services}}`
        $this->addForeignKey(
            '{{%fk-model_orders_planning_boyoq_service-boyahane_services_id}}',
            '{{%model_orders_planning_boyoq_service}}',
            'boyahane_services_id',
            '{{%boyahane_services}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%mop}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_planning_boyoq_service-mop_id}}',
            '{{%model_orders_planning_boyoq_service}}'
        );

        // drops index for column `mop_id`
        $this->dropIndex(
            '{{%idx-model_orders_planning_boyoq_service-mop_id}}',
            '{{%model_orders_planning_boyoq_service}}'
        );

        // drops foreign key for table `{{%boyahane_services}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_planning_boyoq_service-boyahane_services_id}}',
            '{{%model_orders_planning_boyoq_service}}'
        );

        // drops index for column `boyahane_services_id`
        $this->dropIndex(
            '{{%idx-model_orders_planning_boyoq_service-boyahane_services_id}}',
            '{{%model_orders_planning_boyoq_service}}'
        );

        $this->dropTable('{{%model_orders_planning_boyoq_service}}');
    }
}
