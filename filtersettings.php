<?php

/*$settings->add(new admin_setting_configcheckbox('filter_multilangsecond_mode', 
        get_string('mode', 'filter_multilangsecond'),
        get_string('mode_desc', 'filter_multilangsecond'), 0));*/

$settings->add(new admin_setting_configselect(
        'filter_multilangsecond_mode',
        get_string('modenew', 'filter_multilangsecond'),
        get_string('modenew_desc', 'filter_multilangsecond'),
        0,
        array(get_string('mode0', 'filter_multilangsecond'),
              get_string('mode1', 'filter_multilangsecond'),
              get_string('mode2', 'filter_multilangsecond')
        	)
	));