<?php
namespace SimplyAdmire\Redirects\Mvc\Routing;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Http\Component\Exception as ComponentException;
use TYPO3\Flow\Http\Component\ComponentContext;
use TYPO3\Flow\Mvc\DispatchComponent;

class RedirectComponent extends DispatchComponent {

	/**
	 * @Flow\Inject
	 * @var ObjectManager
	 */
	protected $entityManager;

	/**
	 * @param ComponentContext $componentContext
	 * @return void
	 * @throws ComponentException
	 */
	public function handle(ComponentContext $componentContext) {
		$httpRequest = $componentContext->getHttpRequest();
		$requestPath = $httpRequest->getUri()->getPath();

		$queryString = $httpRequest->getUri()->getQuery() !== NULL ? $requestPath . '?' . $httpRequest->getUri()->getQuery() : NULL;

		/** @var Connection $connection */
		$connection = $this->entityManager->getConnection();
		$findRedirectQuery = $connection->prepare('SELECT targeturl, statuscode FROM simplyadmire_redirects_domain_model_redirect WHERE pathhash=:pathhash');
		$findRedirectQuery->execute([':pathhash' => sha1($queryString)]);

		if ($findRedirectQuery->rowCount() === 0) {
			$findRedirectQuery->execute([':pathhash' => sha1($requestPath)]);
		}

		if ($findRedirectQuery->rowCount() > 0) {
			$redirect = $findRedirectQuery->fetch(\PDO::FETCH_ASSOC);

			$httpResponse = $componentContext->getHttpResponse();
			$httpResponse->setStatus((integer)$redirect['statuscode']);
			$httpResponse->setHeader('Location', $redirect['targeturl']);

			// stop processing the current component chain
			$componentContext->setParameter('TYPO3\Flow\Http\Component\ComponentChain', 'cancel', TRUE);
		}
	}

}