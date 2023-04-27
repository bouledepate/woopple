<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%result}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 * - `{{%test}}`
 */
class m230425_050356_create_result_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%result}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'reviewer_id' => $this->integer()->notNull(),
            'test_id' => $this->integer()->notNull(),
            'mark' => $this->integer(),
            'feedback' => $this->string(256),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-result-user_id}}',
            '{{%result}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-result-user_id}}',
            '{{%result}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `reviewer_id`
        $this->createIndex(
            '{{%idx-result-reviewer_id}}',
            '{{%result}}',
            'reviewer_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-result-reviewer_id}}',
            '{{%result}}',
            'reviewer_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `test_id`
        $this->createIndex(
            '{{%idx-result-test_id}}',
            '{{%result}}',
            'test_id'
        );

        // add foreign key for table `{{%test}}`
        $this->addForeignKey(
            '{{%fk-result-test_id}}',
            '{{%result}}',
            'test_id',
            '{{%test}}',
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
            '{{%fk-result-user_id}}',
            '{{%result}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-result-user_id}}',
            '{{%result}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-result-reviewer_id}}',
            '{{%result}}'
        );

        // drops index for column `reviewer_id`
        $this->dropIndex(
            '{{%idx-result-reviewer_id}}',
            '{{%result}}'
        );

        // drops foreign key for table `{{%test}}`
        $this->dropForeignKey(
            '{{%fk-result-test_id}}',
            '{{%result}}'
        );

        // drops index for column `test_id`
        $this->dropIndex(
            '{{%idx-result-test_id}}',
            '{{%result}}'
        );

        $this->dropTable('{{%result}}');
    }
}
