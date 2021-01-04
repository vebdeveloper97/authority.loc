<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_acs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders}}`
 */
class m200716_140310_add_model_orders_id_column_to_models_acs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_acs}}', 'model_orders_id', $this->integer());

        // creates index for column `model_orders_id`
        $this->createIndex(
            '{{%idx-models_acs-model_orders_id}}',
            '{{%models_acs}}',
            'model_orders_id'
        );

        // add foreign key for table `{{%model_orders}}`
        $this->addForeignKey(
            '{{%fk-models_acs-model_orders_id}}',
            '{{%models_acs}}',
            'model_orders_id',
            '{{%model_orders}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_orders}}`
        $this->dropForeignKey(
            '{{%fk-models_acs-model_orders_id}}',
            '{{%models_acs}}'
        );

        // drops index for column `model_orders_id`
        $this->dropIndex(
            '{{%idx-models_acs-model_orders_id}}',
            '{{%models_acs}}'
        );

        $this->dropColumn('{{%models_acs}}', 'model_orders_id');
    }
}
