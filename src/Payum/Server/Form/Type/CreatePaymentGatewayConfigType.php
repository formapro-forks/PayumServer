<?php
namespace Payum\Server\Form\Type;

use Payum\Server\Model\GatewayConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreatePaymentGatewayConfigType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gatewayName', 'payum_gateways_choice', [
                'required' => false,
                'empty_value' => '',
            ])
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => GatewayConfig::class,
            'allow_extra_fields' => true,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'create_payment_gateway_config';
    }
}