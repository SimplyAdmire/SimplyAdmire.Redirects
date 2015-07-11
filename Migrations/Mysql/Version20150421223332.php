<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20150421223332 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE simplyadmire_redirects_domain_model_redirect (persistence_object_identifier VARCHAR(40) NOT NULL, path VARCHAR(255) NOT NULL, pathhash VARCHAR(255) NOT NULL, targeturl VARCHAR(255) NOT NULL, statuscode VARCHAR(255) NOT NULL, INDEX pathhash (pathhash), UNIQUE INDEX UNIQ_F545F4402DBEC75787B98BF8 (pathhash, targeturl), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("DROP TABLE simplyadmire_redirects_domain_model_redirect");
	}
}
