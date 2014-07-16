<?php

namespace Jb\Bundle\SearchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
    * {@inheritdoc}
    */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('jb_search');

        $rootNode
            ->children()
                ->booleanNode('enabled')
                    ->defaultTrue()
                ->end()
                ->scalarNode('engine')
                    ->validate()
                    ->ifNotInArray(array('indextank'))
                        ->thenInvalid('Invalid search engine : "%s"')
                    ->end()
                    ->defaultValue('indextank')
                ->end()
                ->arrayNode('options')
                    ->isRequired()
                    ->children()
                        ->scalarNode('url')->isRequired()->end()
                        ->scalarNode('user')->isRequired()->end()
                        ->scalarNode('password')->isRequired()->end()
                        ->scalarNode('index')->isRequired()->end()
                    ->end()
                ->end()
            ->end();
        ;

        return $treeBuilder;
    }
}
