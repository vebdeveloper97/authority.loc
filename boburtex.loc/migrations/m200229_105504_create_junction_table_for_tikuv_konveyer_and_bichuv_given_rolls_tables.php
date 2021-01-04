<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_konveyer_bichuv_given_rolls}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tikuv_konveyer}}`
 * - `{{%bichuv_given_rolls}}`
 */
class m200229_105504_create_junction_table_for_tikuv_konveyer_and_bichuv_given_rolls_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tikuv_konveyer_bichuv_given_rolls}}', [
            'tikuv_konveyer_id' => $this->integer(),
            'bichuv_given_rolls_id' => $this->integer(),
            'PRIMARY KEY(tikuv_konveyer_id, bichuv_given_rolls_id)',
            'indeks' => $this->double(8,7),
            'created_by' => $this->integer(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'updated_at' => $this->integer(),
            'created_at' => $this->integer()
        ]);

        // creates index for column `tikuv_konveyer_id`
        $this->createIndex(
            '{{%idx-tikuv_konveyer_bichuv_given_rolls-tikuv_konveyer_id}}',
            '{{%tikuv_konveyer_bichuv_given_rolls}}',
            'tikuv_konveyer_id'
        );

        // add foreign key for table `{{%tikuv_konveyer}}`
        $this->addForeignKey(
            '{{%fk-tikuv_konveyer_bichuv_given_rolls-tikuv_konveyer_id}}',
            '{{%tikuv_konveyer_bichuv_given_rolls}}',
            'tikuv_konveyer_id',
            '{{%tikuv_konveyer}}',
            'id',
            'CASCADE'
        );

        // creates index for column `bichuv_given_rolls_id`
        $this->createIndex(
            '{{%idx-tikuv_konveyer_bichuv_given_rolls-bichuv_given_rolls_id}}',
            '{{%tikuv_konveyer_bichuv_given_rolls}}',
            'bichuv_given_rolls_id'
        );

        // add foreign key for table `{{%bichuv_given_rolls}}`
        $this->addForeignKey(
            '{{%fk-tikuv_konveyer_bichuv_given_rolls-bichuv_given_rolls_id}}',
            '{{%tikuv_konveyer_bichuv_given_rolls}}',
            'bichuv_given_rolls_id',
            '{{%bichuv_given_rolls}}',
            'id',
            'CASCADE'
        );
        // creates index for column `indeks`
        $this->createIndex(
            '{{%idx-tikuv_konveyer_bichuv_given_rolls-indeks}}',
            '{{%tikuv_konveyer_bichuv_given_rolls}}',
            'indeks'
        );
        $this->execute("ALTER TABLE `tikuv_konveyer_bichuv_given_rolls` CHANGE `indeks` `indeks` DOUBLE(8,7) NULL DEFAULT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%tikuv_konveyer}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_konveyer_bichuv_given_rolls-tikuv_konveyer_id}}',
            '{{%tikuv_konveyer_bichuv_given_rolls}}'
        );

        // drops index for column `tikuv_konveyer_id`
        $this->dropIndex(
            '{{%idx-tikuv_konveyer_bichuv_given_rolls-tikuv_konveyer_id}}',
            '{{%tikuv_konveyer_bichuv_given_rolls}}'
        );

        // drops foreign key for table `{{%bichuv_given_rolls}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_konveyer_bichuv_given_rolls-bichuv_given_rolls_id}}',
            '{{%tikuv_konveyer_bichuv_given_rolls}}'
        );

        // drops index for column `bichuv_given_rolls_id`
        $this->dropIndex(
            '{{%idx-tikuv_konveyer_bichuv_given_rolls-bichuv_given_rolls_id}}',
            '{{%tikuv_konveyer_bichuv_given_rolls}}'
        );
        // drops index for column `indeks`
        $this->dropIndex(
            '{{%idx-tikuv_konveyer_bichuv_given_rolls-indeks}}',
            '{{%tikuv_konveyer_bichuv_given_rolls}}'
        );
        $this->dropTable('{{%tikuv_konveyer_bichuv_given_rolls}}');
    }
}
