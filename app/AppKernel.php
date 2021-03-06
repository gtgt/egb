<?php
ob_start();
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel {
	public function registerBundles() {
		$bundles = array(
			new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new Symfony\Bundle\SecurityBundle\SecurityBundle(),
			new Symfony\Bundle\TwigBundle\TwigBundle(),
			new Symfony\Bundle\MonologBundle\MonologBundle(),
			new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
			new Symfony\Bundle\AsseticBundle\AsseticBundle(),
			new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
			new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
			new JMS\SerializerBundle\JMSSerializerBundle(),
			new FOS\RestBundle\FOSRestBundle(),
			new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
			#new FOS\HttpCacheBundle\FOSHttpCacheBundle(),
			new Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),
			new Hautelook\TemplatedUriBundle\HautelookTemplatedUriBundle(),
			new Elao\Bundle\FormTranslationBundle\ElaoFormTranslationBundle(),
			new Bazinga\Bundle\RestExtraBundle\BazingaRestExtraBundle(),
			new FOS\UserBundle\FOSUserBundle(),
		);

		if (in_array($this->getEnvironment(), array('dev', 'test'))) {
			foreach (array('AppBundle\AppBundle', 'Egb\UserBundle\UserBundle', 'Egb\ClassBundle\ClassBundle', 'Egb\SubjectBundle\SubjectBundle', 'Symfony\Bundle\WebProfilerBundle\WebProfilerBundle', 'Sensio\Bundle\DistributionBundle\SensioDistributionBundle', 'Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle') as $bundleClass) {
				if (class_exists($bundleClass)) $bundles[] = new $bundleClass();
			}
		}

		return $bundles;
	}

	public function registerContainerConfiguration(LoaderInterface $loader) {
		$loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
	}
}
