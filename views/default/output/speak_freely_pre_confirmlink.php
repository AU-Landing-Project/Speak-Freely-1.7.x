<?php
/**
This view gets called before the default output/confirmlink view
It checks to see if we are looking at the anonymous user profile
If we are looking at the profile (we must be admin) it appends a more detailed question
for the deletion confirmation
 */

$anon_guid = get_plugin_setting('anon_guid','speak_freely');

$del_link = "action/admin/user/delete?guid=" . $anon_guid;

if(strpos($vars['href'], $del_link)){
	$vars['confirm'] = elgg_echo('speak_freely:confirm_user_delete');
}