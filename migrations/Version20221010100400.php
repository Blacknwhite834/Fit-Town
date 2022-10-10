<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221010100400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partenaire DROP partenaire_permission_id');
        $this->addSql('ALTER TABLE structure_permission DROP INDEX UNIQ_D207A6E42F465BEE, ADD INDEX IDX_D207A6E42F465BEE (partenaire_permission_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE structure_permission DROP INDEX IDX_D207A6E42F465BEE, ADD UNIQUE INDEX UNIQ_D207A6E42F465BEE (partenaire_permission_id)');
        $this->addSql('ALTER TABLE partenaire ADD partenaire_permission_id INT NOT NULL');
    }
}
