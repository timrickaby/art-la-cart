<?php
/**
 * 
 * Name:     		Art La Cart
 * Product URI:		https://artlacart.com
 * Description:		Content Management System with Events, Galleries and Basket Support
 * Version:			1.0.0
 * Author:			Tim Rickaby
 * Author URI:		https://timrickaby.com & https://modocodo.com
 * Copyright:		© 2011 Tim Rickaby
 * 
 */

define('ALC_APP_VIEW', 0);
define('ALC_APP_SERVICE', 1);
define('ALC_APP_API', 2);

define('ALC_SCOPE_PUBLIC', 0);
define('ALC_SCOPE_PRIVATE', 1);

define('ALC_RETURN_INSTANCE', 0);
define('ALC_RETURN_ARRAY', 1);
define('ALC_RETURN_JSON', 2);
define('ALC_RETURN_XML', 3);

define('ALC_TTL_TEMPORARY', 0);
define('ALC_TTL_SHORT', 180);
define('ALC_TTL_MEDIUM', 1800);
define('ALC_TTL_LONG', 10800);
define('ALC_TTL_SESSION', 'S');
define('ALC_TTL_FOREVER', '*');

define('ALC_TTL_ONE_USE', '!');
define('ALC_TTL_ONE_TRIP', '@');

define('ALC_SORT_ASC', 'ASC');
define('ALC_SORT_DESC', 'DESC');

define('ALC_CASE_LOWER', 0);
define('ALC_CASE_UPPER', 1);
define('ALC_CASE_TITLE', 2);

define('ALC_IMAGE_ORIGINAL', 'o');
define('ALC_IMAGE_LARGE', 'l');
define('ALC_IMAGE_MEDIUM', 'm');
define('ALC_IMAGE_TILE', 't');
define('ALC_IMAGE_SQUARE_LARGE', 'sl');
define('ALC_IMAGE_SQUARE_SMALL', 'ss');
define('ALC_IMAGE_THUMB_LARGE', 'tl');
define('ALC_IMAGE_THUMB_SMALL', 'ts');

define('ALC_IMAGE_JPG', '.jpg');
define('ALC_IMAGE_PNG', '.png');

define('ALC_INIT_CONSTANTS', true);
?>