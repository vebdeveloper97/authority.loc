<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_var_prints}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%brend}}`
 * - `{{%musteri}}`
 */
class m200306_050135_add_some_fields_column_to_model_var_prints_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_var_prints}}', 'brend_id', $this->integer());
        $this->addColumn('{{%model_var_prints}}', 'musteri_id', $this->bigInteger());

        // creates index for column `brend_id`
        $this->createIndex(
            '{{%idx-model_var_prints-brend_id}}',
            '{{%model_var_prints}}',
            'brend_id'
        );

        // add foreign key for table `{{%brend}}`
        $this->addForeignKey(
            '{{%fk-model_var_prints-brend_id}}',
            '{{%model_var_prints}}',
            'brend_id',
            '{{%brend}}',
            'id'
        );

        // creates index for column `musteri_id`
        $this->createIndex(
            '{{%idx-model_var_prints-musteri_id}}',
            '{{%model_var_prints}}',
            'musteri_id'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-model_var_prints-musteri_id}}',
            '{{%model_var_prints}}',
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
        // drops foreign key for table `{{%brend}}`
        $this->dropForeignKey(
            '{{%fk-model_var_prints-brend_id}}',
            '{{%model_var_prints}}'
        );

        // drops index for column `brend_id`
        $this->dropIndex(
            '{{%idx-model_var_prints-brend_id}}',
            '{{%model_var_prints}}'
        );

        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-model_var_prints-musteri_id}}',
            '{{%model_var_prints}}'
        );

        // drops index for column `musteri_id`
        $this->dropIndex(
            '{{%idx-model_var_prints-musteri_id}}',
            '{{%model_var_prints}}'
        );

        $this->dropColumn('{{%model_var_prints}}', 'brend_id');
        $this->dropColumn('{{%model_var_prints}}', 'musteri_id');
    }
}
