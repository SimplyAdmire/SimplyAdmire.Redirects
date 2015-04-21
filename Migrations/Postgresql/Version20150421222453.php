<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20150421222453 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("CREATE TABLE simplyadmire_redirects_domain_model_redirect (persistence_object_identifier VARCHAR(40) NOT NULL, path VARCHAR(255) NOT NULL, pathhash VARCHAR(255) NOT NULL, targeturl VARCHAR(255) NOT NULL, statuscode VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier))");
		$this->addSql("CREATE INDEX pathhash ON simplyadmire_redirects_domain_model_redirect (pathhash)");
		$this->addSql("CREATE UNIQUE INDEX UNIQ_F545F4402DBEC75787B98BF8 ON simplyadmire_redirects_domain_model_redirect (pathhash, targeturl)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("CREATE SCHEMA public");
		$this->addSql("DROP TABLE simplyadmire_redirects_domain_model_redirect");
	}
}