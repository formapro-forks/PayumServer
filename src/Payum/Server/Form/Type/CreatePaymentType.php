<?php
namespace Payum\Server\Form\Type;

use Payum\Server\Model\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Url;

class CreatePaymentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gatewayConfig', 'create_payment_gateway_config')
            ->add('totalAmount', 'number', array(
                'label' => 'Amount',
                'constraints' => array(new NotBlank(), new Type(['type' => 'numeric']))
            ))
            ->add('currencyCode', 'choice', array(
                'choices' => ['USD' => 'US Dollar', 'EUR' => 'Euro', 'SEK' => 'Swedish krona'],
                'label' => 'Currency',
                'data' => 'USD',
                'constraints' => array(new NotBlank(), new Choice(['USD', 'EUR', 'SEK'])),
            ))
            ->add('clientEmail', 'text', array(
                'required' => false,
                'constraints' => array(new Email()))
            )
            ->add('clientId', 'text', array(
                'required' => false,
            ))
            ->add('description', 'text', array(
                'required' => false,
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Payment::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ));
        $resolver->setOptional(array('factory'));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'create_payment';
    }
}