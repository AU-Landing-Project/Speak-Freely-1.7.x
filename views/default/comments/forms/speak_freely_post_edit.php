<?php

/**
 *
 * Presents a comment form to a non-logged in person
 */
	// there is some bug with the extended view where this will be called multiple times
	// use a token counter to make sure that form is only displayed the first time

if (isset($vars['entity']) && !isloggedin()) {
	
	if($speakfreelyformcounter != 1){  //first time, create the form..
		$speakfreelyformcounter = 1; // now this variable is set, so the form shouldn't replicate
		
		require_once($CONFIG->pluginspath . 'speak_freely/lib/recaptchalib.php');
		$publickey = get_plugin_setting('public_key', 'speak_freely'); // you got this from the signup page

		$form_body = "<div class=\"contentWrapper\">";
		$form_body .= "<label>" . elgg_echo('speak_freely:name') . "<br> " . elgg_view('input/text', array('internalname' => 'anon_name', 'value' => $_SESSION['speak_freely']['anon_name'], 'internalid' => 'speak_freely_name_field')) . "</label> (" . elgg_echo('speak_freely:required') . ")<br><br>";
		$form_body .= "<p class='longtext_editarea'><label>".elgg_echo("generic_comments:text")."<br />" . elgg_view('input/longtext',array('internalname' => 'generic_comment', 'value' => $_SESSION['speak_freely']['generic_comment'])) . "</label></p>";
		$form_body .= "<p>" . elgg_view('input/hidden', array('internalname' => 'entity_guid', 'value' => $vars['entity']->getGUID()));

		// if we have set recaptcha then display the output
		if(get_plugin_setting('recaptcha','speak_freely') == "yes"){
			$recaptcha_style = get_plugin_setting('recaptcha_style','speak_freely');
			if(empty($recaptcha_style)){
				$recaptcha_style = "red"; // set default	
			} 
 
			$form_body .= "<script type=\"text/javascript\">";
			$form_body .= "var RecaptchaOptions = {";
			$form_body .= "theme : '$recaptcha_style'";
			$form_body .= "};";
			$form_body .= "</script>"; 
			
			$form_body .= recaptcha_get_html($publickey);
		}

		$form_body .= elgg_view('input/submit', array('value' => elgg_echo("post"))) . "</p></div>";

		// output the form
		echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$vars['url']}action/comments/anon_add"));
		unset($_SESSION['speak_freely']);
	}
}