<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_1_7_3($object)
{
    return $object->installTab('AdminParentOrders', 'AdminUrbitDelivery', 'Urb-it deliveries');
}
