# PrestaShop1.7-Urbit-Delivery

URB-IT SHIPPING PRESTASHOP

Facts ●	Version: 1.1.6.3 ●	extension key: PrestaShop-Urbit-Delivery ●	Addons page : Urb-it delivery service module ● extension on GitHub

##Prerequisites##

You have an account with Urb-it and have received the integration details for your Urb-it account :
●	X-API-Key ●	Bearer token

The module is installed on your PrestaShop shop.
##Step-by-step installation## STEP 1 : In your Back-office, go in the Modules and Services tab STEP 2 : Click on “Add a new module” Upload the zip of the module STEP 3 : Click on the install button to actually install the module

##Manual installation## If receiving the module in a compressed file.Unpack the files into the module folder of your PrestaShop project via FTP access on your server.

##Settings## Field	Explanation Urb-it module	This is the switch to enable or disable the shipping option Enable Urb-it Specific Time for several days With this dropdown menu you can choose the time range you want to propose in front office for Specific Time orders Send order failure report by email	Here you can select the email addresses that you want to use to receive failure reports when placing orders Now order auto-validation time	This field is a value, in minutes, creating an additional delay for « Now orders » and « first possible » Scheduled orders Order status trigger for confirmation	When an order is placed, an order status must be defined to trig the order confirmation on Urb-it’s end. After this event (can be automatic, changed manually in the back-office) the order will be created in the Urb-it system and Urbers will be able to claim the order to do the pickup and the delivery Currency	On this field you can set a price in three different currencies for the Urbit delivery service Urb-it API Key	This is your retailer key. The token and the API-Key will be provided by your local sales team.

Bearer JWT Token	This token allows Urb-it to identify your web shop when creating orders The token forand the API-Key will be provided by your local sales team. API URL	This is the API URL address where orders are sent Enable test mode	You need to enable test mode before you go against the API test environment

##Troubleshooting## If shipping option is not visible at all in the checkout. ●	Please make sure that you have enabled the carrier in the “carrier” tab. ●	Check if shipping module is enabled and has details entered in configuration. ●	If custom error message appears under the Urb-it shipping option in the checkout This means that the API has reported an error. ● Usually it is because of something wrong in the settings or maybe just that the postal code is outside the delivery area for Urb-it. ●	Remove the custom error message in admin and try again. You will now see a correct error message.

##PrestaShop uninstallation## STEP 1 : In your Back-office, go in the Modules and Services tab STEP 2 : Search for Urb-it STEP 3 : Click on Uninstall the module

##PrestaShop uninstallation## ●	Delete your urbit folder in the “modules” directory of your PrestaShop module project via FTP access on your server. Support If you have any issues with this extension, contact us at support@urbit.com Contribution Any contribution is highly appreciated. The best way to contribute code is to open a pull request on GitHub.

License GPL v3

Credits 2018 Urb-it
