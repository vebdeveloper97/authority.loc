<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_patterns}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%brend}}`
 * - `{{%model_types}}`
 * - `{{%musteri}}`
 */
class m200303_053034_create_base_patterns_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_patterns}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(255),
            'name' => $this->string(255),
            'brend_id' => $this->integer(),
            'musteri_id' => $this->bigInteger(20),
            'model_type_id' => $this->integer(),
            'pattern_type' => $this->smallInteger(1)->defaultValue(1),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `code`
        $this->createIndex(
            '{{%idx-base_patterns-code}}',
            '{{%base_patterns}}',
            'code'
        );

        // creates index for column `brend_id`
        $this->createIndex(
            '{{%idx-base_patterns-brend_id}}',
            '{{%base_patterns}}',
            'brend_id'
        );

        // add foreign key for table `{{%brend}}`
        $this->addForeignKey(
            '{{%fk-base_patterns-brend_id}}',
            '{{%base_patterns}}',
            'brend_id',
            '{{%brend}}',
            'id'
        );

        // creates index for column `model_type_id`
        $this->createIndex(
            '{{%idx-base_patterns-model_type_id}}',
            '{{%base_patterns}}',
            'model_type_id'
        );

        // add foreign key for table `{{%model_type}}`
        $this->addForeignKey(
            '{{%fk-base_patterns-model_type_id}}',
            '{{%base_patterns}}',
            'model_type_id',
            '{{%model_types}}',
            'id'
        );

        // creates index for column `musteri_id`
        $this->createIndex(
            '{{%idx-base_patterns-musteri_id}}',
            '{{%base_patterns}}',
            'musteri_id'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-base_patterns-musteri_id}}',
            '{{%base_patterns}}',
            'musteri_id',
            '{{%musteri}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `code`
        $this->dropIndex(
            '{{%idx-base_patterns-code}}',
            '{{%base_patterns}}'
        );
        // drops foreign key for table `{{%brend}}`
        $this->dropForeignKey(
            '{{%fk-base_patterns-brend_id}}',
            '{{%base_patterns}}'
        );

        // drops index for column `brend_id`
        $this->dropIndex(
            '{{%idx-base_patterns-brend_id}}',
            '{{%base_patterns}}'
        );

        // drops foreign key for table `{{%model_type}}`
        $this->dropForeignKey(
            '{{%fk-base_patterns-model_type_id}}',
            '{{%base_patterns}}'
        );

        // drops index for column `model_type_id`
        $this->dropIndex(
            '{{%idx-base_patterns-model_type_id}}',
            '{{%base_patterns}}'
        );

        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-base_patterns-musteri_id}}',
            '{{%base_patterns}}'
        );

        // drops index for column `musteri_id`
        $this->dropIndex(
            '{{%idx-base_patterns-musteri_id}}',
            '{{%base_patterns}}'
        );

        $this->dropTable('{{%base_patterns}}');
    }
}
