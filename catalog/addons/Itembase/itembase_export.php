<?php

require('includes/application_top.php');
global $lC_Vqmod;

$table_prefix = DB_TABLE_PREFIX;

define('PRODUCTS',  $table_prefix . "products");
define('ORDERS',  $table_prefix . "orders");


if (defined('ADDONS_CONNECTORS_ITEMBASE_STATUS') && (ADDONS_CONNECTORS_ITEMBASE_STATUS == 1)) {
    require_once(DIR_FS_CATALOG . '/addons/Itembase/modules/lib/ItembaseExport.php');
    $export = new \ItembaseExport(
        ADDONS_CONNECTORS_ITEMBASE_API,
        ADDONS_CONNECTORS_ITEMBASE_SECRET,
        ADDONS_CONNECTORS_ITEMBASE_DEBUG,
        null,
        ADDONS_CONNECTORS_ITEMBASE_PRODUCT_EXPORT,
        ADDONS_CONNECTORS_ITEMBASE_TRANSACTION_EXPORT
    );


    switch ($export->getExportType()) {
        case 'orders':
            try {
                require_once($lC_Vqmod->modCheck('includes/classes/order.php'));
                require_once($lC_Vqmod->modCheck('includes/classes/product.php'));
                require_once($lC_Vqmod->modCheck('includes/classes/category.php'));

                $pdo = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);

                $where = '';
                if ($export->lastDate) {
                    $where .= " WHERE o.date_purchased>'" . date('Y-m-d H:i:s', $export->lastDate) . "' ORDER BY o.orders_id ASC ";
                }
                if (($export->recordOffset >= 0) && ($export->recordMax > 0)) {
                    $where .= ' LIMIT ' . $export->recordOffset . ', ' . $export->recordMax;
                }

                $stmt = $pdo->query('SELECT o.orders_id as id FROM ' . ORDERS . ' as o' . $where);
                $ordersArray = array();
                $orderData = array();
                $lang = substr($lC_Language->getCode(), 0, 2);
                while ($row = $stmt->fetch()) {
                    $products = array();
                    $categories = array();
                    $payloadProducts = array();

                    $orderId = $row['id'];
                    $order = new lC_Order($orderId);
                    $payloadTransaction = json_decode(json_encode($order), true);


                    foreach ($order->products as $p) {
                        $product = new lC_Product($p['id']);
                        $payloadProducts[] = $product->getData();
                        if ($product->getCategoryID()) {
                            $category = new lC_Category($product->getCategoryID());
                            if ($category->getTitle()) {
                                $categories[] = array(
                                    'category_id' => $category->getID(),
                                    'category_name' => $category->getTitle()
                                );
                            }
                        }


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
                    foreach ($order->totals as $totals) {
                        if (strpos($totals['title'], $order->info['shipping_method'])) {
                            $shippingCost = substr($totals['text'], 1);
                        }
                    }

                    $orderData = array(
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

                    $orderData['payload'] = $orderData;
                    $orderData['payload']['payload_products'] = $payloadProducts;
                    $orderData['payload']['payload_transaction'] = $payloadTransaction;
                    $ordersArray[] = $orderData;

                }

                $stmt->closeCursor();
                $export->execute($ordersArray);

                break;
            } catch (PDOException $e) {
                echo 'Database connection error.';
            }

        case 'products':
            try {
                global $lC_Language, $lC_Currencies;
                require_once($lC_Vqmod->modCheck('includes/classes/product.php'));
                require_once($lC_Vqmod->modCheck('includes/classes/category_tree.php'));

                $pdo = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);

                $where = '';
                if ($export->lastDate > 0)
                    $where .= " AND p.products_date_added>'" . date('Y-m-d H:i:s', $export->lastDate) . "' ORDER BY p.products_id ASC ";
                if (($export->recordOffset >= 0) && ($export->recordMax > 0)) {
                    $where .= ' LIMIT ' . $export->recordOffset . ', ' . $export->recordMax;
                }

                $stmt = $pdo->query('SELECT p.products_id as id FROM ' . PRODUCTS . ' as p WHERE p.products_status = 1' . $where);

                $productsArray = array();
                $defaultLang = substr($lC_Language->getCode(), 0, 2);

                while ($row = $stmt->fetch()) {
                    $categories = array();
                    $categoryName = array();
                    $desc = array();
                    $name = array();
                    $brand = '';
                    $tags = array();
                    foreach ($lC_Language->getAll() as $lng) {
                        $lC_Language->set($lng['code']);
                        $product = new lC_Product($row['id']);
                        $langCode = substr($lC_Language->getCode(), 0, 2);
                        //product description
                        if ($product->getDescription()) {
                            $desc[] = array(
                                'language' => $langCode,
                                'value' => $product->getDescription()
                            );
                        }
                        if ($product->getTitle()) {
                            $name[] = array(
                                'language' => $langCode,
                                'value' => $product->getTitle()
                            );
                        }
                        if ($product->getCategoryID()) {
                            $category = new lC_CategoryTree();
                            $cat = $category->getData($product->getCategoryID());
                            if ($cat['name']) {
                                $categoryName[] = array(
                                    'language' => $langCode,
                                    'value' => $cat['name']
                                );
                            }
                        }
                        if ($product->hasTags()) {
                            $tags[] = array(
                                'language' => $langCode,
                                'value' => $product->getTags()
                            );
                        }
                    }
                    if ($categoryName) {
                        $categories[] = array(
                            'id' => $cat['id'],
                            'name' => $categoryName
                        );
                    }

                    if ($product->hasManufacturer()) {
                        $brand = array(
                            'id' => $product->getManufacturerID(),
                            'name' => array(
                                'language' => $defaultLang,
                                'value' => $product->getManufacturer()
                            )
                        );
                    }

                    $images = array();
                    foreach ($product->getImages() as $image) {
                        $images[] = HTTP_SERVER . DIR_WS_HTTP_CATALOG . $lC_Image->getAddress($image['image'], $lC_Image->getCode(DEFAULT_IMAGE_GROUP_ID));
                    }

                    $attributes = array();
                    foreach ($product->getSimpleOptions() as $attribute) {
                        $attrValues = array();
                        foreach ($attribute as $attr) {
                            $key = $attr['group_title'];
                            $attrValues[] = $attr['value_title'];
                        }
                        $attributes[] = array(
                            'language' => $defaultLang,
                            'attribute' => array(
                                'key' => $key,
                                'values' => $attrValues
                            )
                        );

                    }

                    $variants = array();
                    $productData = $product->getData();
                    if ($product->hasSubProducts($product->getId())) {
                        $subProducts = $product->getSubProducts($product->getId());
                        foreach ($subProducts as $subProduct) {
                            $variants[] = array(
                                'id' => $subProduct['products_id']
                            );
                        }
                    }

                    $price = $product->getPriceInfo($product->getID(), 1, array());
                    $productsArray[] = array(
                        'parent_id' => ($productData['parent_id'] > 0) ? $productData['parent_id'] : '',
                        'identifier' => array(
                            'id' => $product->getId(),
                            'ean' => '',
                            'isbn' => '',
                            'asin' => '',
                            'upc' => ''
                        ),
                        'name' => $name,
                        'description' => $desc,
                        'categories' => $categories,
                        'variants' => $variants,
                        'tags' => $tags,
                        'attributes' => $attributes,
                        'brand' => $brand,
                        'price_per_unit' => $price['price'],
                        'currency' => $lC_Currencies->getCode(),
                        'tax' => ((int) $p['tax'] > 0) ? \ItembaseCommon::numberFormat((\ItembaseCommon::numberFormat($price['price']) * (\ItembaseCommon::numberFormat($price['tax'])/100))) : 0,
                        'tax_rate' => \ItembaseCommon::numberFormat($price['tax'], 1),
                        'url' => lc_href_link(FILENAME_PRODUCTS, $product->getKeyword(), null, null, null, true),
                        'picture_urls' => $images,
                        'created_at' => date(\DateTime::ISO8601, strtotime($product->getDateAdded())),
                        'updated_at' => ''
                    );
                }

                $stmt->closeCursor();
                $export->execute($productsArray);

                break;
            } catch (PDOException $e) {
                echo 'Database connection error.';
            }
    }
}
