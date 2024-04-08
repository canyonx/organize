<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240408111026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE signalment DROP FOREIGN KEY FK_BBBF6CA2A76ED395');
        $this->addSql('DROP INDEX IDX_BBBF6CA2A76ED395 ON signalment');
        $this->addSql('ALTER TABLE signalment CHANGE user_id member_id INT NOT NULL');
        $this->addSql('ALTER TABLE signalment ADD CONSTRAINT FK_BBBF6CA27597D3FE FOREIGN KEY (member_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_BBBF6CA27597D3FE ON signalment (member_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE signalment DROP FOREIGN KEY FK_BBBF6CA27597D3FE');
        $this->addSql('DROP INDEX IDX_BBBF6CA27597D3FE ON signalment');
        $this->addSql('ALTER TABLE signalment CHANGE member_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE signalment ADD CONSTRAINT FK_BBBF6CA2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_BBBF6CA2A76ED395 ON signalment (user_id)');
    }
}
