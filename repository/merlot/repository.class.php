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
 * repository_merlot is used to search merlot.org in moodle
 *
 * @since 2.0
 * @package moodlecore
 * @subpackage repository
 * @copyright 2009 Dongsheng Cai
 * @author Dongsheng Cai <dongsheng@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class repository_merlot extends repository {

    public function __construct($repositoryid, $context = SYSCONTEXTID, $options = array()) {
        parent::__construct($repositoryid, $context, $options);
        $this->keyword = optional_param('merlot_keyword', '', PARAM_RAW);
        $this->author = optional_param('merlot_author', '', PARAM_RAW);
        $this->licensekey = trim(get_config('merlot', 'licensekey'));
    }

    /**
     * Display login screen or not
     *
     * @return boolean
     */
    public function check_login() {
        return !empty($this->keyword);
    }

    /**
     * Doesn't support global search
     *
     * @return boolean
     */
    public function global_search() {
        return false;
    }

    /**
     * Look for a link in merlot.org
     * @param string $search_text
     * @return array
     */
    public function search($search_text) {
        $ret  = array();
        $ret['nologin'] = true;
        $ret['list'] = $this->_get_collection($this->keyword, $this->author);
        return $ret;
    }

    /**
     * Get a list of links
     * @return array
     */
    public function get_listing() {
        $ret  = array();
        $ret['nologin'] = true;
        $ret['list'] = $this->_get_collection($this->keyword);
        return $ret;
    }

    private function _get_collection($keyword, $author) {
        global $OUTPUT;
        $list = array();
        $this->api = 'http://www.merlot.org/merlot/materials.rest?keywords=' . urlencode($keyword) . '&licenseKey='.$this->licensekey;
        $c = new curl(array('cache'=>true, 'module_cache'=>'repository'));
        $content = $c->get($this->api);
        $xml = simplexml_load_string($content);
        foreach ($xml->results->material as $entry) {
            $list[] = array(
                'title'=>(string)$entry->title,
                'thumbnail'=>$OUTPUT->pix_url('f/unknown-32'),
                'date'=>userdate((int)$entry->creationDate),
                'size'=>'',
                'source'=>(string)$entry->URL
            );
        }
        return $list;
    }

    /**
     * Define a search form
     *
     * @return array
     */
    public function print_login(){
        $ret = array();
        $search = new stdclass;
        $search->type = 'text';
        $search->id   = 'merlog_search';
        $search->name = 'merlot_keyword';
        $search->label = get_string('search').': ';
        $author = new stdclass;
        $author->type = 'text';
        $author->id   = 'merlog_author';
        $author->name = 'merlot_author';
        $author->label = get_string('author', 'search').': ';

        $ret['login'] = array($search, $author);
        $ret['login_btn_label'] = get_string('search');
        $ret['login_btn_action'] = 'search';
        return $ret;
    }

    /**
     * Names of the plugin settings
     *
     * @return array
     */
    public static function get_type_option_names() {
        return array('licensekey');
    }

    /**
     * Add Plugin settings input to Moodle form
     *
     * @param object $mform
     */
    public function type_config_form($mform) {
        $licensekey = get_config('merlot', 'licensekey');
        if (empty($licensekey)) {
            $licensekey = '';
        }
        $strrequired = get_string('required');
        $mform->addElement('text', 'licensekey', get_string('licensekey', 'repository_merlot'), array('value'=>$licensekey,'size' => '40'));
        $mform->addRule('licensekey', $strrequired, 'required', null, 'client');
    }

    /**
     * Support external link only
     *
     * @return int
     */
    public function supported_returntypes() {
        return FILE_EXTERNAL;
    }
    public function supported_filetypes() {
        return array('link');
    }
}

