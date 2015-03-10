<?php
namespace W3build\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;
use W3build\Admin\DataGridBundle\DependencyInjection\W3buildAdminDataGridExtension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class W3buildAdminExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('host', $_SERVER['HTTP_HOST']);
    }

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        $appDir = $container->getParameter('kernel.root_dir');

        $src = realpath($appDir . '/../src');
        $w3build = realpath($appDir . '/../vendor/w3build');

        $finder = new Finder();
        $finder->files()
            ->in(array($w3build, $src))
            ->name('security.yml')
            ->followLinks();

        $security = array();

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach($finder as $file){
            $options = Yaml::parse(file_get_contents($file->getRealPath()));
            if(!$security){
                $security = $options['security'];
            }
            else {
                $keys = array_keys($options['security']);
                foreach($keys as $key){
                    if(array_key_exists($security, $key)){
                        $security[$key] = array_merge($security[$key], $options['security'][$key]);
                    }
                }
            }
        }

        $container->prependExtensionConfig('security', $security);
    }

}