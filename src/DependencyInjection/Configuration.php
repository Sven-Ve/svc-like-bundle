<?php

namespace Svc\LikeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
  public function getConfigTreeBuilder()
  {
    $treeBuilder = new TreeBuilder('svc_like'); # ohne Bundle, so muss es dann im yaml-file heissen
    $rootNode = $treeBuilder->getRootNode();
    return $treeBuilder;
  }

}