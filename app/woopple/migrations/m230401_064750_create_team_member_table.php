<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%team_member}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%team}}`
 * - `{{%user}}`
 */
class m230401_064750_create_team_member_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%team_member}}', [
            'id' => $this->primaryKey(),
            'team_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `team_id`
        $this->createIndex(
            '{{%idx-team_member-team_id}}',
            '{{%team_member}}',
            'team_id'
        );

        // add foreign key for table `{{%team}}`
        $this->addForeignKey(
            '{{%fk-team_member-team_id}}',
            '{{%team_member}}',
            'team_id',
            '{{%team}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-team_member-user_id}}',
            '{{%team_member}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-team_member-user_id}}',
            '{{%team_member}}',
            'user_id',
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
        // drops foreign key for table `{{%team}}`
        $this->dropForeignKey(
            '{{%fk-team_member-team_id}}',
            '{{%team_member}}'
        );

        // drops index for column `team_id`
        $this->dropIndex(
            '{{%idx-team_member-team_id}}',
            '{{%team_member}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-team_member-user_id}}',
            '{{%team_member}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-team_member-user_id}}',
            '{{%team_member}}'
        );

        $this->dropTable('{{%team_member}}');
    }
}
