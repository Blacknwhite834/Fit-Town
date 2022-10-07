<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220919093037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partenaire (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_32FFA373E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partenaire_permission (id INT AUTO_INCREMENT NOT NULL, partenaire_id INT NOT NULL, is_members_read TINYINT(1) NOT NULL, is_members_write TINYINT(1) NOT NULL, is_members_add TINYINT(1) NOT NULL, is_members_products_add TINYINT(1) NOT NULL, is_members_payment_schedules_read TINYINT(1) NOT NULL, is_members_statistiques_read TINYINT(1) NOT NULL, is_members_subscription_read TINYINT(1) NOT NULL, is_payment_schedules_read TINYINT(1) NOT NULL, is_payment_schedules_write TINYINT(1) NOT NULL, is_payment_day_read TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_280ABFB998DE13AC (partenaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure (id INT AUTO_INCREMENT NOT NULL, partenaire_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_6F0137EAE7927C74 (email), INDEX IDX_6F0137EA98DE13AC (partenaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure_permission (id INT AUTO_INCREMENT NOT NULL, structure_id INT NOT NULL, partenaire_permission_id INT DEFAULT NULL, is_members_read TINYINT(1) NOT NULL, is_members_write TINYINT(1) NOT NULL, is_members_add TINYINT(1) NOT NULL, is_members_products_add TINYINT(1) NOT NULL, is_members_payment_schedules_read TINYINT(1) NOT NULL, is_members_statistiques_read TINYINT(1) NOT NULL, is_members_subscription_read TINYINT(1) NOT NULL, is_payment_schedules_read TINYINT(1) NOT NULL, is_payment_schedules_write TINYINT(1) NOT NULL, is_payment_day_read TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_D207A6E42534008B (structure_id), UNIQUE INDEX UNIQ_D207A6E42F465BEE (partenaire_permission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partenaire_permission ADD CONSTRAINT FK_280ABFB998DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id)');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EA98DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id)');
        $this->addSql('ALTER TABLE structure_permission ADD CONSTRAINT FK_D207A6E42534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE structure_permission ADD CONSTRAINT FK_D207A6E42F465BEE FOREIGN KEY (partenaire_permission_id) REFERENCES partenaire_permission (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partenaire_permission DROP FOREIGN KEY FK_280ABFB998DE13AC');
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EA98DE13AC');
        $this->addSql('ALTER TABLE structure_permission DROP FOREIGN KEY FK_D207A6E42534008B');
        $this->addSql('ALTER TABLE structure_permission DROP FOREIGN KEY FK_D207A6E42F465BEE');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE partenaire');
        $this->addSql('DROP TABLE partenaire_permission');
        $this->addSql('DROP TABLE structure');
        $this->addSql('DROP TABLE structure_permission');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
