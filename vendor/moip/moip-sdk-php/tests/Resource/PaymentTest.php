<?php

namespace Moip\Tests\Resource;

use Moip\Tests\MoipTestCase;

class PaymentTest extends MoipTestCase
{
    //todo: credit card hash

    /**
     * Test creating a credit card payment, passing all credit card data.
     */
    public function testCreditCardPCI()
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();
        $this->mockHttpSession($this->body_cc_pay_pci);
        $cc = '5555666677778884';
        $payment = $order->payments()->setCreditCard(5, 2018, $cc, 123, $this->createCustomer())->execute();
        $this->assertNotEmpty($payment->getFundingInstrument()->creditCard);
        $first6 = $payment->getFundingInstrument()->creditCard->first6;
        $last4 = $payment->getFundingInstrument()->creditCard->last4;
        $this->assertEquals($first6, substr($cc, 0, 6));
        $this->assertEquals($last4, substr($cc, -4));
    }

    /**
     * Test creating a billet payment.
     */
    public function testBillet()
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();
        $this->mockHttpSession($this->body_billet_pay);
        $payment = $order->payments()->setBoleto(new \DateTime('today +1day'),
            'http://dev.moip.com.br/images/logo-header-moip.png')->execute();
        $this->assertNotEmpty($payment->getFundingInstrument()->boleto);
        $pay_id = $payment->getId();
        $this->assertEquals($payment->getLinks()->getLink('payBoleto')->getHref(),
            "https://checkout-sandbox.moip.com.br/boleto/$pay_id");
    }
}
