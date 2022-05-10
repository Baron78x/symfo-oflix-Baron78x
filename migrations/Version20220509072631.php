<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220509072631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE casting (id INT AUTO_INCREMENT NOT NULL, role LONGTEXT NOT NULL, credit_order INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE casting_movie (casting_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_CACC89289EB2648F (casting_id), INDEX IDX_CACC89288F93B6FC (movie_id), PRIMARY KEY(casting_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poerson (id INT AUTO_INCREMENT NOT NULL, firstname LONGTEXT NOT NULL, lastname LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE casting_movie ADD CONSTRAINT FK_CACC89289EB2648F FOREIGN KEY (casting_id) REFERENCES casting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE casting_movie ADD CONSTRAINT FK_CACC89288F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre ADD relation_id INT NOT NULL');
        $this->addSql('ALTER TABLE genre ADD CONSTRAINT FK_835033F83256915B FOREIGN KEY (relation_id) REFERENCES movie (id)');
        $this->addSql('CREATE INDEX IDX_835033F83256915B ON genre (relation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE casting_movie DROP FOREIGN KEY FK_CACC89289EB2648F');
        $this->addSql('DROP TABLE casting');
        $this->addSql('DROP TABLE casting_movie');
        $this->addSql('DROP TABLE poerson');
        $this->addSql('ALTER TABLE genre DROP FOREIGN KEY FK_835033F83256915B');
        $this->addSql('DROP INDEX IDX_835033F83256915B ON genre');
        $this->addSql('ALTER TABLE genre DROP relation_id');
    }
}
