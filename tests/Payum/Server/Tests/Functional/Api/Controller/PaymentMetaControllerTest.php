<?php
namespace Payum\Server\Tests\Functional\Api\Controller;

use Payum\Server\Test\ClientTestCase;
use Payum\Server\Test\ResponseHelper;

class PaymentMetaControllerTest extends ClientTestCase
{
    use ResponseHelper;

    /**
     * @test
     */
    public function shouldAllowGetPaymentMeta()
    {
        $this->getClient()->request('GET', '/payments/meta');

        $this->assertClientResponseStatus(200);
        $this->assertClientResponseContentJson();

        $content = $this->getClientResponseJsonContent();

        $this->assertObjectHasAttribute('meta', $content);

        $this->assertObjectHasAttribute('totalAmount', $content->meta);
        $this->assertObjectHasAttribute('currencyCode', $content->meta);
        $this->assertObjectHasAttribute('clientEmail', $content->meta);
        $this->assertObjectHasAttribute('clientId', $content->meta);
    }

    /**
     * @test
     */
    public function shouldAllowGetPaymentGatewayConfigMeta()
    {
        $this->getClient()->request('GET', '/payments/meta');

        $this->assertClientResponseStatus(200);
        $this->assertClientResponseContentJson();

        $content = $this->getClientResponseJsonContent();

        $this->assertObjectHasAttribute('meta', $content);

        $this->assertObjectHasAttribute('gatewayConfig', $content->meta);

        $gatewayConfigMeta = $content->meta->gatewayConfig;
        $this->assertObjectHasAttribute('type', $gatewayConfigMeta);
        $this->assertEquals('form', $gatewayConfigMeta->type);

        $this->assertObjectHasAttribute('children', $gatewayConfigMeta);
        $this->assertObjectHasAttribute('gatewayName', $gatewayConfigMeta->children);

        $this->assertObjectHasAttribute('type', $gatewayConfigMeta->children->gatewayName);
        $this->assertEquals('choice', $gatewayConfigMeta->children->gatewayName->type);
    }


}
