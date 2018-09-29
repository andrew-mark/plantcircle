=== Klarna Payments for WooCommerce ===
Contributors: klarna, krokedil, automattic
Tags: woocommerce, klarna, ecommerce, e-commerce
Donate link: https://klarna.com
Requires at least: 4.0
Tested up to: 4.9.8
Requires PHP: 5.6
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== DESCRIPTION ==

*Choose the payment that you want, Pay Now, Pay Later or Slice It. No credit card numbers, no passwords, no worries.*

Choose the Klarna features you want – and only the features you want – to reduce purchase stress and improve your user experience. We have several financing and direct payment options to meet your needs, and they’re all easily integrated into your existing buying journey.

=== Pay Now (direct payments) ===
Customers who want to pay in full at checkout can do it quickly and securely with a credit/debit card.Friction-free direct purchases while maximising the value for your business thanks to guaranteed payments. If they have a Klarna account they can save their details and enjoy one-click purchases from then on.

===  Pay later (invoice) ===
Try it first, pay it later. Delayed payments for customers who like low friction purchases and to pay after delivery.

=== Slice it (installments) ===
Installment, revolving and other flexible financing plans let customers pay when they can and when they want.

=== How to Get Started ===
* [Sign up for Klarna](https://www.klarna.com/international/business/woocommerce/).
* [Install the plugin](https://wordpress.org/plugins/klarna-payments-for-woocommerce/) on your site. During this process you will be asked to download [Klarna Order Management](https://wordpress.org/plugins/klarna-order-management-for-woocommerce/) so you can handle orders in Klarna directly from WooCommerce.
* Get your store approved by Klarna, and start selling.

=== What's the difference between Klarna Checkout and Klarna Payments? ===
Klarna as your single payment provider keeps everything under one roof. You’ll have one agreement, one point of contact, one settlement file, one payout with __Klarna Checkout__. It only takes a single integration to deliver the full Klarna hosted checkout experience through a widget placed on your site.

__Klarna Payments__ removes the headaches of payments, for both consumers and merchants. Complement your checkout with a Klarna hosted widget located in your existing checkout which offers payment options for customers with a smooth user experience.

== Installation ==
1. Upload plugin folder to to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Go WooCommerce Settings –> Payment Gateways and configure your Klarna Payments settings.
4. Read more about the configuration process in the [plugin documentation](https://docs.woocommerce.com/document/klarna-payments/).

== Frequently Asked Questions ==
= Which countries does this payment gateway support? =
Klarna Payments works for merchants in Sweden, Denmark, Finland, Norway, Germany, Austria, the Netherlands, UK and United States.

= Where can I find Klarna Payments for WooCommerce documentation? =
For help setting up and configuring Klarna Payments for WooCommerce please refer to our [documentation](https://docs.woocommerce.com/document/klarna-payments/).

= Are there any specific requirements? =
* WooCommerce 3.0 or newer is required.
* PHP 5.6 or higher is required.
* A SSL Certificate is required.

== Changelog ==
= 2018.08.17  	- version 1.5.4 =
* Tweak			- Logging improvements.
* Fix			- Version number in enqueuing of admin css file.

= 2018.08.17  	- version 1.5.3 =
* Tweak			- Added filter kp_wc_api_request_args to be able to override order data sent to Klarna.
* Tweak			- Added filter wc_klarna_payments_available_payment_categories to be able to override wich payment methods that should be available.
* Tweak			- Logging improvements in klarna_payments_session_ajax_update function if request fails.
* Tweak			- Added button for hiding Klarna banner in WP admin. Stays hidden for 6 days and then reappears again (if plugin still is in test mode).
* Fix			- KP payment method not available on Order pay page (to avoid compatibility issues with Realex payment plugin).

= 2018.07.23  	- version 1.5.2 =
* Tweak			- Add max width to payment method icons.
* Enhancement	- Added Klarna LEAP functionality (URL's for new customer signup & onboarding).
* Fix			- Added fallback image for 404 on payment gateway icon URL.

= 2018.06.21  	- version 1.5.1 =
* Tweak			- Payment gateway icons now fetched from Klarnas CDN.

= 2018.06.08  	- version 1.5.0 =
* Feature		- Switches to Klarnas new /payments endpoint. Displays each Klarna payment method as its own payment option in checkout.
* Feature		- Added support for wp_add_privacy_policy_content (for GDPR compliance). More info: https://core.trac.wordpress.org/attachment/ticket/43473/PRIVACY-POLICY-CONTENT-HOOK.md.
* Tweak			- Switches to $product->get_name() for Klara order line name.
* Tweak			- Adds Klarna dashboard banners and settings page sidebar.
* Tweak			- Added PHP version and Krokedil to user agent.
* Tweak			- Only log messages if enabled in settings.
* Tweak			- Added logging of error response in Klarna create & update session.
* Tweak			- Added function to hide iframes when not needed.
* Tweak			- Added action klarna_payments_template to template. Action used in plugin to maybe create or update session.
* Fix			- Changes the check in set_klarna_country(). No longer uses is_checkout(). Just check for customer country if WC_Customer exist.

= 2018.01.29  	- version 1.4.2 =
* Fix           - Cleans up translation strings.
* Enhancement   - process_payment method refactoring.    

= 2018.01.25  	- version 1.4.1 =
* Fix           - Fixes WC 3.3 notices.
* Tweak         - Stores Klarna order transaction ID as soon as possible. 
* Tweak         - Adds "can't edit order" admin note.    

= 1.0 =
* Initial release.
