<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210429041244 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file_files (id CHAR(36) NOT NULL COMMENT \'(DC2Type:file_files_id)\', date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', info_path VARCHAR(255) NOT NULL, info_name VARCHAR(255) NOT NULL, info_size INT NOT NULL, entity_type VARCHAR(255) DEFAULT NULL, entity_id VARCHAR(36) DEFAULT NULL, INDEX IDX_ACB6F06EAA9E377A (date), INDEX IDX_ACB6F06EC412EE0281257D5D (entity_type, entity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat_news_news CHANGE status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chat_setting_settings CHANGE name name VARCHAR(255) NOT NULL, CHANGE signification signification VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chat_user_users CHANGE role role VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE file_files');
        $this->addSql('ALTER TABLE chat_news_news CHANGE status status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE chat_setting_settings CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE signification signification VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE chat_user_users CHANGE role role VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
