<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231104182521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDEFE52BF81');
        $this->addSql('DROP INDEX UNIQ_8BF21CDEFE52BF81 ON property');
        $this->addSql('ALTER TABLE property DROP is_property_ad_created, CHANGE ads_id ad_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE4F34D596 FOREIGN KEY (ad_id) REFERENCES propertyad (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8BF21CDE4F34D596 ON property (ad_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE4F34D596');
        $this->addSql('DROP INDEX UNIQ_8BF21CDE4F34D596 ON property');
        $this->addSql('ALTER TABLE property ADD is_property_ad_created TINYINT(1) DEFAULT NULL, CHANGE ad_id ads_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDEFE52BF81 FOREIGN KEY (ads_id) REFERENCES propertyad (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8BF21CDEFE52BF81 ON property (ads_id)');
    }
}
