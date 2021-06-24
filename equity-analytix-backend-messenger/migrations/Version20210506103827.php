<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210506103827 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chat_viewed_messages (id INT AUTO_INCREMENT NOT NULL, message_unit_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:chat_message_id)\', user_id INT DEFAULT NULL, INDEX IDX_1BA4379968354A01 (message_unit_id), INDEX IDX_1BA43799A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat_viewed_messages ADD CONSTRAINT FK_1BA4379968354A01 FOREIGN KEY (message_unit_id) REFERENCES chat_message_messages (id)');
        $this->addSql('ALTER TABLE chat_viewed_messages ADD CONSTRAINT FK_1BA43799A76ED395 FOREIGN KEY (user_id) REFERENCES chat_user_users (id)');
        $this->addSql('ALTER TABLE chat_news_news CHANGE status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chat_setting_settings CHANGE name name VARCHAR(255) NOT NULL, CHANGE signification signification VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chat_user_users CHANGE role role VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE chat_viewed_messages');
        $this->addSql('ALTER TABLE chat_news_news CHANGE status status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE chat_setting_settings CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE signification signification VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE chat_user_users CHANGE role role VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
