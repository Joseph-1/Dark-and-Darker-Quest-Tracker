<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250619162204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE quest_item (quest_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_111189EC209E9EF4 (quest_id), INDEX IDX_111189EC126F525E (item_id), PRIMARY KEY(quest_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quest_item ADD CONSTRAINT FK_111189EC209E9EF4 FOREIGN KEY (quest_id) REFERENCES quest (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quest_item ADD CONSTRAINT FK_111189EC126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE item ADD slug VARCHAR(50) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE quest_item DROP FOREIGN KEY FK_111189EC209E9EF4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quest_item DROP FOREIGN KEY FK_111189EC126F525E
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE quest_item
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE item DROP slug
        SQL);
    }
}
