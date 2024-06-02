<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240602113412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE log_record_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE log_record (id INT NOT NULL, service_name VARCHAR(32) NOT NULL, status_code SMALLINT NOT NULL, event_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX status ON log_record (status_code)');
        $this->addSql('CREATE INDEX service ON log_record (service_name)');
        $this->addSql('CREATE INDEX date ON log_record (event_time)');
        $this->addSql('COMMENT ON COLUMN log_record.event_time IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE log_record_id_seq CASCADE');
        $this->addSql('DROP TABLE log_record');
    }
}
