<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240330152727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE activity_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cgu_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE faq_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE feature_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE friend_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE homepage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE legal_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE setting_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE signal_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE trip_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE trip_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE activity (id INT NOT NULL, name VARCHAR(50) NOT NULL, color VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE cgu (id INT NOT NULL, number INT NOT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE faq (id INT NOT NULL, number INT NOT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE feature (id INT NOT NULL, title VARCHAR(100) NOT NULL, subtitle VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE friend (id INT NOT NULL, member_id INT NOT NULL, friend_id INT NOT NULL, status VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_55EEAC617597D3FE ON friend (member_id)');
        $this->addSql('CREATE INDEX IDX_55EEAC616A5458E8 ON friend (friend_id)');
        $this->addSql('CREATE TABLE homepage (id INT NOT NULL, title VARCHAR(100) NOT NULL, subtitle VARCHAR(100) NOT NULL, background VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE legal (id INT NOT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE message (id INT NOT NULL, member_id INT NOT NULL, trip_request_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, content TEXT NOT NULL, is_read BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6BD307F7597D3FE ON message (member_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FB76C63B3 ON message (trip_request_id)');
        $this->addSql('COMMENT ON COLUMN message.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE setting (id INT NOT NULL, member_id INT NOT NULL, is_new_trip_request BOOLEAN NOT NULL, is_new_message BOOLEAN NOT NULL, is_trip_request_status_change BOOLEAN NOT NULL, is_friend_new_trip BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9F74B8987597D3FE ON setting (member_id)');
        $this->addSql('CREATE TABLE signal (id INT NOT NULL, member_id INT NOT NULL, type VARCHAR(30) NOT NULL, number INT NOT NULL, reason VARCHAR(255) NOT NULL, message TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_740C95F57597D3FE ON signal (member_id)');
        $this->addSql('CREATE TABLE trip (id INT NOT NULL, member_id INT NOT NULL, activity_id INT NOT NULL, title VARCHAR(50) NOT NULL, date_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, description TEXT DEFAULT NULL, lat DOUBLE PRECISION NOT NULL, lng DOUBLE PRECISION NOT NULL, is_available BOOLEAN NOT NULL, location VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7656F53B7597D3FE ON trip (member_id)');
        $this->addSql('CREATE INDEX IDX_7656F53B81C06096 ON trip (activity_id)');
        $this->addSql('COMMENT ON COLUMN trip.date_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE trip_request (id INT NOT NULL, trip_id INT NOT NULL, member_id INT NOT NULL, status VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D7BCA32A5BC2E0E ON trip_request (trip_id)');
        $this->addSql('CREATE INDEX IDX_D7BCA327597D3FE ON trip_request (member_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(50) NOT NULL, birth_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, about TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_conn_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_verified BOOLEAN NOT NULL, slug VARCHAR(50) NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, lat DOUBLE PRECISION DEFAULT NULL, lng DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64986CC499D ON "user" (pseudo)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649989D9B62 ON "user" (slug)');
        $this->addSql('COMMENT ON COLUMN "user".birth_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".last_conn_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_activity (user_id INT NOT NULL, activity_id INT NOT NULL, PRIMARY KEY(user_id, activity_id))');
        $this->addSql('CREATE INDEX IDX_4CF9ED5AA76ED395 ON user_activity (user_id)');
        $this->addSql('CREATE INDEX IDX_4CF9ED5A81C06096 ON user_activity (activity_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC617597D3FE FOREIGN KEY (member_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC616A5458E8 FOREIGN KEY (friend_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F7597D3FE FOREIGN KEY (member_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB76C63B3 FOREIGN KEY (trip_request_id) REFERENCES trip_request (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE setting ADD CONSTRAINT FK_9F74B8987597D3FE FOREIGN KEY (member_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE signal ADD CONSTRAINT FK_740C95F57597D3FE FOREIGN KEY (member_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B7597D3FE FOREIGN KEY (member_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trip_request ADD CONSTRAINT FK_D7BCA32A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trip_request ADD CONSTRAINT FK_D7BCA327597D3FE FOREIGN KEY (member_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_activity ADD CONSTRAINT FK_4CF9ED5AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_activity ADD CONSTRAINT FK_4CF9ED5A81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE activity_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cgu_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE faq_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE feature_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE friend_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE homepage_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE legal_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE setting_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE signal_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE trip_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE trip_request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE friend DROP CONSTRAINT FK_55EEAC617597D3FE');
        $this->addSql('ALTER TABLE friend DROP CONSTRAINT FK_55EEAC616A5458E8');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F7597D3FE');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307FB76C63B3');
        $this->addSql('ALTER TABLE setting DROP CONSTRAINT FK_9F74B8987597D3FE');
        $this->addSql('ALTER TABLE signal DROP CONSTRAINT FK_740C95F57597D3FE');
        $this->addSql('ALTER TABLE trip DROP CONSTRAINT FK_7656F53B7597D3FE');
        $this->addSql('ALTER TABLE trip DROP CONSTRAINT FK_7656F53B81C06096');
        $this->addSql('ALTER TABLE trip_request DROP CONSTRAINT FK_D7BCA32A5BC2E0E');
        $this->addSql('ALTER TABLE trip_request DROP CONSTRAINT FK_D7BCA327597D3FE');
        $this->addSql('ALTER TABLE user_activity DROP CONSTRAINT FK_4CF9ED5AA76ED395');
        $this->addSql('ALTER TABLE user_activity DROP CONSTRAINT FK_4CF9ED5A81C06096');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE cgu');
        $this->addSql('DROP TABLE faq');
        $this->addSql('DROP TABLE feature');
        $this->addSql('DROP TABLE friend');
        $this->addSql('DROP TABLE homepage');
        $this->addSql('DROP TABLE legal');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE signal');
        $this->addSql('DROP TABLE trip');
        $this->addSql('DROP TABLE trip_request');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_activity');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
