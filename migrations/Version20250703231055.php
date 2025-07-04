<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250703231055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE quest_item DROP FOREIGN KEY FK_111189EC126F525E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quest_item DROP FOREIGN KEY FK_111189EC209E9EF4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quest_item ADD id INT AUTO_INCREMENT NOT NULL, ADD required_count INT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quest_item ADD CONSTRAINT FK_111189EC126F525E FOREIGN KEY (item_id) REFERENCES item (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quest_item ADD CONSTRAINT FK_111189EC209E9EF4 FOREIGN KEY (quest_id) REFERENCES quest (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE quest_item MODIFY id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quest_item DROP FOREIGN KEY FK_111189EC209E9EF4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quest_item DROP FOREIGN KEY FK_111189EC126F525E
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON quest_item
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quest_item DROP id, DROP required_count
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quest_item ADD CONSTRAINT FK_111189EC209E9EF4 FOREIGN KEY (quest_id) REFERENCES quest (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quest_item ADD CONSTRAINT FK_111189EC126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quest_item ADD PRIMARY KEY (quest_id, item_id)
        SQL);
    }
}
