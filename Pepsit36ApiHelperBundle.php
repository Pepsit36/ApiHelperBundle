<?php

namespace Pepsit36\ApiHelperBundle;

use Pepsit36\ApiHelperBundle\DependencyInjection\Compiler\ExceptionNormalizerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Pepsit36ApiHelperBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);
		$container->addCompilerPass(new ExceptionNormalizerPass());
	}
}