<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%group}}`.
 */
class m250603_144604_create_group_table extends Migration
{
    public function up()
    {
        $this->createTable('group', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'parent_id' => $this->integer()->null(),
            'is_deleted' => $this->boolean()->notNull()->defaultValue(false),
        ]);

        // Külső kulcs: parent_id → group.id (önmagára hivatkozik)
        $this->addForeignKey(
            'fk-group-parent_id',
            'group',
            'parent_id',
            'group',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-group-parent_id', 'group');
        $this->dropTable('group');
    }
}
