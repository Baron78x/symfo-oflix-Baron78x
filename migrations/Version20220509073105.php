<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220509073105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE casting_person (casting_id INT NOT NULL, person_id INT NOT NULL, INDEX IDX_DCB3973E9EB2648F (casting_id), INDEX IDX_DCB3973E217BBB47 (person_id), PRIMARY KEY(casting_id, person_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE casting_person ADD CONSTRAINT FK_DCB3973E9EB2648F FOREIGN KEY (casting_id) REFERENCES casting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE casting_person ADD CONSTRAINT FK_DCB3973E217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE casting_person');
    }
}
