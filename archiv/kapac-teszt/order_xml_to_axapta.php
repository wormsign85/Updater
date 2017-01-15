<?php

require_once 'config.php';

function log_unas($filename, $message) {
    file_put_contents($filename, date('Y-m-d H:i:s') . ' ' . $message . "\n", FILE_APPEND);
}

//Unas API kapcsolat
function get_unas_client() {
    $soap_server = "https://api.unas.eu/shop/?wsdl";
    ini_set("soap.wsdl_cache_enabled", "0");
///////////////////////////////////////////////
// auth
///////////////////////////////////////////////
// connect
    $client = new SoapClient($soap_server);
    return $client;
}

$client = get_unas_client();

///////////////////////////////////////////////
// init
function get_orders_unas($client, $systemConfig, $soapConfig, $webshopName) {
    $logfilename = $systemConfig['log_dir'] . '/' . $webshopName . '_' . $soapConfig['ShopId'];
    try {
        $auth = array(
            'Username' => $soapConfig['Username'],
            'PasswordCrypt' => $soapConfig['PasswordCrypt'],
            'ShopId' => $soapConfig['ShopId'],
            'AuthCode' => $soapConfig['AuthCode']
        );
//getOrder
        $params = array(
            'InvoiceStatus' => 1,
            'InvoiceAutoSet' => 1,
            'DateStart' => "2014.11.01",
            'DateEnd' => date('Y.m.d')
        );
        $response = $client->getOrder($auth, $params);
    } catch (SoapFault $error) {
      log_unas($logfilename, 'SoapError: ' . $error->getMessage());
        echo "<strong>getOrder Error:</strong><br /> ";
        echo "<pre>" . print_r($error, true) . "</pre>";
    }

    $orders = new SimpleXMLElement($response);

    /* $customerorders = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><Document_Order/>'); */

    foreach ($orders->Order as $sorszam => $order) {
        $customerorders = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><Document_Order/>');
        $order_id = $order->Key;
        $date = strtr($order->Date, array('.' => '-'));
        $customer_email = $order->Customer->Email;
        $customer_username = $order->Customer->Username;
        $contact_name = $order->Customer->Contact->Name;
        $contact_phone = $order->Customer->Contact->Phone;
        $contact_mobile = $order->Customer->Contact->Mobile;
        $contact_lang = $order->Customer->Contact->Lang;
        $invoice_name = $order->Customer->Addresses->Invoice->Name;
        $invoice_zip = $order->Customer->Addresses->Invoice->ZIP;
        $invoice_city = $order->Customer->Addresses->Invoice->City;
        $invoice_street = $order->Customer->Addresses->Invoice->Street;
        $invoice_county = $order->Customer->Addresses->Invoice->County;
        $invoice_country = $order->Customer->Addresses->Invoice->Country;
        $invoice_countrycode = $order->Customer->Addresses->Invoice->Countrycode;
        $invoice_taxnumber = $order->Customer->Addresses->Invoice->Taxnumber;
        $address_shipping_name = $order->Customer->Addresses->Shipping->Name;
        $shipping_zip = $order->Customer->Addresses->Shipping->ZIP;
        $shipping_city = $order->Customer->Addresses->Shipping->City;
        $shipping_street = $order->Customer->Addresses->Shipping->Street;
        $shipping_county = $order->Customer->Addresses->Shipping->County;
        $shipping_country = $order->Customer->Addresses->Shipping->Country;
        $shipping_countrycode = $order->Customer->Addresses->Shipping->Countrycode;
        $currency = $order->Currency;
        $status = $order->Status;
        $payment_id = $order->Payment->Id;
        $payment_name = $order->Payment->Name;
        $payment_type = $order->Payment->Type;
        $payment_status = $order->Payment->Status;
        $shipping_id = $order->Shipping->Id;
        $shipping_name = $order->Shipping->Name;
        $invoice_status = $order->Invoice->Status;
        $invoice_statustext = $order->Invoice->Statustext;
        $invoice_number = $order->Invoice->Number;
//$params = $order->Customer->Params->Param->Value; Ha a vásárló paraméterekből vesszük a vevőkódot
        $comment = $order->Customer->Comment;


        $order_id1 = $order->Key;
        $linenumber = 0;
        $order_header = $customerorders->addChild('Order_Header');
        $order_number = $order_header->addChild('OrderNumber', $order_id);
        $timestamp = strtotime($date);
        $order_header->addChild('OrderDate', date('Y-m-d', $timestamp));
        $order_header->addChild('OrderTime', date('H:i:s', $timestamp) . '.000000');
        $order_header->addChild('OrderCurrency', $currency);
        $order_header->addChild('OrderSource', 'WEB');
        $order_header->addChild('PromotionReference');
        $order_header->addChild('DocumentFunctionCode', 'O');
        $order_header->addChild('Remarks');
        $order_transport = $customerorders->addChild('Order_Transport');
        $order_transport->addChild('MethodOfPayment');
        $order_parties = $customerorders->addChild('Order_Parties');
        $buyer = $order_parties->addChild('Buyer');
        $buyer->addChild('ILN', $comment);
        $seller = $order_parties->addChild('Seller');
        $seller->addChild('ILN', '5990041074007');
        $DeliveryPoint = $order_parties->addChild('DeliveryPoint');
        $DeliveryPoint->addChild('ILN', $comment);
        $ordered_by = $order_parties->addChild('OrderedBy');
        $ordered_by->addChild('Username');
        $carrier = $order_parties->addChild('Carrier');
        $carrier->addChild('Method');
        $carrier->addChild('ILN');
        $order_lines = $customerorders->addChild('Order_Lines');
        foreach ($order->Items->Item as $itemKey => $item) {
            $linenumber = $linenumber + 1;
            $Item_Id = $item->Id;
            $Item_Sku = $item->Sku;
            $Item_Name = $item->Name;
            $Item_Unit = $item->Unit;
            $Item_Quantity = $item->Quantity;
            $Item_PriceNet = $item->PriceNet;
            $Item_PriceGross = $item->PriceGross;
            $Item_Vat = $item->Vat;
            $line = $order_lines->addChild('Line');
            $line_item = $line->addChild('Line_Item');
            $line_item->addChild('LineNumber', $linenumber);
            $line_item->addChild('EAN', $Item_Sku);
            $line_item->addChild('OrderedQuantity', $Item_Quantity);
            $line_item->addChild('OrderedUnitNetPrice', $Item_PriceNet);
            $line_item->addChild('ReMarks');
        }
        $Order_Summary = $customerorders->addChild('Order_Summary');
        $Order_Summary->addChild('Total_Lines', $linenumber);
        $Order_Summary->addChild('TotalOrderedAmount', $Item_PriceNet * $Item_Quantity);
        $dom = dom_import_simplexml($customerorders)->ownerDocument;
        $dom->formatOutput = true;
        file_put_contents($systemConfig['axapta_xml_dir'] . '/akku' . '_' . 'HU' . '_' . $order_id . '.xml', $dom->saveXML());
    }
}

foreach ($config['unas_soap'] as $webshopName => $soapConfig) {
    get_orders_unas($client, $config['system'], $soapConfig, $webshopName);
}
