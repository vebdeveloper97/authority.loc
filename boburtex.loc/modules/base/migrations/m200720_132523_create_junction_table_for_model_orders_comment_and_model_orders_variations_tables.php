<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_comment_model_orders_variations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_comment}}`
 * - `{{%model_orders_variations}}`
 */
class m200720_132523_create_junction_table_for_model_orders_comment_and_model_orders_variations_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_comment_var_rel}}', [
            'model_orders_comment_id' => $this->integer(),
            'model_orders_variations_id' => $this->integer(),
            'type' => $this->smallInteger()->comment('1 bolsa proyek, 2 bolsa variant bekor qilingan boladi'),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
            'PRIMARY KEY(model_orders_comment_id, model_orders_variations_id)',
        ]);

        // creates index for column `model_orders_comment_id`
        $this->createIndex(
            '{{%idx-model_orders_comment_var_rel-model_orders_comment_id}}',
            '{{%model_orders_comment_var_rel}}',
            'model_orders_comment_id'
        );

        // add foreign key for table `{{%model_orders_comment}}`
        $this->addForeignKey(
            '{{%fk-model_orders_comment_var_rel-model_orders_comment_id}}',
            '{{%model_orders_comment_var_rel}}',
            'model_orders_comment_id',
            '{{%model_orders_comment}}',
            'id'
        );

        // creates index for column `model_orders_variations_id`
        $this->createIndex(
            '{{%idx-model_orders_comment_var_rel-model_orders_variations_id}}',
            '{{%model_orders_comment_var_rel}}',
            'model_orders_variations_id'
        );

        // add foreign key for table `{{%model_orders_variations}}`
        $this->addForeignKey(
            '{{%fk-model_orders_comment_var_rel-model_orders_variations_id}}',
            '{{%model_orders_comment_var_rel}}',
            'model_orders_variations_id',
            '{{%model_orders_variations}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_orders_comment}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_comment_var_rel-model_orders_comment_id}}',
            '{{%model_orders_comment_var_rel}}'
        );

        // drops index for column `model_orders_comment_id`
        $this->dropIndex(
            '{{%idx-model_orders_comment_var_rel-model_orders_comment_id}}',
            '{{%model_orders_comment_var_rel}}'
        );

        // drops foreign key for table `{{%model_orders_variations}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_comment_var_rel-model_orders_variations_id}}',
            '{{%model_orders_comment_var_rel}}'
        );

        // drops index for column `model_orders_variations_id`
        $this->dropIndex(
            '{{%idx-model_orders_comment_var_rel-model_orders_variations_id}}',
            '{{%model_orders_comment_var_rel}}'
        );

        $this->dropTable('{{%model_orders_comment_var_rel}}');
    }
}
