<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
final class Version20240321100942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create roles column with default value empty';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user ADD roles JSON DEFAULT (\'[]\') NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user DROP roles');
    }
}
