<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220908102401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE partenaire_permission (id INT AUTO_INCREMENT NOT NULL, is_members_read TINYINT(1) NOT NULL, is_members_write TINYINT(1) NOT NULL, is_members_add TINYINT(1) NOT NULL, is_members_products_add TINYINT(1) NOT NULL, is_members_payment_schedules_read TINYINT(1) NOT NULL, is_members_statistiques_read TINYINT(1) NOT NULL, is_members_subscription_read TINYINT(1) NOT NULL, is_payment_schedules_read TINYINT(1) NOT NULL, is_payment_schedules_write TINYINT(1) NOT NULL, is_payment_day_read TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE partenaire_permission');
    }
}
