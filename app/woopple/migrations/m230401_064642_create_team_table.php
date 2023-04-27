<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%team}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%department}}`
 * - `{{%user}}`
 */
class m230401_064642_create_team_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%team}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(256)->notNull(),
            'department_id' => $this->integer()->notNull(),
            'lead' => $this->integer()->notNull(),
        ]);

        // creates index for column `department_id`
        $this->createIndex(
            '{{%idx-team-department_id}}',
            '{{%team}}',
            'department_id'
        );

        // add foreign key for table `{{%department}}`
        $this->addForeignKey(
            '{{%fk-team-department_id}}',
            '{{%team}}',
            'department_id',
            '{{%department}}',
            'id',
            'CASCADE'
        );

        // creates index for column `lead`
        $this->createIndex(
            '{{%idx-team-lead}}',
            '{{%team}}',
            'lead'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-team-lead}}',
            '{{%team}}',
            'lead',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%department}}`
        $this->dropForeignKey(
            '{{%fk-team-department_id}}',
            '{{%team}}'
        );

        // drops index for column `department_id`
        $this->dropIndex(
            '{{%idx-team-department_id}}',
            '{{%team}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-team-lead}}',
            '{{%team}}'
        );

        // drops index for column `lead`
        $this->dropIndex(
            '{{%idx-team-lead}}',
            '{{%team}}'
        );

        $this->dropTable('{{%team}}');
    }
}
