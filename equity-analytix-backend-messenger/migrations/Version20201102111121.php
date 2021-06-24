<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201102111121 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chat_viewed_news (id INT AUTO_INCREMENT NOT NULL, news_unit_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:chat_news_id)\', user_id INT DEFAULT NULL, INDEX IDX_13CE9D44AF596CEB (news_unit_id), INDEX IDX_13CE9D44A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat_viewed_news ADD CONSTRAINT FK_13CE9D44AF596CEB FOREIGN KEY (news_unit_id) REFERENCES chat_news_news (id)');
        $this->addSql('ALTER TABLE chat_viewed_news ADD CONSTRAINT FK_13CE9D44A76ED395 FOREIGN KEY (user_id) REFERENCES chat_user_users (id)');
        $this->addSql('ALTER TABLE chat_news_news CHANGE status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chat_setting_settings CHANGE name name VARCHAR(255) NOT NULL, CHANGE signification signification VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chat_user_users CHANGE role role VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE chat_viewed_news');
        $this->addSql('ALTER TABLE chat_news_news CHANGE status status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE chat_setting_settings CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE signification signification VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE chat_user_users CHANGE role role VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
