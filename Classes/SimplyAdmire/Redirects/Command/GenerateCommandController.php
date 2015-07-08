<?php
namespace SimplyAdmire\Redirects\Command;

use SimplyAdmire\Redirects\Domain\Model\Redirect;
use TYPO3\Flow\Annotations as Flow;
use SimplyAdmire\Redirects\Domain\Repository\RedirectRepository;
use TYPO3\Flow\Cli\CommandController;

class GenerateCommandController extends CommandController {

	/**
	 * @Flow\Inject
	 * @var RedirectRepository
	 */
	protected $redirectRepository;

	/**
	 * Generates a list of rules that can be used in a .htaccess file
	 *
	 * @return void
	 */
	public function htaccessRulesCommand() {
		$redirects = $this->redirectRepository->findAll();
		if ($redirects->count() === 0) {
			$this->outputLine('No redirects found');
			$this->quit(1);
		}

		/** @var Redirect $redirect */
		foreach ($redirects as $redirect) {
			$this->outputLine('Redirect %s %s %s', [$redirect->getStatusCode(), $redirect->getPath(), $redirect->getTargetUrl()]);
		}
	}

	/**
	 * Generates a list of rules that can be used in an nginx configuration file
	 *
	 * This command now only handles 301 and 302 redirects
	 *
	 * @TODO: Extend this command to make full use of the rewrite possibilities like break, last, and so on
	 * @return void
	 */
	public function nginxRulesCommand() {
		$rules = $this->generateNginxRulesArray();
		foreach ($rules as $rule) {
			$this->outputLine($rule);
		}
	}

	/**
	 * @param string $path
	 */
	public function nginxConfigurationFileCommand($path) {
		$rules = $this->generateNginxRulesArray();
		file_put_contents($path, implode(chr(10), $rules) . chr(10));
		$this->outputLine('Wrote %s rule%s to %s', [count($rules), count($rules) === 1 ? '' : 's', $path]);
	}

	/**
	 * @return array
	 * @throws \TYPO3\Flow\Mvc\Exception\StopActionException
	 */
	protected function generateNginxRulesArray() {
		$redirects = $this->redirectRepository->findAll();
		if ($redirects->count() === 0) {
			$this->outputLine('No redirects found');
			$this->quit(1);
		}

		$rules = [];

		/** @var Redirect $redirect */
		foreach ($redirects as $redirect) {
			if (!in_array($redirect->getStatusCode(), [301, 302])) {
				$this->outputLine('Found status code %s which is not supported by this command', [$redirect->getStatusCode()]);
				$this->quit(1);
			}

			$rules[] = sprintf('rewrite ^%s %s %s', $redirect->getPath(), $redirect->getTargetUrl(), $redirect->getStatusCode() === 302 ? 'redirect' : 'permanent');
		}

		return $rules;
	}
}