# PAYONE Payment Plugin for Magento 1

![CI Status](https://img.shields.io/github/workflow/status/PAYONE-GmbH/magento-1/CI)
![Latest release version (semver)](https://img.shields.io/github/v/release/PAYONE-GmbH/magento-1)

The official extension for Magento 1 to enable payment for your
website via PAYONE service.

## Installation
### Prerequisites
You should have your PAYONE API credentials. If you still don't have
them, [contact PAYONE](https://payone.com).

Software requirements:
- At least PHP 5.6

### Install
It is highly recommended to install the latest release from Github (either using modman or manual installation). The Magento Marketplace is updated infrequently.

## Features
- A successful and much-used solution for all payment processes with already 50,000 downloads
- Seamless integration of Magento's OnePage Checkout
- Very wide range of functions, simple and comprehensible supported by detailed online help
- Central configuration and management options on the Magento Admin Panel
- Export function of the configuration in XML format to facilitate the technical support
- Optional multi-partial capture for partial deliveries
- Supports the multi-shipping extension
- Supports simplified PCI DSS conformity in accordance with SAQ A
- Payment methods can be automatically hidden depending on the amount of the order and the credit rating of the customer

## Documentation
You can our documentation here: https://docs.payone.com/integration/plugins/integration-guide-magento-1.

## Notes

### Related projects
Related projects can provide additional features and perks that can't be included in the main package for various reasons. These projects are not officially supported by BS PAYONE and can cause unexpected changes in module behavior. Support, if any, is given in the respective projects alone:

- [Pragmatic-Apps/magento1-payone-postponecapture](https://github.com/Pragmatic-Apps/magento1-payone-postponecapture)
  
  Provides automatic capture when an order is shipped for Payolution payment methods.


- [Pragmatic-Apps/magento1-payone-restrictproducts](https://github.com/Pragmatic-Apps/magento1-payone-restrictproducts)

  Extension that allows to automatically disable selected methods when certain products are in the shopping cart. This is necessary to comply with requirements of some payment provides (e.g. when it is not allowed to sell coupons).

## Contact
PAYONE GmbH<br>
Lyoner Straße 15<br>
60528 Frankfurt am Main, Germany<br>
Phone +49 431 25968-400<br>
tech.support@payone.com<br>
