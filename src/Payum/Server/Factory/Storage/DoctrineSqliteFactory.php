<?php
namespace Payum\Server\Factory\Storage;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use Doctrine\ORM\Tools\SchemaTool;
use Payum\Core\Bridge\Doctrine\Storage\DoctrineStorage;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class DoctrineSqliteFactory implements FactoryInterface
{
    /**
     * @var string
     */
    private $rootDir;

    /**
     * @param string $rootDir
     */
    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptionsFormBuilder(FormBuilderInterface $builder)
    {
        $builder
            ->add('path', 'text', array(
                'data' => 'file:'.$this->rootDir.'/payum.sqlite.db',
                'required' => true,
                'constraints' => array(new NotBlank),
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function createStorage($modelClass, $idProperty, array $options)
    {
        return new DoctrineStorage($this->createManager($options), $modelClass);
    }

    /**
     * {@inheritDoc}
     */
    public function init(array $options)
    {
        $em = $this->createManager($options);

        $classes = $em->getMetadataFactory()->getAllMetadata();

        $schemaTool = new SchemaTool($em);
        $schemaTool->updateSchema($classes);
    }

    /**
     * @param array $options
     *
     * @return EntityManager
     */
    protected function createManager(array $options)
    {
        $config = new Configuration();
        $driver = new MappingDriverChain;

        // payum's basic models
        $driver->addDriver(
            new SimplifiedXmlDriver(array($this->rootDir.'/vendor/payum/payum/src/Payum/Core/Bridge/Doctrine/Resources/mapping' => 'Payum\Core\Model')),
            'Payum\Core\Model'
        );

        // your models
        $driver->addDriver(
            $config->newDefaultAnnotationDriver(array($this->rootDir.'/src/Payum/Server/Model'), false),
            'Payum\Server\Model'
        );

        $config->setAutoGenerateProxyClasses(true);
        $config->setProxyDir(\sys_get_temp_dir());
        $config->setProxyNamespace('Proxies');
        $config->setMetadataDriverImpl($driver);
        $config->setQueryCacheImpl(new ArrayCache());
        $config->setMetadataCacheImpl(new ArrayCache());

        $connection = array('driver' => 'pdo_sqlite', 'path' => $options['path']);

        return EntityManager::create($connection, $config);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'doctrine_sqlite';
    }

    /**
     * {@inheritDoc}
     */
    public function getTitle()
    {
        return 'Doctrine Sqlite';
    }
}
