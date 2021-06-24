<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201014075118 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chat_message_messages (id CHAR(36) NOT NULL COMMENT \'(DC2Type:chat_message_id)\', user_id INT DEFAULT NULL, chat_room_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_27480E4AA76ED395 (user_id), INDEX IDX_27480E4A1819BCFA (chat_room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_news_news (id CHAR(36) NOT NULL COMMENT \'(DC2Type:chat_news_id)\', user_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, archive VARCHAR(255) NOT NULL COMMENT \'(DC2Type:chat_news_archive)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_EEE37DA0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_room_rooms (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_rooms_users (room_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_E89A2BC454177093 (room_id), INDEX IDX_E89A2BC4A76ED395 (user_id), PRIMARY KEY(room_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_user_users (id INT NOT NULL, user_name VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat_message_messages ADD CONSTRAINT FK_27480E4AA76ED395 FOREIGN KEY (user_id) REFERENCES chat_user_users (id)');
        $this->addSql('ALTER TABLE chat_message_messages ADD CONSTRAINT FK_27480E4A1819BCFA FOREIGN KEY (chat_room_id) REFERENCES chat_room_rooms (id)');
        $this->addSql('ALTER TABLE chat_news_news ADD CONSTRAINT FK_EEE37DA0A76ED395 FOREIGN KEY (user_id) REFERENCES chat_user_users (id)');
        $this->addSql('ALTER TABLE chat_rooms_users ADD CONSTRAINT FK_E89A2BC454177093 FOREIGN KEY (room_id) REFERENCES chat_room_rooms (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_rooms_users ADD CONSTRAINT FK_E89A2BC4A76ED395 FOREIGN KEY (user_id) REFERENCES chat_user_users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat_message_messages DROP FOREIGN KEY FK_27480E4A1819BCFA');
        $this->addSql('ALTER TABLE chat_rooms_users DROP FOREIGN KEY FK_E89A2BC454177093');
        $this->addSql('ALTER TABLE chat_message_messages DROP FOREIGN KEY FK_27480E4AA76ED395');
        $this->addSql('ALTER TABLE chat_news_news DROP FOREIGN KEY FK_EEE37DA0A76ED395');
        $this->addSql('ALTER TABLE chat_rooms_users DROP FOREIGN KEY FK_E89A2BC4A76ED395');
        $this->addSql('DROP TABLE chat_message_messages');
        $this->addSql('DROP TABLE chat_news_news');
        $this->addSql('DROP TABLE chat_room_rooms');
        $this->addSql('DROP TABLE chat_rooms_users');
        $this->addSql('DROP TABLE chat_user_users');
    }
}
