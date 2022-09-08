<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220908085305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EAA76ED395');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE partenaire ADD email VARCHAR(180) NOT NULL, ADD roles JSON NOT NULL, DROP user_id, CHANGE description password VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_32FFA373E7927C74 ON partenaire (email)');
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EAFED90CCA');
        $this->addSql('DROP INDEX UNIQ_6F0137EAFED90CCA ON structure');
        $this->addSql('DROP INDEX UNIQ_6F0137EAA76ED395 ON structure');
        $this->addSql('ALTER TABLE structure ADD email VARCHAR(180) NOT NULL, ADD roles JSON NOT NULL, ADD password VARCHAR(255) NOT NULL, DROP permission_id, DROP user_id, DROP is_active');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6F0137EAE7927C74 ON structure (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles JSON NOT NULL, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP INDEX UNIQ_6F0137EAE7927C74 ON structure');
        $this->addSql('ALTER TABLE structure ADD permission_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, ADD is_active TINYINT(1) NOT NULL, DROP email, DROP roles, DROP password');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EAFED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6F0137EAFED90CCA ON structure (permission_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6F0137EAA76ED395 ON structure (user_id)');
        $this->addSql('DROP INDEX UNIQ_32FFA373E7927C74 ON partenaire');
        $this->addSql('ALTER TABLE partenaire ADD user_id INT NOT NULL, DROP email, DROP roles, CHANGE password description VARCHAR(255) NOT NULL');
    }
}
