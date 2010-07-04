<?php
      // Admin-only code to delete a course utterly

    require_once(dirname(__FILE__) . '/../config.php');
    require_once($CFG->dirroot . '/course/lib.php');

    $id     = required_param('id', PARAM_INT);              // course id
    $delete = optional_param('delete', '', PARAM_ALPHANUM); // delete confirmation hash

    $PAGE->set_url('/course/delete.php', array('id' => $id));
    require_login();

    if (!can_delete_course($id)) {
        print_error('cannotdeletecourse');
    }

    $site = get_site();

    $strdeletecourse = get_string("deletecourse");
    $stradministration = get_string("administration");
    $strcategories = get_string("categories");

    if (! $course = $DB->get_record("course", array("id"=>$id))) {
        print_error("invalidcourseid");
    }

    $category = $DB->get_record("course_categories", array("id"=>$course->category));

    $PAGE->navbar->add($stradministration, new moodle_url('/admin/index.php/'));
    $PAGE->navbar->add($strcategories, new moodle_url('/course/index.php'));
    $PAGE->navbar->add($category->name, new moodle_url('/course/category.php', array('id'=>$course->category)));
    if (! $delete) {
        $strdeletecheck = get_string("deletecheck", "", $course->shortname);
        $strdeletecoursecheck = get_string("deletecoursecheck");

        $PAGE->navbar->add($strdeletecheck);
        $PAGE->set_title("$site->shortname: $strdeletecheck");
        $PAGE->set_heading($site->fullname);
        echo $OUTPUT->header();

        $message = "$strdeletecoursecheck<br /><br />" . format_string($course->fullname) .  " (" . format_string($course->shortname) . ")";
        echo $OUTPUT->confirm($message, "delete.php?id=$course->id&delete=".md5($course->timemodified), "category.php?id=$course->category");

        echo $OUTPUT->footer();
        exit;
    }

    if ($delete != md5($course->timemodified)) {
        print_error("invalidmd5");
    }

    if (!confirm_sesskey()) {
        print_error('confirmsesskeybad', 'error');
    }

    // OK checks done, delete the course now.

    add_to_log(SITEID, "course", "delete", "view.php?id=$course->id", "$course->fullname (ID $course->id)");

    $strdeletingcourse = get_string("deletingcourse", "", format_string($course->shortname));

    $PAGE->navbar->add($strdeletingcourse);
    $PAGE->set_title("$site->shortname: $strdeletingcourse");
    $PAGE->set_heading($site->fullname);
    echo $OUTPUT->header();
    echo $OUTPUT->heading($strdeletingcourse);

    delete_course($course);
    fix_course_sortorder(); //update course count in catagories

    echo $OUTPUT->heading( get_string("deletedcourse", "", format_string($course->shortname)) );

    echo $OUTPUT->continue_button("category.php?id=$course->category");

    echo $OUTPUT->footer();


