<?php
/**
 * Created by PhpStorm.
 * User: richa
 * Date: 16.11.2016
 * Time: 10.16
 */

namespace Egb\Util\Twig;


use Leafo\ScssPhp\Compiler;
use Psr\Cache\CacheItemPoolInterface;

class SassRenderFunction extends \Twig_Extension {
	private $compiler;
	private $cachePool;

	/**
	 * RenderFunction constructor.
	 * @param Compiler $compiler
	 * @param CacheItemPoolInterface $cachePool
	 * @param $importRootDir
	 * @param string $sassFormatter Leafo\ScssPhp\Formatter\Expanded
	 *                              | Leafo\ScssPhp\Formatter\Nested
	 *                              | Leafo\ScssPhp\Formatter\Compressed
	 *                              | Leafo\ScssPhp\Formatter\Compact
	 *                              | Leafo\ScssPhp\Formatter\Crunched
	 */
	public function __construct(Compiler $compiler, CacheItemPoolInterface $cachePool, $importRootDir = null, $sassFormatter = 'Leafo\ScssPhp\Formatter\Crunched') {
		$this->compiler = $compiler;
		$this->compiler->addImportPath($importRootDir);
		$this->compiler->setFormatter($sassFormatter);
		$this->cachePool = $cachePool;
	}


	/**
	 * @return \Twig_SimpleFunction[]
	 */
	public function getFunctions() {
		return array(
			new \Twig_SimpleFunction('renderSass', array($this, 'sassTemplate'), array('needs_context' => true, 'needs_environment' => true, 'is_safe' => array('html'))),
		);
	}

	public function getFilters() {
		return array(
			new \Twig_SimpleFilter('sass', array($this, 'sassFilter'), array('is_safe' => array('html'))),
		);
	}

	public function sassFilter($content) {
		$cacheKey = "sass_inline_".sha1($content);
		$cacheItem = $this->cachePool->getItem($cacheKey);
		if ($cacheItem->isHit()) return $cacheItem->get();

		$sass = $this->compiler->compile($content);

		// Save the rendered sass file to cache
		$cacheItem->set($sass);
		$this->cachePool->save($cacheItem);

		// serve the file
		return $sass;
	}

	public function sassTemplate(\Twig_Environment $twig, $context, $template) {

		// Render twig file
		// TODO: Does TWIG cache this?!
		$renderedFile = $twig->render($template, $context);


		// Check if SASS is already in cache
		$cacheKey = $this->removeIllegalCharacters($template)."_".sha1($renderedFile);
		$cacheItem = $this->cachePool->getItem($cacheKey);
		if ($cacheItem->isHit())
			return $cacheItem->get();

		// Render sass
		$sass = $this->compiler->compile($renderedFile);

		// Save the rendered sass file to cache
		$cacheItem->set($sass);
		$this->cachePool->save($cacheItem);

		// serve the file
		return $sass;
	}

	public function getName() {
		return 'twig_sass';
	}

	/**
	 * @param $template
	 * @return mixed
	 * TODO: FIX THIS!!
	 */
	private function removeIllegalCharacters($template) {
		$illigalChars = "{,},(,),/,\\,@,:";
		foreach (explode(",", $illigalChars) as $char)
			$template = str_replace($char, "_", $template);

		return $template;
	}
}
