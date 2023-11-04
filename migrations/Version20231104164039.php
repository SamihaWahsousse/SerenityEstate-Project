<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231104164039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property ADD ads_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDEFE52BF81 FOREIGN KEY (ads_id) REFERENCES propertyad (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8BF21CDEFE52BF81 ON property (ads_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDEFE52BF81');
        $this->addSql('DROP INDEX UNIQ_8BF21CDEFE52BF81 ON property');
        $this->addSql('ALTER TABLE property DROP ads_id');
    }
}
