<?php
namespace SimplyAdmire\Redirects\Command;

use SimplyAdmire\Redirects\Domain\Model\Redirect;
use TYPO3\Flow\Annotations as Flow;
use SimplyAdmire\Redirects\Domain\Repository\RedirectRepository;
use TYPO3\Flow\Cli\CommandController;

class ImportCommandController extends CommandController {

	/**
	 * @Flow\Inject
	 * @var RedirectRepository
	 */
	protected $redirectRepository;

	/**
	 * Import redirects from a whitespace (tab or spaces) delimited source file
	 *
	 * Every line of the file should be a redirect, empty lines are allowed.
	 * The lines should have the following format:
	 *
	 * 	<path>	<target url>	<status code>
	 *
	 * The status code is optional, if left out code 301 will be used
	 *
	 * @param string $path
	 */
	public function fromFileCommand($path) {
		if (!file_exists($path)) {
			$this->outputLine('File %s can not be found', [$path]);
			$this->quit(1);
		}

		$updateCount = 0;
		$createCount = 0;

		$lines = file($path);
		foreach ($lines as $line) {
			$line = trim($line);
			if (empty($line)) {
				continue;
			}

			preg_match('/(?P<path>[^\s]*)[\s]*(?P<target>[^\s]*)[\s]*(?P<statuscode>[0-9]*)/', $line, $match);

			if (empty($match['path']) || empty($match['target'])) {
				$this->outputLine('Skipping line because either path or target is not set: %s', [$line]);
				continue;
			}

			$statusCode = !empty($match['statuscode']) ? (int)$match['statuscode'] : 301;

			$redirect = $this->redirectRepository->findOneByPathHash(sha1($match['path']));
			if ($redirect instanceof Redirect) {
				if ($redirect->getTargetUrl() !== $match['target'] || $redirect->getStatusCode() !== $statusCode) {
					$redirect->setTargetUrl($match['target']);
					$redirect->setStatusCode($statusCode);
					$this->redirectRepository->update($redirect);
					$updateCount++;
				}
			} else {
				$redirect = new Redirect();
				$redirect->setPath($match['path']);
				$redirect->setTargetUrl($match['target']);
				$redirect->setStatusCode($statusCode);
				$this->redirectRepository->add($redirect);
				$createCount++;
			}
		}

		$this->outputLine(
			'Done. Created %s new redirect%s and updated %s already existing redirect%s',
			[
				$createCount,
				$createCount === 1 ? '' : 's',
				$updateCount,
				$updateCount === 1 ? '' : 's'
			]
		);
	}

}