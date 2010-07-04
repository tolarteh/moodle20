<?php  //$Id: mod.php,v 1.1 2010-04-30 03:06:23 moodler Exp $

    if (!defined('MOODLE_INTERNAL')) {
        die('Direct access to this script is forbidden.'); // It must be included from a Moodle page
    }

    if (has_capability('coursereport/completion:view', $context)) {
        $completion = new completion_info($course);
        if ($completion->is_enabled()) {
            echo '<p>';
            echo '<a href="'.$CFG->wwwroot.'/course/report/completion/index.php?course='.$course->id.'">'.get_string('coursecompletionreport','completion').'</a>';
            echo '</p>';
        }
    }
?>
