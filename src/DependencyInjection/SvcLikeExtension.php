<?php

namespace Svc\LikeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SvcLikeExtension extends Extension
{
  public function load(array $configs, ContainerBuilder $container)
  {
    // $rootPath = $container->getParameter("kernel.project_dir");
    // $this->createConfigIfNotExists($rootPath);

    $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
    $loader->load('services.xml');

    $configuration = $this->getConfiguration($configs, $container);
    $config = $this->processConfiguration($configuration, $configs);

    // set arguments for __construct in services
//    $definition = $container->getDefinition('svc_profile.controller.change-pw');
//    $definition->setArgument(1, $config['enableCaptcha']);
  }

  private function createConfigIfNotExists($rootPath)
  {
  }

}
