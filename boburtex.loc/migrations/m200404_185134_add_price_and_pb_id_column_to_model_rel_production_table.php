<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_rel_production}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%pb}}`
 */
class m200404_185134_add_price_and_pb_id_column_to_model_rel_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_rel_production}}', 'price', $this->decimal(20,3)->defaultValue(0));
        $this->addColumn('{{%model_rel_production}}', 'pb_id', $this->integer());

        // creates index for column `pb_id`
        $this->createIndex(
            '{{%idx-model_rel_production-pb_id}}',
            '{{%model_rel_production}}',
            'pb_id'
        );

        // add foreign key for table `{{%pb}}`
        $this->addForeignKey(
            '{{%fk-model_rel_production-pb_id}}',
            '{{%model_rel_production}}',
            'pb_id',
            '{{%pul_birligi}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%pb}}`
        $this->dropForeignKey(
            '{{%fk-model_rel_production-pb_id}}',
            '{{%model_rel_production}}'
        );

        // drops index for column `pb_id`
        $this->dropIndex(
            '{{%idx-model_rel_production-pb_id}}',
            '{{%model_rel_production}}'
        );

        $this->dropColumn('{{%model_rel_production}}', 'price');
        $this->dropColumn('{{%model_rel_production}}', 'pb_id');
    }
}
