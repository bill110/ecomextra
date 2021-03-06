<?php
/** 
* @(#) $Id: languages/english.php $
* 
* language definitions
*
* @package languages 
*
* @copyright 2013 ecomextra
* @copyright Portions Copyright 2007 osCommerce
* @license   Released under the GNU General Public License. 
*/

// look in your $PATH_LOCALE/locale directory for available locales
// or type locale -a on the server.
// Examples:
// on RedHat try 'en_US'
// on FreeBSD try 'en_US.ISO_8859-1'
// on Windows try 'en', or 'English'
@setlocale(LC_TIME, 'en_US.ISO_8859-1');

define('LNG_DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
define('LNG_DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('LNG_DATE_FORMAT', 'm/d/Y'); // this is used for date()
define('LNG_DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
define('LNG_JQUERY_DATEPICKER_I18N_CODE', ''); // leave empty for en_US; see http://jqueryui.com/demos/datepicker/#localization
define('LNG_JQUERY_DATEPICKER_FORMAT', 'mm/dd/yy'); // see http://docs.jquery.com/UI/Datepicker/formatDate

////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function ecx_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
  }
}

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LNG_LANGUAGE_CURRENCY', 'USD');

// Global entries for the <html> tag
define('LNG_HTML_PARAMS', 'dir="ltr" lang="en"');

// charset for web pages and emails
define('LNG_CHARSET', 'utf-8');

// page title
define('LNG_TITLE', STORE_NAME);

// header text in includes/header.php
define('LNG_HEADER_TITLE_CREATE_ACCOUNT', 'Create an Account');
define('LNG_HEADER_TITLE_MY_ACCOUNT', 'My Account');
define('LNG_HEADER_TITLE_CART_CONTENTS', 'Cart Contents');
define('LNG_HEADER_TITLE_CHECKOUT', 'Checkout');
define('LNG_HEADER_TITLE_TOP', 'Top');
define('LNG_HEADER_TITLE_CATALOG', 'Catalog');
define('LNG_HEADER_TITLE_LOGOFF', 'Log Off');
define('LNG_HEADER_TITLE_LOGIN', 'Log In');

// footer text in includes/footer.php
define('LNG_FOOTER_TEXT_REQUESTS_SINCE', 'requests since');

// text for gender
define('LNG_MALE', 'Male');
define('LNG_FEMALE', 'Female');
define('LNG_MALE_ADDRESS', 'Mr.');
define('LNG_FEMALE_ADDRESS', 'Ms.');

// text for date of birth example
define('LNG_DOB_FORMAT_STRING', 'mm/dd/yyyy');

// checkout procedure text
define('LNG_CHECKOUT_BAR_DELIVERY', 'Delivery Information');
define('LNG_CHECKOUT_BAR_PAYMENT', 'Payment Information');
define('LNG_CHECKOUT_BAR_CONFIRMATION', 'Confirmation');
define('LNG_CHECKOUT_BAR_FINISHED', 'Finished!');

// pull down default text
define('LNG_PULL_DOWN_DEFAULT', 'Please Select');
define('LNG_TYPE_BELOW', 'Type Below');

// javascript messages
define('LNG_JS_ERROR', 'Errors have occured during the process of your form.\n\nPlease make the following corrections:\n\n');

define('LNG_JS_REVIEW_TEXT', '* The \'Review Text\' must have at least ' . REVIEW_TEXT_MIN_LENGTH . ' characters.\n');
define('LNG_JS_REVIEW_RATING', '* You must rate the product for your review.\n');

define('LNG_JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Please select a payment method for your order.\n');

define('LNG_JS_ERROR_SUBMITTED', 'This form has already been submitted. Please press Ok and wait for this process to be completed.');

define('LNG_ERROR_NO_PAYMENT_MODULE_SELECTED', 'Please select a payment method for your order.');

define('LNG_CATEGORY_COMPANY', 'Company Details');
define('LNG_CATEGORY_PERSONAL', 'Your Personal Details');
define('LNG_CATEGORY_ADDRESS', 'Your Address');
define('LNG_CATEGORY_CONTACT', 'Your Contact Information');
define('LNG_CATEGORY_OPTIONS', 'Options');
define('LNG_CATEGORY_PASSWORD', 'Your Password');

define('LNG_ENTRY_COMPANY', 'Company Name:');
define('LNG_ENTRY_COMPANY_TEXT', '');
define('LNG_ENTRY_GENDER', 'Gender:');
define('LNG_ENTRY_GENDER_ERROR', 'Please select your Gender.');
define('LNG_ENTRY_GENDER_TEXT', '*');
define('LNG_ENTRY_FIRST_NAME', 'First Name:');
define('LNG_ENTRY_FIRST_NAME_ERROR', 'Your First Name must contain a minimum of ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.');
define('LNG_ENTRY_FIRST_NAME_TEXT', '*');
define('LNG_ENTRY_LAST_NAME', 'Last Name:');
define('LNG_ENTRY_LAST_NAME_ERROR', 'Your Last Name must contain a minimum of ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.');
define('LNG_ENTRY_LAST_NAME_TEXT', '*');
define('LNG_ENTRY_DATE_OF_BIRTH', 'Date of Birth:');
define('LNG_ENTRY_DATE_OF_BIRTH_ERROR', 'Your Date of Birth must be in this format: MM/DD/YYYY (eg 05/21/1970)');
define('LNG_ENTRY_DATE_OF_BIRTH_TEXT', '* (eg. 05/21/1970)');
define('LNG_ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');
define('LNG_ENTRY_EMAIL_ADDRESS_ERROR', 'Your E-Mail Address must contain a minimum of ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.');
define('LNG_ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Your E-Mail Address does not appear to be valid - please make any necessary corrections.');
define('LNG_ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Your E-Mail Address already exists in our records - please log in with the e-mail address or create an account with a different address.');
define('LNG_ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('LNG_ENTRY_STREET_ADDRESS', 'Street Address:');
define('LNG_ENTRY_STREET_ADDRESS_ERROR', 'Your Street Address must contain a minimum of ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.');
define('LNG_ENTRY_STREET_ADDRESS_TEXT', '*');
define('LNG_ENTRY_SUBURB', 'Suburb:');
define('LNG_ENTRY_SUBURB_TEXT', '');
define('LNG_ENTRY_POST_CODE', 'Post Code:');
define('LNG_ENTRY_POST_CODE_ERROR', 'Your Post Code must contain a minimum of ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.');
define('LNG_ENTRY_POST_CODE_TEXT', '*');
define('LNG_ENTRY_CITY', 'City:');
define('LNG_ENTRY_CITY_ERROR', 'Your City must contain a minimum of ' . ENTRY_CITY_MIN_LENGTH . ' characters.');
define('LNG_ENTRY_CITY_TEXT', '*');
define('LNG_ENTRY_STATE', 'State/Province:');
define('LNG_ENTRY_STATE_ERROR', 'Your State must contain a minimum of ' . ENTRY_STATE_MIN_LENGTH . ' characters.');
define('LNG_ENTRY_STATE_ERROR_SELECT', 'Please select a state from the States pull down menu.');
define('LNG_ENTRY_STATE_TEXT', '*');
define('LNG_ENTRY_COUNTRY', 'Country:');
define('LNG_ENTRY_COUNTRY_ERROR', 'You must select a country from the Countries pull down menu.');
define('LNG_ENTRY_COUNTRY_TEXT', '*');
define('LNG_ENTRY_TELEPHONE_NUMBER', 'Telephone Number:');
define('LNG_ENTRY_TELEPHONE_NUMBER_ERROR', 'Your Telephone Number must contain a minimum of ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.');
define('LNG_ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('LNG_ENTRY_FAX_NUMBER', 'Fax Number:');
define('LNG_ENTRY_FAX_NUMBER_TEXT', '');
define('LNG_ENTRY_NEWSLETTER', 'Newsletter:');
define('LNG_ENTRY_NEWSLETTER_TEXT', '');
define('LNG_ENTRY_NEWSLETTER_YES', 'Subscribed');
define('LNG_ENTRY_NEWSLETTER_NO', 'Unsubscribed');
define('LNG_ENTRY_PASSWORD', 'Password:');
define('LNG_ENTRY_PASSWORD_ERROR', 'Your Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('LNG_ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'The Password Confirmation must match your Password.');
define('LNG_ENTRY_PASSWORD_TEXT', '*');
define('LNG_ENTRY_PASSWORD_CONFIRMATION', 'Password Confirmation:');
define('LNG_ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('LNG_ENTRY_PASSWORD_CURRENT', 'Current Password:');
define('LNG_ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('LNG_ENTRY_PASSWORD_CURRENT_ERROR', 'Your Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('LNG_ENTRY_PASSWORD_NEW', 'New Password:');
define('LNG_ENTRY_PASSWORD_NEW_TEXT', '*');
define('LNG_ENTRY_PASSWORD_NEW_ERROR', 'Your new Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('LNG_ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'The Password Confirmation must match your new Password.');
define('LNG_PASSWORD_HIDDEN', '--HIDDEN--');

define('LNG_FORM_REQUIRED_INFORMATION', '* Required information');

// constants for use in ecx_prev_next_display function
define('LNG_TEXT_RESULT_PAGE', 'Result Pages:');
define('LNG_TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> products)');
define('LNG_TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> orders)');
define('LNG_TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> reviews)');
define('LNG_TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> new products)');
define('LNG_TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> specials)');

define('LNG_PREVNEXT_TITLE_FIRST_PAGE', 'First Page');
define('LNG_PREVNEXT_TITLE_PREVIOUS_PAGE', 'Previous Page');
define('LNG_PREVNEXT_TITLE_NEXT_PAGE', 'Next Page');
define('LNG_PREVNEXT_TITLE_LAST_PAGE', 'Last Page');
define('LNG_PREVNEXT_TITLE_PAGE_NO', 'Page %d');
define('LNG_PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Previous Set of %d Pages');
define('LNG_PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Next Set of %d Pages');
define('LNG_PREVNEXT_BUTTON_FIRST', '&lt;&lt;FIRST');
define('LNG_PREVNEXT_BUTTON_PREV', '[&lt;&lt;&nbsp;Prev]');
define('LNG_PREVNEXT_BUTTON_NEXT', '[Next&nbsp;&gt;&gt;]');
define('LNG_PREVNEXT_BUTTON_LAST', 'LAST&gt;&gt;');

define('LNG_IMAGE_BUTTON_ADD_ADDRESS', 'Add Address');
define('LNG_IMAGE_BUTTON_ADDRESS_BOOK', 'Address Book');
define('LNG_IMAGE_BUTTON_BACK', 'Back');
define('LNG_IMAGE_BUTTON_BUY_NOW', 'Buy Now');
define('LNG_IMAGE_BUTTON_CHANGE_ADDRESS', 'Change Address');
define('LNG_IMAGE_BUTTON_CHECKOUT', 'Checkout');
define('LNG_IMAGE_BUTTON_CONFIRM_ORDER', 'Confirm Order');
define('LNG_IMAGE_BUTTON_CONTINUE', 'Continue');
define('LNG_IMAGE_BUTTON_CONTINUE_SHOPPING', 'Continue Shopping');
define('LNG_IMAGE_BUTTON_DELETE', 'Delete');
define('LNG_IMAGE_BUTTON_EDIT_ACCOUNT', 'Edit Account');
define('LNG_IMAGE_BUTTON_HISTORY', 'Order History');
define('LNG_IMAGE_BUTTON_LOGIN', 'Sign In');
define('LNG_IMAGE_BUTTON_IN_CART', 'Add to Cart');
define('LNG_IMAGE_BUTTON_NOTIFICATIONS', 'Notifications');
define('LNG_IMAGE_BUTTON_QUICK_FIND', 'Quick Find');
define('LNG_IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Remove Notifications');
define('LNG_IMAGE_BUTTON_REVIEWS', 'Reviews');
define('LNG_IMAGE_BUTTON_SEARCH', 'Search');
define('LNG_IMAGE_BUTTON_SHIPPING_OPTIONS', 'Shipping Options');
define('LNG_IMAGE_BUTTON_TELL_A_FRIEND', 'Tell a Friend');
define('LNG_IMAGE_BUTTON_UPDATE', 'Update');
define('LNG_IMAGE_BUTTON_UPDATE_CART', 'Update Cart');
define('LNG_IMAGE_BUTTON_WRITE_REVIEW', 'Write Review');

define('LNG_SMALL_IMAGE_BUTTON_DELETE', 'Delete');
define('LNG_SMALL_IMAGE_BUTTON_EDIT', 'Edit');
define('LNG_SMALL_IMAGE_BUTTON_VIEW', 'View');

define('LNG_ICON_ARROW_RIGHT', 'more');
define('LNG_ICON_CART', 'In Cart');
define('LNG_ICON_ERROR', 'Error');
define('LNG_ICON_SUCCESS', 'Success');
define('LNG_ICON_WARNING', 'Warning');

define('LNG_TEXT_GREETING_PERSONAL', 'Welcome back <span class="greetUser">%s!</span> Would you like to see which <a href="%s"><u>new products</u></a> are available to purchase?');
define('LNG_TEXT_GREETING_PERSONAL_RELOGON', '<small>If you are not %s, please <a href="%s"><u>log yourself in</u></a> with your account information.</small>');
define('LNG_TEXT_GREETING_GUEST', 'Welcome <span class="greetUser">Guest!</span> Would you like to <a href="%s"><u>log yourself in</u></a>? Or would you prefer to <a href="%s"><u>create an account</u></a>?');

define('LNG_TEXT_SORT_PRODUCTS', 'Sort products ');
define('LNG_TEXT_DESCENDINGLY', 'descendingly');
define('LNG_TEXT_ASCENDINGLY', 'ascendingly');
define('LNG_TEXT_BY', ' by ');

define('LNG_TEXT_REVIEW_BY', 'by %s');
define('LNG_TEXT_REVIEW_WORD_COUNT', '%s words');
define('LNG_TEXT_REVIEW_RATING', 'Rating: %s [%s]');
define('LNG_TEXT_REVIEW_DATE_ADDED', 'Date Added: %s');
define('LNG_TEXT_NO_REVIEWS', 'There are currently no product reviews.');

define('LNG_TEXT_NO_NEW_PRODUCTS', 'There are currently no products.');

define('LNG_TEXT_UNKNOWN_TAX_RATE', 'Unknown tax rate');

define('LNG_TEXT_REQUIRED', '<span class="errorText">Required</span>');

define('LNG_ERROR_ECX_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><strong><small>ECX ERROR:</small> Cannot send the email through the specified SMTP server. Please check your php.ini setting and correct the SMTP server if necessary.</strong></font>');

define('LNG_TEXT_CCVAL_ERROR_INVALID_DATE', 'The expiry date entered for the credit card is invalid. Please check the date and try again.');
define('LNG_TEXT_CCVAL_ERROR_INVALID_NUMBER', 'The credit card number entered is invalid. Please check the number and try again.');
define('LNG_TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'The first four digits of the number entered are: %s. If that number is correct, we do not accept that type of credit card. If it is wrong, please try again.');

define('LNG_FOOTER_TEXT_BODY', 'Copyright &copy; ' . date('Y') . ' <a href="' . ecx_href_link(FILENAME_DEFAULT) . '">' . STORE_NAME . '</a><br />Powered by <a href="http://www.ecomextra.com" target="_blank">Ecomextra</a>');
?>
