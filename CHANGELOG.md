# Changelog

**The changelog is continued on the Github releases page**

## 5.3.0

New Features

Add Apple Pay
Maintenance

Rename Payolution to Unzer

## 5.2.0

New Features
 
* re-introduced credit card holder field for better acceptance when in scope for 3D Secure 2.0. 
We had previously disabled it to streamline the amount of fields a customer had to fill by default. With PSD2 and 3D Secure 2.0 in effect, we saw that using the cardholder field can benefit the ratio of frictionless payments vs. leaving it out.
 
Bugfix(es)
 
* call Ratepay DFP endpoint only when selected
* better checkout performance for checkouts with many addresses
* fixed item qtys doubled when using multi tabs and redirect payments (thx @igloominusx)
 
Maintenance
 
* rebranded "RatePay" to "Ratepay"
* tested with Magento 1.9.4.4

## 5.1.0

New Feature(s)

* Removed uses of jQuery where not strictly neccessary

Bugfixes

* Corrected voucher VAT for Klarna start_session call
* Corrected PayDirekt shipping vat

Maintenance

* Updated Ratepay legal texts
* Recognize OpenMage version and incorporate into request diagnostic data
* Tested with Magento 1.9.4.4

## 5.0.0

New Feature(s)
 
* new klarna implementation
* New database fields for RatePay orders
* IP address in API log is now anonymized

Bugfixes

* Amazon Pay is not displayed in backend to avoid template error (fixes #207)

Maintenance
 
* remove DISCOVER
* Improved RatePay Snipped ID handling
* Transmit customer ID to Ratepay for returning customers
* Decoupled Payolution and RatePay JS files
* Fixed position of Paydirekt logo
* Fixed broken CSV file (thanks @sprankhub)
* Fixed incomplete Modman file (thanks @Schrank)
 
Warning(s)
 
* klarna legacy will be shut down in the end of Sep 2020
* Additionally, there is a known incompatibility with the Klarna Payments Plugin für M1 which will be fixed in a subsequent release


## 4.1.5
**Features**

* Added RatePay Direct Debit
* Order reversal is now possible even if order can't be reversed at Payone API

**Bugfixes**

* Amazon's widgets.js is now loaded correctly
* Store ID is now fixed from order table if not in tx-status table (thanks @alphanyx)
* Shopgate orders are now recognized correctly (#181, thanks @sprankhub)
* CVC code can now be hidden again
* Updated iDeal bank groups
* Fixed some cases of "backwards browser button" orders being handled the wrong way
* Fixed several typos (thanks @Ersian)

## 4.1.4
**Features**

* RatePay B2B Invoice added
* In case of guaratee rejection, Payone Safe Invoice is not displayed again to the customer
* Enforce Amazon Pay redirect mode for in-app browsers (#161, thanks @gndk)
* Sending the overcapture flag for PayDirekt now possible
* RatePay backend orders are now possible
* Automatically recognize credit card type after 6 digits of PAN

**Bugfixes**
* Fixed wrong license identifier in composer.json
* Fixed missing modman directive (#176, thanks @tim-bezhashvyly)
* Fixed "undefined index" problems (#152, thanks @norgeindian; #168, thanks @Schrank)
* Headers are now set correctly when using FastCGI
* Fixed some bugs introduced with Mage 1.9.3.x (#165)
* Re-activated the conversion of reference parameters for PayDirekt to honor disallowed chars
* Removed HTML from backend labels for 1.9.3.8 compat

## 4.1.2
**Features**

* Handle Ratepay unauthorized payment response by disabling the payment method for the customer
* Allow to send registration number instead of birthdate for B2B cases

## 4.1.1
**Feature**

* Completed Amazon Pay background processes
* RatePay orders can now be properly reversed


## 4.1.0
**Feature**

* New payment methods
    * Invoice with payment gurantee
    * Amazon Pay
* Allow quote as parameter in Payone_Core_Model_Payment_Method_Abstract
* Add functionality for recurring payment
* Improve the handling of external payment providers

**Bug**
* Set correct model for default mapping
* Correctly handle payment fee calculation
* Fixed default payment titles for giropay & idl
* Payone_Core_Model_Mapper_ApiRequest_Payment_Authorize_Abstract getCustomerIp() will no return the last ip like described in the comment
* Fixed php notice because array key does not exist
* Payone_Core_Model_Observer_Sales_Order paymentPlaceEnd() will now validate if the user exists
* Fixed url-encoding
