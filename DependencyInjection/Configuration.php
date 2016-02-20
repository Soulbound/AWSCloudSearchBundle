<?php

namespace SAWSCS\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sawscs');

        $rootNode
            ->children()
                ->arrayNode('credentials')
                    ->children()
                        ->scalarNode('aws_key')->end()
                        ->scalarNode('aws_secret')->end()
                    ->end()
                ->end()
                ->scalarNode('default_domain')->end()
                ->scalarNode('aws_region')->end()
                ->scalarNode('aws_version')->end()
                ->arrayNode('domains')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('credentials')
                                ->children()
                                    ->scalarNode('aws_key')->end()
                                    ->scalarNode('aws_secret')->end()
                                ->end()
                            ->end()
                            ->scalarNode('aws_region')->end()
                            ->scalarNode('aws_version')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
