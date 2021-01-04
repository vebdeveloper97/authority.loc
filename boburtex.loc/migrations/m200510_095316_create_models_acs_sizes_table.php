<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%models_acs_sizes}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_acs}}`
 * - `{{%size}}`
 */
class m200510_095316_create_models_acs_sizes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%models_acs_sizes}}', [
            'id' => $this->primaryKey(),
            'models_acs_id' => $this->integer(),
            'size_id' => $this->integer(),
        ]);

        // creates index for column `models_acs_id`
        $this->createIndex(
            '{{%idx-models_acs_sizes-models_acs_id}}',
            '{{%models_acs_sizes}}',
            'models_acs_id'
        );

        // add foreign key for table `{{%models_acs}}`
        $this->addForeignKey(
            '{{%fk-models_acs_sizes-models_acs_id}}',
            '{{%models_acs_sizes}}',
            'models_acs_id',
            '{{%models_acs}}',
            'id',
            'CASCADE'
        );

        // creates index for column `size_id`
        $this->createIndex(
            '{{%idx-models_acs_sizes-size_id}}',
            '{{%models_acs_sizes}}',
            'size_id'
        );

        // add foreign key for table `{{%size}}`
        $this->addForeignKey(
            '{{%fk-models_acs_sizes-size_id}}',
            '{{%models_acs_sizes}}',
            'size_id',
            '{{%size}}',
            'id',
            'CASCADE'
        );
        $this->addColumn('{{%models_acs}}','for_all_sizes', $this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%models_acs}}`
        $this->dropForeignKey(
            '{{%fk-models_acs_sizes-models_acs_id}}',
            '{{%models_acs_sizes}}'
        );

        // drops index for column `models_acs_id`
        $this->dropIndex(
            '{{%idx-models_acs_sizes-models_acs_id}}',
            '{{%models_acs_sizes}}'
        );

        // drops foreign key for table `{{%size}}`
        $this->dropForeignKey(
            '{{%fk-models_acs_sizes-size_id}}',
            '{{%models_acs_sizes}}'
        );

        // drops index for column `size_id`
        $this->dropIndex(
            '{{%idx-models_acs_sizes-size_id}}',
            '{{%models_acs_sizes}}'
        );

        $this->dropTable('{{%models_acs_sizes}}');
        $this->dropColumn('{{%models_acs}}','for_all_sizes');
    }
}
