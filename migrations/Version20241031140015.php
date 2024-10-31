<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241031140015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE era CHANGE last_reminder_sent last_reminder_sent DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE era_entry CHANGE work_mode work_mode VARCHAR(255) DEFAULT NULL, CHANGE team team VARCHAR(255) DEFAULT NULL, CHANGE general_agreement general_agreement LONGTEXT DEFAULT NULL, CHANGE last_reminder_sent last_reminder_sent DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE last_confirmed_at last_confirmed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE era_entry CHANGE work_mode work_mode VARCHAR(255) NOT NULL, CHANGE team team VARCHAR(255) NOT NULL, CHANGE general_agreement general_agreement LONGTEXT NOT NULL, CHANGE last_reminder_sent last_reminder_sent DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE last_confirmed_at last_confirmed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE era CHANGE last_reminder_sent last_reminder_sent DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
