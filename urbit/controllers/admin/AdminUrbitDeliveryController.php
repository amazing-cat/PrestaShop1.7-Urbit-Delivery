<?php

require_once(dirname(__FILE__) . '/../../classes/UrbitConfigurations.php');

class AdminUrbitDeliveryController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->required_database = true;
        $this->table = 'urbit_order_cart';
        $this->className = 'UrbitCart';

        $this->context = Context::getContext();
        $this->context->controller = $this;

        parent::__construct();

        $this->addRowAction('view');
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->fields_list = array(
            'id_urbit_order_cart' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ),
            'delivery_first_name' => array(
                'title' => $this->l('First Name'),
            ),
            'delivery_last_name' => array(
                'title' => $this->l('Last Name'),
            ),
            'delivery_street' => array(
                'title' => $this->l('Address'),
            ),
            'delivery_zip_code' => array(
                'title' => $this->l('Zip/Postal Code'),
                'align' => 'right',
            ),
            'delivery_city' => array(
                'title' => $this->l('City'),
            ),
            'delivery_time' => array(
                'title' => $this->l('Delivery Time'),
            ),
        );
    }

    public function renderForm()
    {
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Urb-it Delivery Address'),
                'icon' => 'icon-envelope-alt'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('First Name'),
                    'name' => 'delivery_first_name',
                    'required' => true,
                    'col' => '4',
                    'hint' => $this->l('Invalid characters:') . ' 0-9!&amp;lt;&amp;gt;,;?=+()@#"�{}_$%:',
                    'default_value' => '',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Last Name'),
                    'name' => 'delivery_last_name',
                    'required' => true,
                    'col' => '4',
                    'hint' => $this->l('Invalid characters:') . ' 0-9!&amp;lt;&amp;gt;,;?=+()@#"�{}_$%:',
                    'default_value' => '',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Address'),
                    'name' => 'delivery_street',
                    'col' => '6',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Zip/Postal Code'),
                    'name' => 'delivery_zip_code',
                    'col' => '2',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('City'),
                    'name' => 'delivery_city',
                    'col' => '4',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Contact phone'),
                    'name' => 'delivery_contact_phone',
                    'required' => true,
                    'col' => '4',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Is gift'),
                    'name' => 'delivery_is_gift',
                    'values' => array(
                        array(
                            'id' => 'delivery_is_gift_1',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'delivery_is_gift_0',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Gift receiver phone'),
                    'name' => 'delivery_gift_receiver_phone',
                    'col' => '4',
                ),
                array(
                    'type' => 'datetime',
                    'label' => $this->l('Delivery Time'),
                    'name' => 'delivery_time',
                    'col' => '4',
                    'required' => true,
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'is_send',
                    'value' => 'false',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            ),
        );

        return parent::renderForm();
    }

    /**
     * Gets delivery date timestamp
     * @param $deliveryDate string delivery date from API [format: Y-m-d\TH:i:sP]
     * @return int
     */
    protected function getDeliveryDateTimestamp($deliveryDate)
    {
        $date = DateTime::createFromFormat('Y-m-d\TH:i:sP', $deliveryDate, new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone('CET'));

        return (int)$date->getTimestamp();
    }

    protected function validateDelivery(&$errors)
    {
        $urbApi = new UbitAPIWrapper();
        $address_validation = $urbApi->validateDeliveryAddress(
            Tools::getValue('delivery_street'),
            Tools::getValue('delivery_zip_code'),
            Tools::getValue('delivery_city')
        );

        $is_address_valid = !$address_validation->hasError();

        if (!empty($address_validation->error_code)) {
            $errors[] = UrbitConfigurations::getErrorMessage($address_validation->error_code);
        }

        $date = DateTime::createFromFormat('Y-m-d H:i:s', ltrim(Tools::getValue('delivery_time')), new DateTimeZone('CET'));
        $chosenDeliveryDateTimestamp = $date->getTimestamp();

        $possible_delivery_hours = $urbApi->getDeliveryHours();
        $delivery_hours_items = !$possible_delivery_hours->hasError()
            ? $possible_delivery_hours->args->items
            : array();

        $is_time_valid = false;

        foreach ($delivery_hours_items as $delivery_item) {
            if ($delivery_item->closed == 1) {
                continue;
            }

            $firstDeliveryTimestamp = strtotime(
                '+5 minutes',
                $this->getDeliveryDateTimestamp($delivery_item->first_delivery)
            );
            $lastDeliveryTimestamp = strtotime(
                '-5 minutes',
                $this->getDeliveryDateTimestamp($delivery_item->last_delivery)
            );

            if ($chosenDeliveryDateTimestamp >= $firstDeliveryTimestamp &&
                $lastDeliveryTimestamp >= $chosenDeliveryDateTimestamp) {
                $is_time_valid = true;
                break;
            }
        }

        if (!$is_time_valid) {
            $errors[] = "The expected delivery date and time not available.";
        }

        return $is_address_valid && $is_time_valid;
    }

    protected function formatApiErrorMessage($response) {
        $args = $response->args;
        $errors = $args->errors->errors;
        if (!is_array($errors)) {
            $errors = array($errors);
        }

        $errors = array_map(function ($error) {
            $error_message = isset($error->message) ? $error->message : '';
            return isset($error->reason) && isset($error->reason->description)
                ? "$error_message ({$error->reason->description})"
                : $error_message;
        }, $errors);

        array_unshift($errors, $args->message);
        return implode("<br/>\n", $errors);
    }

    public function processSave()
    {
        if (Tools::getValue('submitFormAjax')) {
            $this->redirect_after = false;
        }

        $errors = [];
        if (!$this->validateDelivery($errors)) {
            $this->errors += $errors;
        }

        if (empty($this->errors)) {
            $cart = parent::processSave();

            // Send changes to the Urbit API.
            $urbit = Module::getInstanceByName('urbit');
            $response = $urbit->sendUpdateCheckout($cart->id_urbit_order_cart);
            if ($response->hasError()) {
                $this->errors[] = $this->formatApiErrorMessage($response);
                $this->redirect_after = false;
                $this->display = 'edit';
                return false;
            }

            return $cart;
        } else {
            $this->redirect_after = false;
            $this->display = 'edit';
            return false;
        }
    }
}
