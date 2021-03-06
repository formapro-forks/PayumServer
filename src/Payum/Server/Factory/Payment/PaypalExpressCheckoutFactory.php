<?php
namespace Payum\Server\Factory\Payment;

use Payum\Paypal\ExpressCheckout\Nvp\Api;
use Payum\Paypal\ExpressCheckout\Nvp\PaymentFactory;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class PaypalExpressCheckoutFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function configureOptionsFormBuilder(FormBuilderInterface $builder)
    {
        $builder
            ->add('username', 'text', array(
                'required' => true,
                'constraints' => array(new NotBlank),
            ))
            ->add('password', 'text', array(
                'required' => true,
                'constraints' => array(new NotBlank),
            ))
            ->add('signature', 'text', array(
                'required' => true,
                'constraints' => array(new NotBlank),
            ))
            ->add('sandbox', 'checkbox', array(
                'required' => false,
                'data' => true,
                'empty_data' => true,
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function createPayment(array $options)
    {
        return PaymentFactory::create(new Api($options));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'paypal_express_checkout';
    }
}