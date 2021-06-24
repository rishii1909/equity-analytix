<?php

require __DIR__ . '/../vendor/autoload.php';

use net\authorize\api\contract\v1 as AnetApi;
use net\authorize\api\controller as AnetController;

function ea_create_subscription($data) {
	$merchantAuth = new AnetApi\MerchantAuthenticationType();
	$merchantAuth->setName('6F57afxFB');
	$merchantAuth->setTransactionKey('2M4C3T2nfA84cAHq');
	$refId = 'ref' . time();

	$subscription = new AnetApi\ARBSubscriptionType();
	$subscription->setName('EA subscription');

	$inverval = new AnetApi\PaymentScheduleType\IntervalAType();
	$inverval->setLength($data['interval_length']);
	$inverval->setUnit('days');

	$paymentSchedule = new AnetApi\PaymentScheduleType();
	$paymentSchedule->setInterval($inverval);
	$paymentSchedule->setStartDate(new DateTime('2020-08-30'));
	$paymentSchedule->setTotalOccurrences('12');

	$subscription->setPaymentSchedule($paymentSchedule);
	$subscription->setAmount(299.99);

	$creditCard = new AnetApi\CreditCardType();
	$creditCard->setCardNumber($data['credit_card']);
	$creditCard->setExpirationDate($data['expiration_date']);

	$payment = new AnetApi\PaymentType();
	$payment->setCreditCard($creditCard);
	$subscription->setPayment($payment);

	$order = new AnetApi\OrderType();
	$order->setInvoiceNumber('1234567');
	$order->setDescription('Invoice for EA subscription');
	$subscription->setOrder($order);

	$billTo = new AnetApi\NameAndAddressType();
	$billTo->setFirstName($data['first_name']);
	$billTo->setLastName($data['last_name']);

	$subscription->setBillTo($billTo);

	$request = new AnetApi\ARBCreateSubscriptionRequest();
	$request->setMerchantAuthentication($merchantAuth);
	$request->setRefId($refId);
	$request->setSubscription($subscription);

	$controller = new AnetController\ARBCreateSubscriptionController($request);
	$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

	if (($response != null) && ($response->getMessages()->getResultCode() == 'Ok')) {
		echo 'SUCCESS: Subscription ID : ' . $response->getSubscriptionId();
	} else {
		$errorMessages = $response->getMessages()->getMessage();
//		echo 'Response : ' . $errorMessages[0]->getCode() . ' ' . $errorMessages[0]->getText();
		throw new Exception($errorMessages[0]->getText());
	}

	return $response;
}

function ea_create_transaction() {
	$merchantAuth = new AnetApi\MerchantAuthenticationType();
	$merchantAuth->setName('6F57afxFB');
	$merchantAuth->setTransactionKey('2M4C3T2nfA84cAHq');
	$refId = 'ref' . time();

	$creditCard = new AnetApi\CreditCardType();
	$creditCard->setCardNumber('4111111111111111');
	$creditCard->setExpirationDate('2028-12');
	$paymentOne = new AnetApi\PaymentType();
	$paymentOne->setCreditCard($creditCard);

	$transactionRequestType = new AnetApi\TransactionRequestType();
	$transactionRequestType->setTransactionType('authCaptureTransaction');
	$transactionRequestType->setAmount(513.08);
	$transactionRequestType->setPayment($paymentOne);

	$request = new AnetApi\CreateTransactionRequest();
	$request->setMerchantAuthentication($merchantAuth);
	$request->setRefId($refId);
	$request->setTransactionRequest($transactionRequestType);

	$controller = new AnetController\CreateTransactionController($request);

	$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

	if ($response != null) {
		$tresponse = $response->getTransactionResponse();
		if (($tresponse != null) && ($tresponse->getResponseCode()=="1")) {
			echo 'Charge credit card auth code : ' . $tresponse->getAuthCode() . '\n';
			echo 'Charge credit card auth code : ' . $tresponse->getTransId() . '\n';
		} else {
			echo 'Charge credit card error : Invalid response \n';
		}
	} else {
		echo 'Charge credit card null response returned';
	}
}

