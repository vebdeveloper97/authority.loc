<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_mini_postal}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_list}}`
 */
class m200509_111511_create_model_mini_postal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_mini_postal}}', [
            'id' => $this->primaryKey(),
            'models_list_id' => $this->integer(),
            'name' => $this->string(),
            'users_id' => $this->integer(),
            'eni' => $this->double(),
            'uzunligi' => $this->double(),
            'samaradorlik' => $this->double(),
            'type' => $this->smallInteger()->comment('Turi')->defaultValue(1),
            'count_items' => $this->integer()->comment('Elementlar soni'),
            'total_patterns' => $this->integer()->comment('Lekalalar soni'),
            'total_patterns_loid' => $this->integer()->comment('Lekala qismlari soni'),
            'specific_weight' => $this->double()->comment('O‘ziga xos og`irlik'),
            'total_weight' => $this->double()->comment('Umumiy og`irlik'),
            'used_weight' => $this->double()->comment('Ishlatilgan og`irlik'),
            'lossed_weight' => $this->double()->comment('Yo`qotilgan og`irlik'),
            'size_collection_id' => $this->integer(),
            'cost_surface' => $this->decimal(20,3)->comment(`Ishlatilgan yuza`),
            'cost_weight' => $this->decimal(20,3)->comment('Ishlatilgan og`irlik'),
            'loss_surface' => $this->decimal(20,3)->comment('Yo`qotishlar yuzasi'),
            'loss_weight' => $this->decimal(20,3)->comment('Yo`qotishlar og`irligi'),
            'spent_surface' => $this->decimal(20,3)->comment(`Sarflangan yuza`),
            'spent_weight' => $this->decimal(20,3)->comment('Sarflangan og`irlik'),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `models_list_id`
        $this->createIndex(
            '{{%idx-model_mini_postal-models_list_id}}',
            '{{%model_mini_postal}}',
            'models_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-model_mini_postal-models_list_id}}',
            '{{%model_mini_postal}}',
            'models_list_id',
            '{{%models_list}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-model_mini_postal-models_list_id}}',
            '{{%model_mini_postal}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-model_mini_postal-models_list_id}}',
            '{{%model_mini_postal}}'
        );

        $this->dropTable('{{%model_mini_postal}}');
    }
}
