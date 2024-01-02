<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240102113043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_activity (user_id INT NOT NULL, activity_id INT NOT NULL, PRIMARY KEY(user_id, activity_id))');
        $this->addSql('CREATE INDEX IDX_4CF9ED5AA76ED395 ON user_activity (user_id)');
        $this->addSql('CREATE INDEX IDX_4CF9ED5A81C06096 ON user_activity (activity_id)');
        $this->addSql('ALTER TABLE user_activity ADD CONSTRAINT FK_4CF9ED5AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_activity ADD CONSTRAINT FK_4CF9ED5A81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE activity_user DROP CONSTRAINT fk_8e570ddb81c06096');
        $this->addSql('ALTER TABLE activity_user DROP CONSTRAINT fk_8e570ddba76ed395');
        $this->addSql('DROP TABLE activity_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE activity_user (activity_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(activity_id, user_id))');
        $this->addSql('CREATE INDEX idx_8e570ddba76ed395 ON activity_user (user_id)');
        $this->addSql('CREATE INDEX idx_8e570ddb81c06096 ON activity_user (activity_id)');
        $this->addSql('ALTER TABLE activity_user ADD CONSTRAINT fk_8e570ddb81c06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE activity_user ADD CONSTRAINT fk_8e570ddba76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_activity DROP CONSTRAINT FK_4CF9ED5AA76ED395');
        $this->addSql('ALTER TABLE user_activity DROP CONSTRAINT FK_4CF9ED5A81C06096');
        $this->addSql('DROP TABLE user_activity');
    }
}
