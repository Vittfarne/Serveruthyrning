<?php

function template_main()
{
	global $context, $settings, $options, $txt, $modsettings, $scripturl, $user_profile;
	@loadMemberContext();

	$user_id = $context['user']['id'];
	@loadMemberData($user_id);
	$is_guest = (isset($context['user']['is_guest']) && $context['user']['is_guest']);

	if (!$is_guest)
	{
		$sql = "SELECT additional_groups FROM smf_members WHERE id_member = $user_id";
		$result = mysql_query($sql);
		$additional_groups_string = trim(mysql_result($result, 0));
		$additional_groups = explode(',', $additional_groups_string);
		if (in_array('9', $additional_groups) || $user_profile[$user_id]['id_group'] == '9')
		{
			render_page($user_id);
		}
		else
		{
			critical_error('Access denied. You\'re not a member.');
		}
	}
	else
	{
		critical_error('Access denied.');
	}
}

function critical_error($message)
{
	$error = '';
	$error .= '<h3 class="error">' . $message . '</h3>';
	echo $error;
}

function render_page($user_id)
{
	echo 'Du är användare nummer ' . $user_id . '.';
}

