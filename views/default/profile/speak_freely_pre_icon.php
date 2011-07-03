<?php
/**
 * This view is called before the profile/icon view
 * If the icon we are looking for belongs to our anonymous user then set $vars['override'] to true
 * This will allow the icon to be fetched but will disable the link to the profile and the drop down menu
 * 
 */
if (empty($vars['entity']))
			$vars['entity'] = $vars['user'];

		if ($vars['entity'] instanceof ElggUser) {
			$anon_guid = get_plugin_setting('anon_guid','speak_freely');
			if($vars['entity']->guid == $anon_guid){
				$vars['override'] = true;
			}
		}