<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Moip\Moip;
use Moip\MoipBasicAuth;
class Welcome extends CI_Controller {

	/**
	* Index Page for this controller.
	*
	* Maps to the following URL
	* 		http://example.com/index.php/welcome
	*	- or -
	* 		http://example.com/index.php/welcome/index
	*	- or -
	* Since this controller is set as the default controller in
	* config/routes.php, it's displayed at http://example.com/
	*
	* So any other public methods not prefixed with an underscore will
	* map to /index.php/welcome/<method_name>
	* @see https://codeigniter.com/user_guide/general/urls.html
	*/
	public function index()
	{
		$token = '01010101010101010101010101010101';
		$key = 'ABABABABABABABABABABABABABABABABABABABAB';

		$moip = new Moip(new MoipBasicAuth($token, $key), Moip::ENDPOINT_SANDBOX);

		/* CRIANDO O COMPRADOR */
		echo "CRIANDO O COMPRADOR";
		try {
			$customer = $moip->customers()->setOwnId(uniqid())
			->setFullname('Fulano de Tal')
			->setEmail('fulano@email.com')
			->setBirthDate('1988-12-30')
			->setTaxDocument('22222222222')
			->setPhone(11, 66778899)
			->addAddress('BILLING',
			'Rua de teste', 123,
			'Bairro', 'Sao Paulo', 'SP',
			'01234567', 8)
			->addAddress('SHIPPING',
			'Rua de teste do SHIPPING', 123,
			'Bairro do SHIPPING', 'Sao Paulo', 'SP',
			'01234567', 8)
			->create();
			var_dump($customer);
		} catch (Exception $e) {
			var_dump($e->__toString());
		}

		/* CRIANDO O PEDIDO */
		echo "CRIANDO O PEDIDO";
		try {
			$order = $moip->orders()->setOwnId(uniqid())
			->addItem("bicicleta 1",1, "sku1", 10000)
			->addItem("bicicleta 2",1, "sku2", 11000)
			->addItem("bicicleta 3",1, "sku3", 12000)
			->addItem("bicicleta 4",1, "sku4", 13000)
			->addItem("bicicleta 5",1, "sku5", 14000)
			->addItem("bicicleta 6",1, "sku6", 15000)
			->addItem("bicicleta 7",1, "sku7", 16000)
			->addItem("bicicleta 8",1, "sku8", 17000)
			->addItem("bicicleta 9",1, "sku9", 18000)
			->addItem("bicicleta 10",1, "sku10", 19000)
			->setShippingAmount(3000)->setAddition(1000)->setDiscount(5000)
			->setCustomer($customer)
			->create();

			var_dump($order);
		} catch (Exception $e) {
			var_dump($e->__toString());
		}

		/* CRIANDO O PAGAMENTO */
		echo "CRIANDO O PAGAMENTO";
		try {
			$payment = $order->payments()->setCreditCard(12, 21, '4073020000000002', '123', $customer)
			->execute();

			var_dump($payment);
		} catch (Exception $e) {
			var_dump($e->__toString());
		}
		
	}
}
