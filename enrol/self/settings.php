<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Self enrolment plugin settings and presets.
 *
 * @package   enrol_self
 * @copyright 2010 Petr Skoda {@link http://skodak.org}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    //--- general settings -----------------------------------------------------------------------------------
    $settings->add(new admin_setting_heading('enrol_self_settings', '', get_string('pluginname_desc', 'enrol_self')));

    $settings->add(new admin_setting_configcheckbox('enrol_self/requirepassword',
        get_string('requirepassword', 'enrol_self'), get_string('requirepassword_desc', 'enrol_self'), 0));

    $settings->add(new admin_setting_configcheckbox('enrol_self/usepasswordpolicy',
        get_string('usepasswordpolicy', 'enrol_self'), get_string('usepasswordpolicy_desc', 'enrol_self'), 0));

    $settings->add(new admin_setting_configcheckbox('enrol_self/showhint',
        get_string('showhint', 'enrol_self'), get_string('showhint_desc', 'enrol_self'), 0));

    $settings->add(new admin_setting_configcheckbox('enrol_self/sendcoursewelcomemessage',
        get_string('sendcoursewelcomemessage', 'enrol_self'), get_string('sendcoursewelcomemessage_desc', 'enrol_self'), 1));


    //--- enrol instance defaults ----------------------------------------------------------------------------
    $settings->add(new admin_setting_heading('enrol_self_defaults',
        get_string('enrolinstancedefaults', 'admin'), get_string('enrolinstancedefaults_desc', 'admin')));

    $settings->add(new admin_setting_configcheckbox('enrol_self/defaultenrol',
        get_string('defaultenrol', 'enrol'), get_string('defaultenrol_desc', 'enrol'), 1));

    $options = array(ENROL_INSTANCE_ENABLED  => get_string('yes'),
                     ENROL_INSTANCE_DISABLED => get_string('no'));
    $settings->add(new admin_setting_configselect_with_advanced('enrol_self/status',
        get_string('status', 'enrol_self'), get_string('status_desc', 'enrol_self'),
        array('value'=>ENROL_INSTANCE_DISABLED, 'adv'=>false), $options));

    $options = array(1  => get_string('yes'),
                     0 => get_string('no'));
    $settings->add(new admin_setting_configselect_with_advanced('enrol_self/groupkey',
        get_string('groupkey', 'enrol_self'), get_string('groupkey_desc', 'enrol_self'),
        array('value'=>0, 'adv'=>true), $options));

    if (!during_initial_install()) {
        $options = get_default_enrol_roles(get_context_instance(CONTEXT_SYSTEM));
        $student = get_archetype_roles('student');
        $student = reset($student);
        $settings->add(new admin_setting_configselect_with_advanced('enrol_self/roleid',
            get_string('defaultrole', 'enrol_self'), get_string('defaultrole_desc', 'enrol_self'),
            array('value'=>$student->id, 'adv'=>false), $options));
    }

    $settings->add(new admin_setting_configtext_with_advanced('enrol_self/enrolperiod',
        get_string('enrolperiod', 'enrol_self'), get_string('enrolperiod_desc', 'enrol_self'),
        array('value'=>0, 'adv'=>true), PARAM_INT));


}
