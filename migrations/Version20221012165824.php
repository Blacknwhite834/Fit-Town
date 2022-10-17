<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221012165824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partenaire DROP partenaire_permission_id');
        $this->addSql('ALTER TABLE partenaire_permission DROP FOREIGN KEY FK_280ABFB998DE13AC');
        $this->addSql('DROP INDEX UNIQ_280ABFB998DE13AC ON partenaire_permission');
        $this->addSql('ALTER TABLE partenaire_permission DROP partenaire_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partenaire_permission ADD partenaire_id INT NOT NULL');
        $this->addSql('ALTER TABLE partenaire_permission ADD CONSTRAINT FK_280ABFB998DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_280ABFB998DE13AC ON partenaire_permission (partenaire_id)');
        $this->addSql('ALTER TABLE partenaire ADD partenaire_permission_id INT NOT NULL');
    }
}
