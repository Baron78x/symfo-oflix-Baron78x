<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220525071038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie ADD updated_at DATETIME DEFAULT NULL, CHANGE slug slug VARCHAR(320) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D5EF26F2B36786B ON movie (title)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D5EF26F989D9B62 ON movie (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_1D5EF26F2B36786B ON movie');
        $this->addSql('DROP INDEX UNIQ_1D5EF26F989D9B62 ON movie');
        $this->addSql('ALTER TABLE movie DROP updated_at, CHANGE slug slug VARCHAR(255) DEFAULT NULL');
    }
}
