<?php

namespace Spygar\Magento2\DependencyInjection;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;


class SpygarMagento2Extension extends Extension
{
     /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // $configuration = new Configuration();
        // $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        
        $loader->load('services.yml');
        $loader->load('repository.yml');
        $loader->load('controllers.yml');
        $loader->load('jobs.yml');
        $loader->load('steps.yml');
        $loader->load('readers.yml');
        // $loader->load('processors.yml');
        $loader->load('writers.yml');
        $loader->load('form_providers.yml');
        $loader->load('job_parameters.yml');

    }
}