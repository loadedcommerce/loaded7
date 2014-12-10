<?php
    if (defined('ADDONS_CONNECTORS_ITEMBASE_STATUS') && (ADDONS_CONNECTORS_ITEMBASE_STATUS == 1)) {
        global $lC_Vqmod, $lC_Image, $lC_Language;

        require_once (DIR_FS_CATALOG . '/addons/Itembase/modules/ItembaseCommon.php');
        $itembase = new \ItembaseCommon(
            ADDONS_CONNECTORS_ITEMBASE_API,
            ADDONS_CONNECTORS_ITEMBASE_SECRET,
            ADDONS_CONNECTORS_ITEMBASE_DEBUG
        );
        try {
            require_once($lC_Vqmod->modCheck('includes/classes/order.php'));
            require_once($lC_Vqmod->modCheck('includes/classes/product.php'));
            require_once($lC_Vqmod->modCheck('includes/classes/category.php'));

            $orderId = $oID;
            $customerId = $lC_Customer->getID();
            $order = new lC_Order($orderId);
            $lang = substr($lC_Language->getCode(), 0, 2);
            $payloadProducts = array();
            // convert order object to array
            $payloadTransaction = json_decode(json_encode($order), true);
            foreach($order->products as $p) {
                $product = new lC_Product($p['id']);
                if ($product->getCategoryID()) {
                    $category = new lC_Category($product->getCategoryID());
                    if ($category->getTitle()) {
                        $categories[] = array(
                            'category_id' => $category->getID(),
                            'category_name' => $category->getTitle()
                        );
                    }

                }
                $payloadProducts[] = $product->getData();
                $products[] = array(
                    'category' => $categories,
                    'name' => $product->getTitle(),
                    'price_per_unit' => \ItembaseCommon::numberFormat($p['price']),
                    'currency' => $order->info['currency'],
                    'tax' => ((int) $p['tax'] > 0) ? \ItembaseCommon::numberFormat((\ItembaseCommon::numberFormat($p['price']) * (\ItembaseCommon::numberFormat($p['tax'])/100))) : 0,
                    'tax_rate' => \ItembaseCommon::numberFormat($p['tax'], 1),
                    'quantity' => $p['qty'],
                    'identifier' => array(
                        'id' => $product->getID(),
                        'ean' => '',
                        'isbn' => '',
                        'asin' => '',
                        'upc' => ''
                    ),
                    'description' => $product->getDescription(),
                    'picture_urls' => array(
                        HTTP_SERVER . DIR_WS_HTTP_CATALOG . $lC_Image->getAddress($product->getImage(), $lC_Image->getCode(DEFAULT_IMAGE_GROUP_ID))
                    ),
                    'url' => lc_href_link(FILENAME_PRODUCTS, $product->getKeyword(), null, null, null, true)
                );
            }

            $billingName = explode(' ', $order->billing['name']);
            $customerName = explode(' ', $order->customer['name']);
            $shippingCost = 0;
            foreach($order->totals as $totals) {
                if (strpos($totals['title'], $order->info['shipping_method'])) {
                    $shippingCost = substr($totals['text'], 1);
                }
            }

            $transactionData = array(
                'transaction' => array(
                    'id' => $orderId,
                    'created_at' => date(\DateTime::ISO8601, strtotime($order->info['date_purchased'])),
                    'currency' => $order->info['currency'],
                    'total_price' => \ItembaseCommon::numberFormat(substr($order->info['total'], 1)),
                    'products' => $products,
                    'shipping' => array(
                        'address' => array(
                            'street' => $order->delivery['street_address'],
                            'housenumber' => '',
                            'zip' => $order->delivery['postcode'],
                            'city' => $order->delivery['city'],
                            'country' => $order->delivery['country_iso2'],
                        ),
                        'price' => \ItembaseCommon::numberFormat($shippingCost),
                        'currency' => $order->info['currency'],
                        'method' => $order->info['shipping_method']
                    ),
                    'billing' => array(
                        'address' => array(
                            'firstname' => $billingName[0],
                            'lastname' => $billingName[1],
                            'street' => $order->billing['street_address'],
                            'housenumber' => '',
                            'zip' => $order->billing['postcode'],
                            'city' => $order->billing['city'],
                            'country' => $order->billing['country_iso2']
                        )
                    )
                ),
                'buyer' => array(
                    'email' => $order->customer['email_address'],
                    'firstname' => $customerName[0],
                    'lastname' => $customerName[1],
                    'customer_id' => $order->customer['id'],
                    'language' => $lang
                ),
                'seller' => array(
                    'type' => 'retailer',
                    'language' => $lang
                )
            );
            $transactionData['payload'] = $transactionData;
            $transactionData['payload']['payload_products'] = $payloadProducts;
            $transactionData['payload']['payload_transaction'] = $payloadTransaction;
            $itembaseData = $itembase->prepareData($transactionData);


        } catch (Exception $e) {
            $itembase->errorHandler($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
        }

        restore_error_handler();
    }
