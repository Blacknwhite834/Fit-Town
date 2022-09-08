<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220908105445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partenaire ADD partenaire_permission_id INT NOT NULL');
        $this->addSql('ALTER TABLE partenaire ADD CONSTRAINT FK_32FFA3732F465BEE FOREIGN KEY (partenaire_permission_id) REFERENCES partenaire_permission (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_32FFA3732F465BEE ON partenaire (partenaire_permission_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partenaire DROP FOREIGN KEY FK_32FFA3732F465BEE');
        $this->addSql('DROP INDEX UNIQ_32FFA3732F465BEE ON partenaire');
        $this->addSql('ALTER TABLE partenaire DROP partenaire_permission_id');
    }
}
