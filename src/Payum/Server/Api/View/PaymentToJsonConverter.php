<?php
namespace Payum\Server\Api\View;

use Payum\Core\Registry\GatewayRegistryInterface;
use Payum\Server\Model\Payment;

class PaymentToJsonConverter
{
    /**
     * @var GatewayRegistryInterface
     */
    private $registry;

    /**
     * @var GatewayConfigToJsonConverter
     */
    private $gatewayConfigToJsonConverter;

    /**
     * @param GatewayRegistryInterface $registry
     * @param GatewayConfigToJsonConverter $gatewayConfigToJsonConverter
     */
    public function __construct(GatewayRegistryInterface $registry, GatewayConfigToJsonConverter $gatewayConfigToJsonConverter)
    {
        $this->registry = $registry;
        $this->gatewayConfigToJsonConverter = $gatewayConfigToJsonConverter;
    }

    /**
     * @param Payment $payment
     *
     * @return array
     */
    public function convert(Payment $payment)
    {
        return [
            'id' => $payment->getId(),
            'status' => $payment->getStatus(),
            'number' => $payment->getNumber(),
            'totalAmount' => $payment->getTotalAmount(),
            'currencyCode' => $payment->getCurrencyCode(),
            'clientEmail' => $payment->getClientEmail(),
            'clientId' => $payment->getClientId(),
            'description' => $payment->getDescription(),
            'details' => $payment->getDetails(),
            'gateway' => $this->gatewayConfigToJsonConverter->convert($payment->getGatewayConfig()),
        ];
    }
}
