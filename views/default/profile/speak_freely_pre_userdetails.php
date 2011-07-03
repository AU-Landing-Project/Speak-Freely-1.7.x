<?php

/**
This view gets called before the default profile/userdetails view
It checks to see if we are looking at the anonymous user profile
If we are looking at the profile and are not an admin, it registers an error and
sends them back to the page they came from.
 */
$anon_guid = get_plugin_setting('anon_guid','speak_freely');

//if they're not admin and trying view anon_user, register error and send them away
if ($vars['entity']->guid == $anon_guid && !isadminloggedin()){
	register_error(elgg_echo('speak_freely:profile_view'));
	forward(REFERRER);
}

// they are admin and viewing anon_user - show warning about deletion
if ($vars['entity']->guid == $anon_guid && isadminloggedin() && $speakfreelywarningcount != 1){
	//counter to prevent more than one display of this view - have to track down that bug
	$speakfreelywarningcount = 1;
?>
<div class="speak_freely_warning">
<?php echo elgg_echo('speak_freely:profile:warning'); ?>
</div>
<?php
}