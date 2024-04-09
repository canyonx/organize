<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240330185510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, color VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cgu (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE faq (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feature (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, subtitle VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE friend (id INT AUTO_INCREMENT NOT NULL, member_id INT NOT NULL, friend_id INT NOT NULL, status VARCHAR(10) NOT NULL, INDEX IDX_55EEAC617597D3FE (member_id), INDEX IDX_55EEAC616A5458E8 (friend_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE homepage (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, subtitle VARCHAR(100) NOT NULL, background VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE legal (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, member_id INT NOT NULL, trip_request_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', content LONGTEXT NOT NULL, is_read TINYINT(1) NOT NULL, INDEX IDX_B6BD307F7597D3FE (member_id), INDEX IDX_B6BD307FB76C63B3 (trip_request_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, member_id INT NOT NULL, is_new_trip_request TINYINT(1) NOT NULL, is_new_message TINYINT(1) NOT NULL, is_trip_request_status_change TINYINT(1) NOT NULL, is_friend_new_trip TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_9F74B8987597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `signal` (id INT AUTO_INCREMENT NOT NULL, member_id INT NOT NULL, type VARCHAR(30) NOT NULL, number INT NOT NULL, reason VARCHAR(255) NOT NULL, message LONGTEXT DEFAULT NULL, INDEX IDX_740C95F57597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trip (id INT AUTO_INCREMENT NOT NULL, member_id INT NOT NULL, activity_id INT NOT NULL, title VARCHAR(50) NOT NULL, date_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description LONGTEXT DEFAULT NULL, lat DOUBLE PRECISION NOT NULL, lng DOUBLE PRECISION NOT NULL, is_available TINYINT(1) NOT NULL, location VARCHAR(100) DEFAULT NULL, INDEX IDX_7656F53B7597D3FE (member_id), INDEX IDX_7656F53B81C06096 (activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trip_request (id INT AUTO_INCREMENT NOT NULL, trip_id INT NOT NULL, member_id INT NOT NULL, status VARCHAR(10) NOT NULL, INDEX IDX_D7BCA32A5BC2E0E (trip_id), INDEX IDX_D7BCA327597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(50) NOT NULL, birth_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', avatar VARCHAR(255) DEFAULT NULL, about LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_conn_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_verified TINYINT(1) NOT NULL, slug VARCHAR(50) NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, lat DOUBLE PRECISION DEFAULT NULL, lng DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D64986CC499D (pseudo), UNIQUE INDEX UNIQ_8D93D649989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_activity (user_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_4CF9ED5AA76ED395 (user_id), INDEX IDX_4CF9ED5A81C06096 (activity_id), PRIMARY KEY(user_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC617597D3FE FOREIGN KEY (member_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC616A5458E8 FOREIGN KEY (friend_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F7597D3FE FOREIGN KEY (member_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB76C63B3 FOREIGN KEY (trip_request_id) REFERENCES trip_request (id)');
        $this->addSql('ALTER TABLE setting ADD CONSTRAINT FK_9F74B8987597D3FE FOREIGN KEY (member_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `signal` ADD CONSTRAINT FK_740C95F57597D3FE FOREIGN KEY (member_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B7597D3FE FOREIGN KEY (member_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE trip_request ADD CONSTRAINT FK_D7BCA32A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id)');
        $this->addSql('ALTER TABLE trip_request ADD CONSTRAINT FK_D7BCA327597D3FE FOREIGN KEY (member_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_activity ADD CONSTRAINT FK_4CF9ED5AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_activity ADD CONSTRAINT FK_4CF9ED5A81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC617597D3FE');
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC616A5458E8');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F7597D3FE');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FB76C63B3');
        $this->addSql('ALTER TABLE setting DROP FOREIGN KEY FK_9F74B8987597D3FE');
        $this->addSql('ALTER TABLE `signal` DROP FOREIGN KEY FK_740C95F57597D3FE');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53B7597D3FE');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53B81C06096');
        $this->addSql('ALTER TABLE trip_request DROP FOREIGN KEY FK_D7BCA32A5BC2E0E');
        $this->addSql('ALTER TABLE trip_request DROP FOREIGN KEY FK_D7BCA327597D3FE');
        $this->addSql('ALTER TABLE user_activity DROP FOREIGN KEY FK_4CF9ED5AA76ED395');
        $this->addSql('ALTER TABLE user_activity DROP FOREIGN KEY FK_4CF9ED5A81C06096');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE cgu');
        $this->addSql('DROP TABLE faq');
        $this->addSql('DROP TABLE feature');
        $this->addSql('DROP TABLE friend');
        $this->addSql('DROP TABLE homepage');
        $this->addSql('DROP TABLE legal');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE `signal`');
        $this->addSql('DROP TABLE trip');
        $this->addSql('DROP TABLE trip_request');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_activity');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
