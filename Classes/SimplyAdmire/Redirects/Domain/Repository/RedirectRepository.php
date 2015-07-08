<?php
namespace SimplyAdmire\Redirects\Domain\Repository;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Doctrine\Repository;
use TYPO3\Flow\Persistence\QueryInterface;

/**
 * @Flow\Scope("singleton")
 */
class RedirectRepository extends Repository {

	/**
	 * @var array
	 */
	protected $defaultOrderings = ['path' => QueryInterface::ORDER_ASCENDING];

}