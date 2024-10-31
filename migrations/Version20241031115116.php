<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241031115116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE era (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, last_reminder_sent DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', deadline_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE era_entry (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', era_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', full_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, work_mode VARCHAR(255) NOT NULL, team VARCHAR(255) NOT NULL, general_agreement LONGTEXT NOT NULL, last_reminder_sent DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_change_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2392D1B6707300A1 (era_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE era_entry ADD CONSTRAINT FK_2392D1B6707300A1 FOREIGN KEY (era_id) REFERENCES era (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE era_entry DROP FOREIGN KEY FK_2392D1B6707300A1');
        $this->addSql('DROP TABLE era');
        $this->addSql('DROP TABLE era_entry');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
