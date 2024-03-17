<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240317095629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE signal ADD member_id INT NOT NULL');
        $this->addSql('ALTER TABLE signal ADD CONSTRAINT FK_740C95F57597D3FE FOREIGN KEY (member_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_740C95F57597D3FE ON signal (member_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE signal DROP CONSTRAINT FK_740C95F57597D3FE');
        $this->addSql('DROP INDEX IDX_740C95F57597D3FE');
        $this->addSql('ALTER TABLE signal DROP member_id');
    }
}
