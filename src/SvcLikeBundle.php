<?php

namespace Svc\LikeBundle;

use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SvcLikeBundle extends AbstractBundle
{
  public function getPath(): string
  {
    return \dirname(__DIR__);
  }

  public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
  {
    $container->import('../config/services.yaml');
  }

  public function prependExtension(ContainerConfigurator $containerConfigurator, ContainerBuilder $containerBuilder): void
  {
    if (!$this->isAssetMapperAvailable($containerBuilder)) {
      return;
    }

    $containerBuilder->prependExtensionConfig('framework', [
      'asset_mapper' => [
        'paths' => [
          __DIR__ . '/../assets/src' => 'svc/like-bundle/src',
          __DIR__ . '/../assets/styles' => 'svc/like-bundle/styles',
        ],
      ],
    ]);
  }

  private function isAssetMapperAvailable(ContainerBuilder $container): bool
  {
    if (!interface_exists(AssetMapperInterface::class)) {
      return false;
    }

    // check that FrameworkBundle 6.3 or higher is installed
    $bundlesMetadata = $container->getParameter('kernel.bundles_metadata');
    if (!isset($bundlesMetadata['FrameworkBundle'])) {
      return false;
    }

    return is_file($bundlesMetadata['FrameworkBundle']['path'] . '/Resources/config/asset_mapper.php');
  }
}
