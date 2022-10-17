<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221017135207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE structure_permission DROP FOREIGN KEY FK_D207A6E42F465BEE');
        $this->addSql('DROP INDEX UNIQ_D207A6E42F465BEE ON structure_permission');
        $this->addSql('ALTER TABLE structure_permission ADD permission_partenaire_id INT NOT NULL, DROP partenaire_permission_id');
        $this->addSql('ALTER TABLE structure_permission ADD CONSTRAINT FK_D207A6E455600663 FOREIGN KEY (permission_partenaire_id) REFERENCES partenaire_permission (id)');
        $this->addSql('CREATE INDEX IDX_D207A6E455600663 ON structure_permission (permission_partenaire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE structure_permission DROP FOREIGN KEY FK_D207A6E455600663');
        $this->addSql('DROP INDEX IDX_D207A6E455600663 ON structure_permission');
        $this->addSql('ALTER TABLE structure_permission ADD partenaire_permission_id INT DEFAULT NULL, DROP permission_partenaire_id');
        $this->addSql('ALTER TABLE structure_permission ADD CONSTRAINT FK_D207A6E42F465BEE FOREIGN KEY (partenaire_permission_id) REFERENCES partenaire_permission (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D207A6E42F465BEE ON structure_permission (partenaire_permission_id)');
    }
}
