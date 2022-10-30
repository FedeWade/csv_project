<?php

namespace Federico\Bundle\CsvManagerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class CsvManagerExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__. "/../Resources/config"));
        $loader->load('services.xml');
/*
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('federico_bundle_csv_manager.processors.web_services_processor');
        $definition->replaceArgument(0, $config['csv_options_configuration']);*/
    }

}