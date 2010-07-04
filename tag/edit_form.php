<?php

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->dirroot.'/lib/formslib.php');

class tag_edit_form extends moodleform {

    function definition () {

        $mform =& $this->_form;

        $mform->addElement('header', 'tag', get_string('description','tag'));

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $systemcontext   = get_context_instance(CONTEXT_SYSTEM);

        if (has_capability('moodle/tag:manage', $systemcontext)) {
            $mform->addElement('text', 'rawname', get_string('name', 'tag'),
                    'maxlength="'.TAG_MAX_LENGTH.'" size="'.TAG_MAX_LENGTH.'"');
        }

        $mform->addElement('editor', 'description_editor', get_string('description', 'tag'), null, $this->_customdata['editoroptions']);

        if (has_capability('moodle/tag:manage', $systemcontext)) {
           $mform->addElement('checkbox', 'tagtype', get_string('officialtag', 'tag'));
        }

        $mform->addElement('html', '<br/><div id="relatedtags-autocomplete-container">');
        $mform->addElement('textarea', 'relatedtags', get_string('relatedtags','tag'), 'cols="50" rows="3"');
        $mform->setType('relatedtags', PARAM_TAGLIST);
        $mform->addElement('html', '<div id="relatedtags-autocomplete"></div>');
        $mform->addElement('html', '</div>');

        $this->add_action_buttons(false, get_string('updatetag', 'tag'));

    }

}
