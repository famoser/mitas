<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241031134845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE era ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE era_entry ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE last_change_at last_confirmed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE era_entry ADD last_change_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP last_confirmed_at, DROP created_at, DROP last_changed_at');
        $this->addSql('ALTER TABLE era DROP created_at, DROP last_changed_at');
        $this->addSql('ALTER TABLE user DROP created_at, DROP last_changed_at');
    }
}
