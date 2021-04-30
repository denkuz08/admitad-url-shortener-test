<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210430021905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO user(login, api_token) VALUES('test_user1', '12c1480dcaa94584ed5ef87ff8ecb855')");
        $this->addSql("INSERT INTO user(login, api_token) VALUES('test_user2', 'c0fea7aaf4d6d1243f584c003ad5b1d3')");

        $this->addSql("INSERT INTO url(created_user_id, url, short_code, created_at) VALUES(1, 'https://google.com/', 'ggl', '2021-04-29')");
    }

    public function down(Schema $schema): void
    {
    }
}
