<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251105132507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiant_matiere ADD etudiant_id INT NOT NULL, ADD matiere_id INT NOT NULL');
        $this->addSql('ALTER TABLE etudiant_matiere ADD CONSTRAINT FK_44BB19CEDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE etudiant_matiere ADD CONSTRAINT FK_44BB19CEF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('CREATE INDEX IDX_44BB19CEDDEAB1A3 ON etudiant_matiere (etudiant_id)');
        $this->addSql('CREATE INDEX IDX_44BB19CEF46CD258 ON etudiant_matiere (matiere_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiant_matiere DROP FOREIGN KEY FK_44BB19CEDDEAB1A3');
        $this->addSql('ALTER TABLE etudiant_matiere DROP FOREIGN KEY FK_44BB19CEF46CD258');
        $this->addSql('DROP INDEX IDX_44BB19CEDDEAB1A3 ON etudiant_matiere');
        $this->addSql('DROP INDEX IDX_44BB19CEF46CD258 ON etudiant_matiere');
        $this->addSql('ALTER TABLE etudiant_matiere DROP etudiant_id, DROP matiere_id');
    }
}
