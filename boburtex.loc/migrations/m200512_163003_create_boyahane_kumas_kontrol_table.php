<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%boyahane_kumas_kontrol}}`.
 */
class m200512_163003_create_boyahane_kumas_kontrol_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%boyahane_kumas_kontrol}}', [
            'id' => $this->bigPrimaryKey(),
            'part_id' => $this->bigInteger(), // boyahane_siparis_part.id
            'subpart_id' => $this->bigInteger(), // boyahane_siparis_subpart.id
            'ram_en' => $this->integer(3),
            'ram_grm' => $this->integer(3),
            'ram_chekmezlik_x' => $this->decimal(4,2),
            'ram_chekmezlik_y' => $this->decimal(4,2),
            'ram_may_don' => $this->decimal(5,2),
            'ram_haslik' => $this->string(100),
            'sanfor_en' => $this->integer(3),
            'sanfor_grm' => $this->integer(3),
            'sanfor_chekmezlik_x' => $this->decimal(4,2),
            'sanfor_chekmezlik_y' => $this->decimal(4,2),
            'sanfor_may_don' => $this->decimal(5,2),
            'sanfor_haslik' => $this->string(100),
            'kurutma_en' => $this->integer(3),
            'kurutma_grm' => $this->integer(3),
            'kurutma_chekmezlik_x' => $this->decimal(4,2),
            'kurutma_chekmezlik_y' => $this->decimal(4,2),
            'kurutma_may_don' => $this->decimal(5,2),
            'kurutma_haslik' => $this->string(100),
            'onaylayan_adi' => $this->string(100)->notNull(),
            'onaylayan_unvani' => $this->string(100)->notNull(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createIndex(
            '{{%idx-boyahane_kumas_kontrol-boyahane_siparis_part}}',
            '{{%boyahane_kumas_kontrol}}',
            'part_id'
        );
        $this->addForeignKey(
            '{{%fk-boyahane_kumas_kontrol-boyahane_siparis_part}}',
            '{{%boyahane_kumas_kontrol}}',
            'part_id',
            '{{%boyahane_siparis_part}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-boyahane_kumas_kontrol-boyahane_siparis_subpart}}',
            '{{%boyahane_kumas_kontrol}}',
            'subpart_id'
        );
        $this->addForeignKey(
            '{{%fk-boyahane_kumas_kontrol-boyahane_siparis_subpart}}',
            '{{%boyahane_kumas_kontrol}}',
            'subpart_id',
            '{{%boyahane_siparis_subpart}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-boyahane_kumas_kontrol-users-created_by}}',
            '{{%boyahane_kumas_kontrol}}',
            'created_by'
        );
        $this->addForeignKey(
            '{{%fk-boyahane_kumas_kontrol-users-created_by}}',
            '{{%boyahane_kumas_kontrol}}',
            'created_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-boyahane_kumas_kontrol-users-updated_by}}',
            '{{%boyahane_kumas_kontrol}}',
            'updated_by'
        );
        $this->addForeignKey(
            '{{%fk-boyahane_kumas_kontrol-users-updated_by}}',
            '{{%boyahane_kumas_kontrol}}',
            'updated_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey(
            '{{%fk-boyahane_kumas_kontrol-boyahane_siparis_subpart}}',
            '{{%boyahane_kumas_kontrol}}'
        );
        $this->dropIndex(
            '{{%idx-boyahane_kumas_kontrol-boyahane_siparis_subpart}}',
            '{{%boyahane_kumas_kontrol}}'
        );

        $this->dropForeignKey(
            '{{%fk-boyahane_kumas_kontrol-boyahane_siparis_part}}',
            '{{%boyahane_kumas_kontrol}}'
        );;
        $this->dropIndex(
            '{{%idx-boyahane_kumas_kontrol-boyahane_siparis_part}}',
            '{{%boyahane_kumas_kontrol}}'
        );

        $this->dropForeignKey(
            '{{%fk-boyahane_kumas_kontrol-users-created_by}}',
            '{{%boyahane_kumas_kontrol}}'
        );
        $this->dropIndex(
            '{{%idx-boyahane_kumas_kontrol-users-created_by}}',
            '{{%boyahane_kumas_kontrol}}'
        );

        $this->dropForeignKey(
            '{{%fk-boyahane_kumas_kontrol-users-updated_by}}',
            '{{%boyahane_kumas_kontrol}}'
        );
        $this->dropIndex(
            '{{%idx-boyahane_kumas_kontrol-users-updated_by}}',
            '{{%boyahane_kumas_kontrol}}'
        );


        $this->dropTable('{{%boyahane_kumas_kontrol}}');

    }
}
