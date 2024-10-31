<?php
/*
Plugin Name: Restrict Password Changes - MultiSite
Plugin URI: http://www.totallyryan.com/projects/restrict-password-changes-wordpress-multisite/
Description: This is a plugin for Wordpress Multisite 3.0+ that restricts password changes or resets to super admins only. This is handy when user creation and authentication are handled from an external application and passwords should only be changed on one system.
Author: Ryan Willis
Version: 0.1
Author URI: http://www.totallyryan.com
*/

/*
/--------------------------------------------------------------------\
|                                                                    |
| License: GPL                                                       |
|                                                                    |
| Restrict Password Changes (MultiSite) - Restricts password         |
| changes or resets to super administrators                          |
| Copyright (C) 2010, Ryan Willis,                                   |
| http://www.totallyryan.com                                         |
| All rights reserved.                                               |
|                                                                    |
| This program is free software; you can redistribute it and/or      |
| modify it under the terms of the GNU General Public License        |
| as published by the Free Software Foundation; either version 2     |
| of the License, or (at your option) any later version.             |
|                                                                    |
| This program is distributed in the hope that it will be useful,    |
| but WITHOUT ANY WARRANTY; without even the implied warranty of     |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the      |
| GNU General Public License for more details.                       |
|                                                                    |
| You should have received a copy of the GNU General Public License  |
| along with this program; if not, write to the                      |
| Free Software Foundation, Inc.                                     |
| 51 Franklin Street, Fifth Floor                                    |
| Boston, MA  02110-1301, USA                                        |
|                                                                    |
\--------------------------------------------------------------------/
*/

function tr_restrict_password_changes_prevent() {

		if(!is_super_admin()) {
			$_POST['pass1'] = '';
			$_POST['pass2'] = '';
		}

}

function tr_restrict_password_changes($val = null) {

	if(is_multisite()) {
		
		if(is_super_admin()) return true;
		else return false;

	} else {

		return true;

	}

}

function tr_restrict_password_reset() {
	return false;
}

function tr_remove_reset_link_init() {
	add_filter('gettext', 'tr_remove_reset_link');
}

function tr_remove_reset_link($text) {
	if(strpos($text, 'Lost your password') !== false) $text = str_replace('Lost your password', '', str_replace('Lost your password?', '', str_replace('Lost your password</a>?', '</a>', $text)));
	return $text;
}

add_filter('show_password_fields', 'tr_restrict_password_changes');
add_action('edit_user_profile_update', 'tr_restrict_password_changes_prevent');
add_action('personal_options_update', 'tr_restrict_password_changes_prevent');
add_filter('allow_password_reset', 'tr_restrict_password_reset');
add_action('login_head', 'tr_remove_reset_link_init');
add_filter('login_errors', 'tr_remove_reset_link');


?>