<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250714085659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE rarity DROP FOREIGN KEY FK_B7C0BE464A908604
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B7C0BE464A908604 ON rarity
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rarity ADD name VARCHAR(100) NOT NULL, DROP rarity_item_id, DROP rarity
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE rarity ADD rarity_item_id INT DEFAULT NULL, ADD rarity VARCHAR(40) DEFAULT NULL, DROP name
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rarity ADD CONSTRAINT FK_B7C0BE464A908604 FOREIGN KEY (rarity_item_id) REFERENCES item (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B7C0BE464A908604 ON rarity (rarity_item_id)
        SQL);
    }
}
