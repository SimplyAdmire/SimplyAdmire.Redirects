<?php
namespace SimplyAdmire\Redirects\Domain\Model;

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 * @ORM\Table(
 * 	uniqueConstraints={
 * 		@ORM\UniqueConstraint(name="path_target",columns={"pathhash", "targeturl"})
 * 	},
 * 	indexes={
 * 		@ORM\Index(name="pathhash",columns={"pathhash"})
 * 	}
 * )
 */
class Redirect {

	/**
	 * @var string
	 */
	protected $path;

	/**
	 * @var string
	 */
	protected $pathHash;

	/**
	 * @var string
	 */
	protected $targetUrl;

	/**
	 * @var string
	 */
	protected $statusCode = 301;

	/**
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * @param string $path
	 */
	public function setPath($path) {
		$this->path = $path;
		$this->setPathHash(md5($path));
	}

	/**
	 * @return string
	 */
	public function getPathHash() {
		return $this->pathHash;
	}

	/**
	 * @param string $pathHash
	 */
	public function setPathHash($pathHash) {
		$this->pathHash = $pathHash;
	}

	/**
	 * @return string
	 */
	public function getTargetUrl() {
		return $this->targetUrl;
	}

	/**
	 * @param string $targetUrl
	 */
	public function setTargetUrl($targetUrl) {
		$this->targetUrl = $targetUrl;
	}

	/**
	 * @return string
	 */
	public function getStatusCode() {
		return $this->statusCode;
	}

	/**
	 * @param string $statusCode
	 */
	public function setStatusCode($statusCode) {
		$this->statusCode = $statusCode;
	}


}