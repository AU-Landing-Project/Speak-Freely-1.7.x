<?php
/**
 *Comment functionality
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Matt Beckett
 * @copyright University of Athabasca 2011
 */

/**
 *
 */
include_once 'lib/functions.php';

function speak_freely_init() {

	// Load system configuration
	global $CONFIG;
	
	// Extend system CSS with our own styles
	elgg_extend_view('metatags','speak_freely/metatags', 1000);

	// Load the language file
	register_translations($CONFIG->pluginspath . "speak_freely/languages/");

	// see if we have any saved settings for this plugin
	$anon_guid = get_plugin_setting('anon_guid','speak_freely');
	$recaptcha = get_plugin_setting('recaptcha','speak_freely');
	$public_key = get_plugin_setting('public_key','speak_freely');
	$private_key = get_plugin_setting('private_key','speak_freely');
	
	//if we don't have a public key, set default
	if(empty($public_key)){
		set_plugin_setting('public_key', '6LfviMMSAAAAACXdnUPHLHheWkAYIJ-m-8QAOy6R', 'speak_freely');
	}
	
	//if we don't have a private key, set default
	
	if(empty($private_key)){
		set_plugin_setting('private_key', '6LfviMMSAAAAAIi_StJYyPXfRggSR9nEKPqkVqvU', 'speak_freely');
	}
	
	// if we don't have a setting for recaptcha, default to yes, better to have it than not if unsure
	if(empty($recaptcha)){
		set_plugin_setting('recaptcha', 'yes', 'speak_freely');	
	}
	
	//get all of the information on our fake user
	$user = get_user($anon_guid);
	
	if(!$user){ // our user is missing, make new one
		$anon_guid = set_anonymous_user();
		//save our fake users guid for the plugin to access
		set_plugin_setting('anon_guid', $anon_guid, 'speak_freely');
	}
	
	
	if(!isloggedin()){
    	set_view_location ('embed/link', $CONFIG->pluginspath.'speak_freely/views/default/speak_freely/embed_link_override.php');
	} 

register_page_handler('speak_freely','speak_freely_page_handler');

}

function speak_freely_pagesetup() {

	if (get_context() == 'admin' && isadminloggedin()) {
		global $CONFIG;
		add_submenu_item(elgg_echo('speak_freely:settings'), $CONFIG->wwwroot . 'pg/speak_freely/edit.php');
	}
}

function speak_freely_page_handler()
{
	global $CONFIG;

	include($CONFIG->pluginspath . "speak_freely/pages/edit.php");
}

global $CONFIG;

register_elgg_event_handler('init','system','speak_freely_init');
register_elgg_event_handler('pagesetup','system','speak_freely_pagesetup');

//register action to save our anonymous comments
register_action("comments/anon_add", true, $CONFIG->pluginspath . "speak_freely/actions/comments/anon_add.php");

//register action to save our plugin settings
register_action("speak_freely_settings", false, $CONFIG->pluginspath . "speak_freely/actions/speak_freely_settings.php", true);


// extend the form view to present a comment form to a logged out user
elgg_extend_view('comments/forms/edit', 'comments/forms/speak_freely_post_edit', 1000);

// add override for anonymous user icon
elgg_extend_view('profile/icon', 'profile/speak_freely_pre_icon', 0);

//add override for anonymous user profile
elgg_extend_view('profile/userdetails', 'profile/speak_freely_pre_userdetails', 0);
elgg_extend_view('output/confirmlink', 'output/speak_freely_pre_confirmlink', 0);
?>