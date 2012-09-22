<?php
// #########################################################################
// Application Information
// #########################################################################
define('APP_VERSION', '1.0');
define('CAKE_VERSION', '2.2.1');
define('REPOSITORY_TYPE', 'git');

// #########################################################################
// Regular Expressions
// #########################################################################
define('VALID_TEMPERATURE', '/^\d{1,3}\.\d{1,2}$|^\d{1,3}$/');	// Valid temperature
define('VALID_GENDER', '/^[MF]$/');								// Valid Gender Uppercase M or F
define('VALID_SINGLE_UPPERCASE', '/^[A-Z]$/'); 					// Single uppercase letter
define('VALID_EMPTY_SINGLE_UPPERCASE', '/^$|^[A-Z]$/');			// Single uppercase letter or empty string
define('VALID_USERNAME', '/^\w{3,}$/');							// allow numbers, letters and underscore (must be at least 3 characters)
define('VALID_INITALS', '/^\w{3}$|^[A-Za-z][\-][A-Za-z]$/');	// allow numbers, letters and dash (must be at least 3 characters)
define('VALID_NAME', '/^[A-Za-z\ \.\,]+$/');						// Valid name and number including 'space' and '.'
define('VALID_ADDRESS', '/^[A-Za-z0-9\ \.\-\,\#]+$/');			// Valid address
define('VALID_ANY', '//');										// Anything validates, even empty
define('VALID_MIME_TYPE', '/^[\w-]+\/[\w-]+$/');				// valid mime content types: allow letters, numbers and/or hyphens (-) separated by a slash (/)
define('VALID_INT_OR_EMPTY', '/^\d+$|^$/');						// Valid int, empty
define('VALID_INT_OR_FLOAT2_OR_EMPTY', '/^$|^\d*\.?\d{1,2}$|^\d+$/');// Valid int, float to the hundreth, or empty
define('VALID_DATETIME_OR_EMPTY', '/^$|^\d{2}\/\d{2}\/\d{4}\ \d{2}/:\d{2}/:\d{2}$/');// Valid format MM/DD/YYYY
define('VALID_ALPHA_NUM_OR_EMPTY', '/^$|^[\w\ \-\.]+$/');		// Alpha Numeric, accepts underscore and dot "."and space
define('VALID_ALPHA_NUM', '/^[\w\ \-\.]+$/');					// Alpha Numeric, accepts underscore and dot "."and space
define('VALID_ALPHA_NUM_COMMA', '/^[\w\ \-\.\,\/]+$/');		// Alpha Numeric, accepts underscore and dot "."and space
define('VALID_YES_NO', '/^[12]$/');								// Valid if one or two
define('VALID_POSTAL_CODE', '/^[\d-]{5,11}$/');					// Valid Postal Code
define('VALID_PHONE_NUMBER', '/^[\d-]{7,12}$/');				// Valid for phone numbers
// #########################################################################
// Constants
// #########################################################################
define('DATABASE_USER_ID', 1); // user_id of the default database User
define('MIN_PASSWORD_LENGTH', 8); // Minimum password length
define('PASSWORD_EXPIRATION_DAYS', (90 * 24 * 60 * 60)); // When will the password expire?
define('FULL_ACCESS_GROUPS', "1"); //What groups have full unrestricted access
define('ADMIN_GROUPS', "1"); //What groups have full unrestricted access

// the amount of time in seconds that must pass before an account may be
// disabled for exceeding MAX_LOGIN_ATTEMPTS login attempts
define('LOGIN_ATTEMPT_PERIOD', 1*60*60); // 1*60*60 seconds = 1 hour
define('MAX_LOGIN_ATTEMPTS', 5); // maximum number of login attempts allowed within LOGIN_ATTEMPT_PERIOD
define('RESULTS_PER_PAGE', 10); //the number of search results to display per page when paginating search results
define('FL_STATE_ID', 10);
define('US_COUNTRY_ID', 230);

// variables for email bass password/username recovery
define('TICKET_TIME_HOURS', 72);

// Variable to control shibboleth login
define('SHIBBOLETH_REQUIRED', false);

?>
