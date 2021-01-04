<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_list}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%model_season}}`
 */
class m190923_132808_add_some_column_to_models_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_list}}', 'users_id', $this->bigInteger()->after('packaging_notes'));
        $this->addColumn('{{%models_list}}', 'model_season', $this->integer()->after('packaging_notes'));
        $this->addColumn('{{%models_list}}', 'product_details', $this->text()->after('packaging_notes'));
        $this->addColumn('{{%models_list}}', 'default_comment', $this->text()->after('packaging_notes'));
        $this->addColumn('{{%models_list}}', 'long_name', $this->string()->after('name'));

        // creates index for column `users_id`
        $this->createIndex(
            '{{%idx-models_list-users_id}}',
            '{{%models_list}}',
            'users_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-models_list-users_id}}',
            '{{%models_list}}',
            'users_id',
            '{{%users}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        // creates index for column `model_season`
        $this->createIndex(
            '{{%idx-models_list-model_season}}',
            '{{%models_list}}',
            'model_season'
        );

        // add foreign key for table `{{%model_season}}`
        $this->addForeignKey(
            '{{%fk-models_list-model_season}}',
            '{{%models_list}}',
            'model_season',
            '{{%model_season}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-models_list-users_id}}',
            '{{%models_list}}'
        );

        // drops index for column `users_id`
        $this->dropIndex(
            '{{%idx-models_list-users_id}}',
            '{{%models_list}}'
        );

        // drops foreign key for table `{{%model_season}}`
        $this->dropForeignKey(
            '{{%fk-models_list-model_season}}',
            '{{%models_list}}'
        );

        // drops index for column `model_season`
        $this->dropIndex(
            '{{%idx-models_list-model_season}}',
            '{{%models_list}}'
        );

        $this->dropColumn('{{%models_list}}', 'users_id');
        $this->dropColumn('{{%models_list}}', 'model_season');
        $this->dropColumn('{{%models_list}}', 'product_details');
        $this->dropColumn('{{%models_list}}', 'default_comment');
        $this->dropColumn('{{%models_list}}', 'long_name');
    }
}
