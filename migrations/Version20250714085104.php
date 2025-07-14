<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250714085104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE item ADD rarity_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE item ADD CONSTRAINT FK_1F1B251EF3747573 FOREIGN KEY (rarity_id) REFERENCES rarity (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1F1B251EF3747573 ON item (rarity_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EF3747573
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_1F1B251EF3747573 ON item
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE item DROP rarity_id
        SQL);
    }
}
