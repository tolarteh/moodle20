<?php

require_once($CFG->dirroot . '/comment/lib.php');

class block_comments extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_comments');
        $this->version = 2009072000;
    }

    function specialization() {
        // require js for commenting
        comment::init();
    }
    function applicable_formats() {
        return array('all' => true);
    }

    function instance_allow_multiple() {
        return false;
    }

    function get_content() {
        global $CFG, $PAGE;
        if (!$CFG->usecomments) {
            $this->content->text = '';
            if ($this->page->user_is_editing()) {
                $this->content->text = get_string('disabledcomments');
            }
            return $this->content;
        }
        if ($this->content !== NULL) {
            return $this->content;
        }
        if (empty($this->instance)) {
            return null;
        }
        $this->content->footer = '';
        $this->content->text = '';
        if (isloggedin() && !isguestuser()) {
            list($context, $course, $cm) = get_context_info_array($PAGE->context->id);
            $args = new stdclass;
            $args->context   = $PAGE->context;
            $args->course    = $course;
            $args->area      = 'page_comments';
            $args->itemid    = 0;
            // set 'env' to tell moodle tweak ui for this block
            $args->env       = 'block_comments';
            $args->linktext  = get_string('showcomments');
            $comment = new comment($args);

            $this->content = new stdClass;
            $this->content->text = $comment->output(true);
            $this->content->footer = '';
        }
        return $this->content;
    }
    function instance_delete() {
        global $DB, $PAGE;
        //delete orphan records
        $DB->delete_records('comments', array('contextid'=>$PAGE->context->id, 'commentarea'=>'page_comments', 'itemid'=>0));
        return true;
    }
}
