<?php
/**
 * This file defines a simple editor
 *
 * @author Jordi Piguillem
 * @author Josep Arus
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package wiki
 *
 */

/**
 * @TODO: Doc this function
 */
function wiki_print_editor_html($pageid, $content, $version = -1, $section = null, $upload = false, $deleteuploads = array()) {
    global $CFG, $OUTPUT;

    $OUTPUT->heading(strtoupper(get_string('formathtml', 'wiki')));

    $action = $CFG->wwwroot.'/mod/wiki/edit.php?pageid='.$pageid;

    if (!empty($section)) {
        $action .= "&section=".urlencode($section);
    }

    print_container_start(false, 'mdl-align');
    echo '<form method="post" action="'.$action.'">';
    print_container(print_textarea(true, 20, 100, 0, 0, "newcontent", $content, 0, true, '', 'form-textarea-advanced'), false, 'wiki_editor');
    wiki_print_edit_form_default_fields('html', $pageid, $version, $upload, $deleteuploads);
    echo '</form>';

    print_container_end();
}
