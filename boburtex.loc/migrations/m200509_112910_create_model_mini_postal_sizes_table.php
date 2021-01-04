<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_mini_postal_sizes}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_mini_postal}}`
 */
class m200509_112910_create_model_mini_postal_sizes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_mini_postal_sizes}}', [
            'id' => $this->primaryKey(),
            'model_mini_postal_id' => $this->integer(),
            'size_id' => $this->integer(),
            'count' => $this->integer(),
            'count_detail' => $this->integer()->comment('Detallar soni'),
        ]);

        // creates index for column `model_mini_postal_id`
        $this->createIndex(
            '{{%idx-model_mini_postal_sizes-model_mini_postal_id}}',
            '{{%model_mini_postal_sizes}}',
            'model_mini_postal_id'
        );

        // add foreign key for table `{{%model_mini_postal}}`
        $this->addForeignKey(
            '{{%fk-model_mini_postal_sizes-model_mini_postal_id}}',
            '{{%model_mini_postal_sizes}}',
            'model_mini_postal_id',
            '{{%model_mini_postal}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_mini_postal}}`
        $this->dropForeignKey(
            '{{%fk-model_mini_postal_sizes-model_mini_postal_id}}',
            '{{%model_mini_postal_sizes}}'
        );

        // drops index for column `model_mini_postal_id`
        $this->dropIndex(
            '{{%idx-model_mini_postal_sizes-model_mini_postal_id}}',
            '{{%model_mini_postal_sizes}}'
        );

        $this->dropTable('{{%model_mini_postal_sizes}}');
    }
}
