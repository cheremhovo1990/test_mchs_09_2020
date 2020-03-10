<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200310162257 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX currency_unit_un_char_code ON currency_unit (char_code)');
        $this->addSql('ALTER TABLE currency ADD currency_unit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE currency ADD CONSTRAINT FK_6956883F25148AE2 FOREIGN KEY (currency_unit_id) REFERENCES currency_unit (id)');
        $this->addSql('CREATE INDEX IDX_6956883F25148AE2 ON currency (currency_unit_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE currency DROP FOREIGN KEY FK_6956883F25148AE2');
        $this->addSql('DROP INDEX IDX_6956883F25148AE2 ON currency');
        $this->addSql('ALTER TABLE currency DROP currency_unit_id');
        $this->addSql('DROP INDEX currency_unit_un_char_code ON currency_unit');
    }
}
