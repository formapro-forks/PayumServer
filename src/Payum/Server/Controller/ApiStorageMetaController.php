<?php
namespace Payum\Server\Controller;

use Payum\Server\Factory\Storage\FactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiStorageMetaController
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var array|FactoryInterface[]
     */
    private $factories;

    /**
     * @param FormFactoryInterface $formFactory
     * @param FactoryInterface[] $factories
     */
    public function __construct(FormFactoryInterface $formFactory, array $factories)
    {
        $this->formFactory = $formFactory;
        $this->factories = $factories;
    }

    /**
     * @return JsonResponse
     */
    public function getAllAction()
    {
        $normalizedFactories = array();

        foreach ($this->factories as $name => $factory) {
            $normalizedFactories[$name] = $this->normalizeFactory($factory);
        }

        return new JsonResponse(array('metas' => $normalizedFactories));
    }

    protected function normalizeFactory(FactoryInterface $factory)
    {
        $builder = $this->formFactory->createBuilder('form', null, array(
            'csrf_protection' => false,
        ));

        $factory->configureOptionsFormBuilder($builder);

        $formView = $builder->getForm()->createView();

        $options = array();
        foreach ($formView->children as $name => $child) {
            $options[$name] = array(
                'default' => $child->vars['data'],
                'label' => $child->vars['label'],
                'required' => $child->vars['required'],
            );

            if (in_array('text', $child->vars['block_prefixes'])) {
                $options[$name]['type'] = 'text';
            } elseif (in_array('checkbox', $child->vars['block_prefixes'])) {
                $options[$name]['type'] = 'checkbox';
            } elseif (in_array('choice', $child->vars['block_prefixes'])) {
                $options[$name]['type'] = 'choice';
                $options[$name]['choices'] = $child->vars['choices'];
            } else {
                $options[$name]['type'] = 'text';
            }
        }

        return array(
            'name' => $factory->getName(),
            'options' => $options
        );
    }
}
