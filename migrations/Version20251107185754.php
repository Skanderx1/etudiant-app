<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251107185754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matiere_etudiant DROP FOREIGN KEY FK_C516BA5BDDEAB1A3');
        $this->addSql('ALTER TABLE matiere_etudiant DROP FOREIGN KEY FK_C516BA5BF46CD258');
        $this->addSql('DROP TABLE matiere_etudiant');
        $this->addSql('ALTER TABLE absence DROP FOREIGN KEY FK_765AE0C9DDEAB1A3');
        $this->addSql('ALTER TABLE absence DROP FOREIGN KEY FK_765AE0C9F46CD258');
        $this->addSql('ALTER TABLE absence CHANGE nbre_abscences nbre_absences INT NOT NULL');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C9DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C9F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etudiant_matiere ADD etudiant_id INT NOT NULL, ADD matiere_id INT NOT NULL, CHANGE absences absences INT DEFAULT NULL');
        $this->addSql('ALTER TABLE etudiant_matiere ADD CONSTRAINT FK_44BB19CEDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etudiant_matiere ADD CONSTRAINT FK_44BB19CEF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_44BB19CEDDEAB1A3 ON etudiant_matiere (etudiant_id)');
        $this->addSql('CREATE INDEX IDX_44BB19CEF46CD258 ON etudiant_matiere (matiere_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matiere_etudiant (matiere_id INT NOT NULL, etudiant_id INT NOT NULL, INDEX IDX_C516BA5BF46CD258 (matiere_id), INDEX IDX_C516BA5BDDEAB1A3 (etudiant_id), PRIMARY KEY(matiere_id, etudiant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE matiere_etudiant ADD CONSTRAINT FK_C516BA5BDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matiere_etudiant ADD CONSTRAINT FK_C516BA5BF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE absence DROP FOREIGN KEY FK_765AE0C9DDEAB1A3');
        $this->addSql('ALTER TABLE absence DROP FOREIGN KEY FK_765AE0C9F46CD258');
        $this->addSql('ALTER TABLE absence CHANGE nbre_absences nbre_abscences INT NOT NULL');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C9DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C9F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE etudiant_matiere DROP FOREIGN KEY FK_44BB19CEDDEAB1A3');
        $this->addSql('ALTER TABLE etudiant_matiere DROP FOREIGN KEY FK_44BB19CEF46CD258');
        $this->addSql('DROP INDEX IDX_44BB19CEDDEAB1A3 ON etudiant_matiere');
        $this->addSql('DROP INDEX IDX_44BB19CEF46CD258 ON etudiant_matiere');
        $this->addSql('ALTER TABLE etudiant_matiere DROP etudiant_id, DROP matiere_id, CHANGE absences absences INT NOT NULL');
    }
}
