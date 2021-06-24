<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201019035108 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chat_archived_news (id INT AUTO_INCREMENT NOT NULL, news_unit_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:chat_news_id)\', user_id INT DEFAULT NULL, INDEX IDX_D3E729E0AF596CEB (news_unit_id), INDEX IDX_D3E729E0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat_archived_news ADD CONSTRAINT FK_D3E729E0AF596CEB FOREIGN KEY (news_unit_id) REFERENCES chat_news_news (id)');
        $this->addSql('ALTER TABLE chat_archived_news ADD CONSTRAINT FK_D3E729E0A76ED395 FOREIGN KEY (user_id) REFERENCES chat_user_users (id)');
        $this->addSql('ALTER TABLE chat_news_news DROP archive, CHANGE text text VARCHAR(512) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chat_user_users CHANGE role role VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE chat_archived_news');
        $this->addSql('ALTER TABLE chat_news_news ADD archive VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:chat_news_archive)\', CHANGE text text VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE status status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE chat_user_users CHANGE role role VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
