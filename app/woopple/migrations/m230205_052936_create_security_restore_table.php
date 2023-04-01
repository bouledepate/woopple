<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%security_restore}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m230205_052936_create_security_restore_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%security_restore}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'moderated_by' => $this->integer()->notNull(),
            'reason' => $this->string(),
            'status' => $this->integer()->notNull()->defaultValue(1),
            'request_date' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-security_restore-user_id}}',
            '{{%security_restore}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-security_restore-user_id}}',
            '{{%security_restore}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `moderated_by`
        $this->createIndex(
            '{{%idx-security_restore-moderated_by}}',
            '{{%security_restore}}',
            'moderated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-security_restore-moderated_by}}',
            '{{%security_restore}}',
            'moderated_by',
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
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-security_restore-user_id}}',
            '{{%security_restore}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-security_restore-user_id}}',
            '{{%security_restore}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-security_restore-moderated_by}}',
            '{{%security_restore}}'
        );

        // drops index for column `moderated_by`
        $this->dropIndex(
            '{{%idx-security_restore-moderated_by}}',
            '{{%security_restore}}'
        );

        $this->dropTable('{{%security_restore}}');
    }
}
