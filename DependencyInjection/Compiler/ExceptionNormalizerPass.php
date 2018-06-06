<?php

namespace Pepsit36\ApiHelperBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ExceptionNormalizerPass implements CompilerPassInterface
{
	/**
	 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 */
	public function process(ContainerBuilder $container): void
	{
		$exceptionListenerDefinition = $container->findDefinition('pepsit36.apihelper.listener.exception_listener');
		$normalizers = $container->findTaggedServiceIds('exception.normalizer');

		foreach ($normalizers as $id => $tags) {
			$exceptionListenerDefinition->addMethodCall('addNormalizer', [new Reference($id)]);
		}
	}
}
