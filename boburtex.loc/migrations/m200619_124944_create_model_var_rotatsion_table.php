<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_var_rotatsion}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%brend}}`
 * - `{{%musteri}}`
 */
class m200619_124944_create_model_var_rotatsion_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_var_rotatsion}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'width' => $this->double(),
            'height' => $this->double(),
            'add_info' => $this->text(),
            'status' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'code' => $this->string(30),
            'desen_no' => $this->string(30),
            'image' => $this->string(),
            'model_view_id' => $this->integer(),
            'model_types_id' => $this->integer(),
            'brend_id' => $this->integer(),
            'musteri_id' => $this->bigInteger(),
        ]);

        // creates index for column `brend_id`
        $this->createIndex(
            '{{%idx-model_var_rotatsion-brend_id}}',
            '{{%model_var_rotatsion}}',
            'brend_id'
        );

        // add foreign key for table `{{%brend}}`
        $this->addForeignKey(
            '{{%fk-model_var_rotatsion-brend_id}}',
            '{{%model_var_rotatsion}}',
            'brend_id',
            '{{%brend}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `musteri_id`
        $this->createIndex(
            '{{%idx-model_var_rotatsion-musteri_id}}',
            '{{%model_var_rotatsion}}',
            'musteri_id'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-model_var_rotatsion-musteri_id}}',
            '{{%model_var_rotatsion}}',
            'musteri_id',
            '{{%musteri}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%brend}}`
        $this->dropForeignKey(
            '{{%fk-model_var_rotatsion-brend_id}}',
            '{{%model_var_rotatsion}}'
        );

        // drops index for column `brend_id`
        $this->dropIndex(
            '{{%idx-model_var_rotatsion-brend_id}}',
            '{{%model_var_rotatsion}}'
        );

        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-model_var_rotatsion-musteri_id}}',
            '{{%model_var_rotatsion}}'
        );

        // drops index for column `musteri_id`
        $this->dropIndex(
            '{{%idx-model_var_rotatsion-musteri_id}}',
            '{{%model_var_rotatsion}}'
        );

        $this->dropTable('{{%model_var_rotatsion}}');
    }
}
