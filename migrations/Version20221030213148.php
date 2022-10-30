<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221030213148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE doctrine_migrations_versions');
        $this->addSql('ALTER TABLE `admin` CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE roles roles JSON NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE is_active is_active TINYINT(1) NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_880E0D76E7927C74 ON `admin` (email)');
        $this->addSql('ALTER TABLE partenaire CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE roles roles JSON NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE is_active is_active TINYINT(1) NOT NULL, CHANGE is_password_change is_password_change TINYINT(1) NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_32FFA373E7927C74 ON partenaire (email)');
        $this->addSql('ALTER TABLE partenaire_permission CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE is_members_read is_members_read TINYINT(1) NOT NULL, CHANGE is_members_write is_members_write TINYINT(1) NOT NULL, CHANGE is_members_add is_members_add TINYINT(1) NOT NULL, CHANGE is_members_products_add is_members_products_add TINYINT(1) NOT NULL, CHANGE is_members_payment_schedules_read is_members_payment_schedules_read TINYINT(1) NOT NULL, CHANGE is_members_statistiques_read is_members_statistiques_read TINYINT(1) NOT NULL, CHANGE is_members_subscription_read is_members_subscription_read TINYINT(1) NOT NULL, CHANGE is_payment_schedules_read is_payment_schedules_read TINYINT(1) NOT NULL, CHANGE is_payment_schedules_write is_payment_schedules_write TINYINT(1) NOT NULL, CHANGE is_payment_day_read is_payment_day_read TINYINT(1) NOT NULL, CHANGE partenaire_id partenaire_id INT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE partenaire_permission ADD CONSTRAINT FK_280ABFB998DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_280ABFB998DE13AC ON partenaire_permission (partenaire_id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES partenaire (id)');
        $this->addSql('ALTER TABLE structure CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE roles roles JSON NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE is_active is_active TINYINT(1) NOT NULL, CHANGE is_password_change is_password_change TINYINT(1) NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EA98DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6F0137EAE7927C74 ON structure (email)');
        $this->addSql('CREATE INDEX IDX_6F0137EA98DE13AC ON structure (partenaire_id)');
        $this->addSql('ALTER TABLE structure_permission CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE is_members_read is_members_read TINYINT(1) NOT NULL, CHANGE is_members_write is_members_write TINYINT(1) NOT NULL, CHANGE is_members_add is_members_add TINYINT(1) NOT NULL, CHANGE is_members_products_add is_members_products_add TINYINT(1) NOT NULL, CHANGE is_members_payment_schedules_read is_members_payment_schedules_read TINYINT(1) NOT NULL, CHANGE is_members_statistiques_read is_members_statistiques_read TINYINT(1) NOT NULL, CHANGE is_members_subscription_read is_members_subscription_read TINYINT(1) NOT NULL, CHANGE is_payment_schedules_read is_payment_schedules_read TINYINT(1) NOT NULL, CHANGE is_payment_schedules_write is_payment_schedules_write TINYINT(1) NOT NULL, CHANGE is_payment_day_read is_payment_day_read TINYINT(1) NOT NULL, CHANGE structure_id structure_id INT NOT NULL, CHANGE permission_partenaire_id permission_partenaire_id INT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE structure_permission ADD CONSTRAINT FK_D207A6E42534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE structure_permission ADD CONSTRAINT FK_D207A6E455600663 FOREIGN KEY (permission_partenaire_id) REFERENCES partenaire_permission (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D207A6E42534008B ON structure_permission (structure_id)');
        $this->addSql('CREATE INDEX IDX_D207A6E455600663 ON structure_permission (permission_partenaire_id)');
        $this->addSql('ALTER TABLE messenger_messages CHANGE id id BIGINT AUTO_INCREMENT NOT NULL, CHANGE body body LONGTEXT NOT NULL, CHANGE headers headers LONGTEXT NOT NULL, CHANGE queue_name queue_name VARCHAR(190) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE available_at available_at DATETIME NOT NULL, CHANGE delivered_at delivered_at DATETIME DEFAULT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE doctrine_migrations_versions (version TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, executed_at TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, execution_time INT DEFAULT NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `admin` MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_880E0D76E7927C74 ON `admin`');
        $this->addSql('DROP INDEX `primary` ON `admin`');
        $this->addSql('ALTER TABLE `admin` CHANGE id id INT DEFAULT NULL, CHANGE email email TEXT DEFAULT NULL, CHANGE roles roles JSON DEFAULT NULL, CHANGE password password TEXT DEFAULT NULL, CHANGE is_active is_active INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partenaire_permission MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE partenaire_permission DROP FOREIGN KEY FK_280ABFB998DE13AC');
        $this->addSql('DROP INDEX UNIQ_280ABFB998DE13AC ON partenaire_permission');
        $this->addSql('DROP INDEX `primary` ON partenaire_permission');
        $this->addSql('ALTER TABLE partenaire_permission CHANGE id id INT DEFAULT NULL, CHANGE partenaire_id partenaire_id INT DEFAULT NULL, CHANGE is_members_read is_members_read INT DEFAULT NULL, CHANGE is_members_write is_members_write INT DEFAULT NULL, CHANGE is_members_add is_members_add INT DEFAULT NULL, CHANGE is_members_products_add is_members_products_add INT DEFAULT NULL, CHANGE is_members_payment_schedules_read is_members_payment_schedules_read INT DEFAULT NULL, CHANGE is_members_statistiques_read is_members_statistiques_read INT DEFAULT NULL, CHANGE is_members_subscription_read is_members_subscription_read INT DEFAULT NULL, CHANGE is_payment_schedules_read is_payment_schedules_read INT DEFAULT NULL, CHANGE is_payment_schedules_write is_payment_schedules_write INT DEFAULT NULL, CHANGE is_payment_day_read is_payment_day_read INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partenaire MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_32FFA373E7927C74 ON partenaire');
        $this->addSql('DROP INDEX `primary` ON partenaire');
        $this->addSql('ALTER TABLE partenaire CHANGE id id INT DEFAULT NULL, CHANGE email email TEXT DEFAULT NULL, CHANGE roles roles JSON DEFAULT NULL, CHANGE password password TEXT DEFAULT NULL, CHANGE name name TEXT DEFAULT NULL, CHANGE description description TEXT DEFAULT NULL, CHANGE is_active is_active INT DEFAULT NULL, CHANGE is_password_change is_password_change INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE messenger_messages MODIFY id BIGINT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON messenger_messages');
        $this->addSql('DROP INDEX IDX_75EA56E0FB7336F0 ON messenger_messages');
        $this->addSql('DROP INDEX IDX_75EA56E0E3BD61CE ON messenger_messages');
        $this->addSql('DROP INDEX IDX_75EA56E016BA31DB ON messenger_messages');
        $this->addSql('ALTER TABLE messenger_messages CHANGE id id TEXT DEFAULT NULL, CHANGE body body TEXT DEFAULT NULL, CHANGE headers headers TEXT DEFAULT NULL, CHANGE queue_name queue_name TEXT DEFAULT NULL, CHANGE created_at created_at TEXT DEFAULT NULL, CHANGE available_at available_at TEXT DEFAULT NULL, CHANGE delivered_at delivered_at TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE structure MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EA98DE13AC');
        $this->addSql('DROP INDEX UNIQ_6F0137EAE7927C74 ON structure');
        $this->addSql('DROP INDEX IDX_6F0137EA98DE13AC ON structure');
        $this->addSql('DROP INDEX `primary` ON structure');
        $this->addSql('ALTER TABLE structure CHANGE id id INT DEFAULT NULL, CHANGE email email TEXT DEFAULT NULL, CHANGE roles roles JSON DEFAULT NULL, CHANGE password password TEXT DEFAULT NULL, CHANGE adresse adresse TEXT DEFAULT NULL, CHANGE is_active is_active INT DEFAULT NULL, CHANGE is_password_change is_password_change INT DEFAULT NULL');
        $this->addSql('ALTER TABLE structure_permission MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE structure_permission DROP FOREIGN KEY FK_D207A6E42534008B');
        $this->addSql('ALTER TABLE structure_permission DROP FOREIGN KEY FK_D207A6E455600663');
        $this->addSql('DROP INDEX UNIQ_D207A6E42534008B ON structure_permission');
        $this->addSql('DROP INDEX IDX_D207A6E455600663 ON structure_permission');
        $this->addSql('DROP INDEX `primary` ON structure_permission');
        $this->addSql('ALTER TABLE structure_permission CHANGE id id INT DEFAULT NULL, CHANGE structure_id structure_id INT DEFAULT NULL, CHANGE permission_partenaire_id permission_partenaire_id INT DEFAULT NULL, CHANGE is_members_read is_members_read INT DEFAULT NULL, CHANGE is_members_write is_members_write INT DEFAULT NULL, CHANGE is_members_add is_members_add INT DEFAULT NULL, CHANGE is_members_products_add is_members_products_add INT DEFAULT NULL, CHANGE is_members_payment_schedules_read is_members_payment_schedules_read INT DEFAULT NULL, CHANGE is_members_statistiques_read is_members_statistiques_read INT DEFAULT NULL, CHANGE is_members_subscription_read is_members_subscription_read INT DEFAULT NULL, CHANGE is_payment_schedules_read is_payment_schedules_read INT DEFAULT NULL, CHANGE is_payment_schedules_write is_payment_schedules_write INT DEFAULT NULL, CHANGE is_payment_day_read is_payment_day_read INT DEFAULT NULL');
    }
}
