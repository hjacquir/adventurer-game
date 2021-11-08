<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211001183831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add gps coordinates';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE gps_coordinates_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE gps_coordinates (id INT NOT NULL, latitude INT NOT NULL, longitude INT NOT NULL,PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7401B253C ON gps_coordinates (latitude)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7401B253D ON gps_coordinates (longitude)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE gps_coordinates_id_seq CASCADE');
        $this->addSql('DROP TABLE gps_coordinates');
    }
}
