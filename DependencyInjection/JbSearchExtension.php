<?php

namespace Jb\Bundle\SearchBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Sculpin Search Extension.
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class JbSearchExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration;
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('jb_sculpin.search.enabled', $config['enabled']);
        $container->setParameter('jb_sculpin.search.engine', $config['engine']);
        $container->setParameter('jb_sculpin.search.options.url', $config['options']['url']);
        $container->setParameter('jb_sculpin.search.options.user', $config['options']['user']);
        $container->setParameter('jb_sculpin.search.options.password', $config['options']['password']);
        $container->setParameter('jb_sculpin.search.options.index', $config['options']['index']);

        $referenceSearch = new Reference('jb_sculpin.search.engine.'.$config['engine']);
        $referenceBuilder = new Reference('jb_sculpin.search.document_builder.'.$config['engine']);
        $container
            ->getDefinition('jb_sculpin.search.indexation.listener')
            ->replaceArgument(0, $referenceSearch)
            ->replaceArgument(1, $referenceBuilder);
    }
}
