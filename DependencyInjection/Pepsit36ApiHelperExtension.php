<?php

namespace Pepsit36\ApiHelperBundle\DependencyInjection;

use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class Pepsit36ApiHelperExtension extends Extension
{

	/**
	 * Loads a specific configuration.
	 *
	 * @param array            $configs
	 * @param ContainerBuilder $container
	 *
	 * @throws \Exception
	 */
	public function load(array $configs, ContainerBuilder $container): void
	{
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
		$loader->load('services.yml');

		try {
			$loader->load('services_' . $container->getParameter("kernel.environment") . '.yml');
		} catch (FileLocatorFileNotFoundException $e) {
		}
	}
}