<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251105131255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiant ADD nbre_absence INT NOT NULL');
        $this->addSql('ALTER TABLE etudiant_matiere ADD absences INT NOT NULL, DROP etudiant_id, DROP matiere_id, DROP absence');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiant DROP nbre_absence');
        $this->addSql('ALTER TABLE etudiant_matiere ADD matiere_id INT NOT NULL, ADD absence INT NOT NULL, CHANGE absences etudiant_id INT NOT NULL');
    }
}
