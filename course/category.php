<?php
      // Displays the top level category or all courses
      // In editing mode, allows the admin to edit a category,
      // and rearrange courses

    require_once("../config.php");
    require_once("lib.php");

    $id = required_param('id', PARAM_INT); // Category id
    $page = optional_param('page', 0, PARAM_INT); // which page to show
    $perpage = optional_param('perpage', $CFG->coursesperpage, PARAM_INT); // how many per page
    $categoryedit = optional_param('categoryedit', -1, PARAM_BOOL);
    $hide = optional_param('hide', 0, PARAM_INT);
    $show = optional_param('show', 0, PARAM_INT);
    $moveup = optional_param('moveup', 0, PARAM_INT);
    $movedown = optional_param('movedown', 0, PARAM_INT);
    $moveto = optional_param('moveto', 0, PARAM_INT);
    $resort = optional_param('resort', 0, PARAM_BOOL);

    $site = get_site();

    if (empty($id)) {
        print_error("unknowcategory");
    }

    $PAGE->set_category_by_id($id);
    $urlparams = array('id' => $id);
    if ($page) {
        $urlparams['page'] = $page;
    }
    if ($perpage) {
        $urlparams['perpage'] = $perpage;
    }
    $PAGE->set_url('/course/category.php', $urlparams);
    $context = $PAGE->context;
    $category = $PAGE->category;

    $canedit = can_edit_in_category($category->id);
    if ($canedit) {
        if ($categoryedit !== -1) {
            $USER->editing = $categoryedit;
        }
        require_login();
        $editingon = $PAGE->user_is_editing();
    } else {
        if ($CFG->forcelogin) {
            require_login();
        }
        $editingon = false;
    }

    if (!$category->visible) {
        require_capability('moodle/category:viewhiddencategories', $context);
    }

    // Process any category actions.
    if (has_capability('moodle/category:manage', $context)) {
        /// Resort the category if requested
        if ($resort and confirm_sesskey()) {
            if ($courses = get_courses($category->id, "fullname ASC", 'c.id,c.fullname,c.sortorder')) {
                $i = 1;
                foreach ($courses as $course) {
                    $DB->set_field('course', 'sortorder', $category->sortorder+$i, array('id'=>$course->id));
                    $i++;
                }
                fix_course_sortorder(); // should not be needed
            }
        }
    }

    // Process any course actions.
    if ($editingon) {
    /// Move a specified course to a new category
        if (!empty($moveto) and $data = data_submitted() and confirm_sesskey()) {   // Some courses are being moved
            // user must have category update in both cats to perform this
            require_capability('moodle/category:manage', $context);
            require_capability('moodle/category:manage', get_context_instance(CONTEXT_COURSECAT, $moveto));

            if (!$destcategory = $DB->get_record('course_categories', array('id' => $data->moveto))) {
                print_error('cannotfindcategory', '', '', $data->moveto);
            }

            $courses = array();
            foreach ($data as $key => $value) {
                if (preg_match('/^c\d+$/', $key)) {
                    $courseid = substr($key, 1);
                    array_push($courses, $courseid);

                    // check this course's category
                    if ($movingcourse = $DB->get_record('course', array('id'=>$courseid))) {
                        if ($movingcourse->category != $id ) {
                            print_error('coursedoesnotbelongtocategory');
                        }
                    } else {
                        print_error('cannotfindcourse');
                    }
                }
            }
            move_courses($courses, $data->moveto);
        }

    /// Hide or show a course
        if ((!empty($hide) or !empty($show)) and confirm_sesskey()) {
            if (!empty($hide)) {
                $course = $DB->get_record('course', array('id' => $hide));
                $visible = 0;
            } else {
                $course = $DB->get_record('course', array('id' => $show));
                $visible = 1;
            }

            if ($course) {
                $coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);
                require_capability('moodle/course:visibility', $coursecontext);
                $DB->set_field('course', 'visible', $visible, array('id' => $course->id));
                $DB->set_field('course', 'visibleold', $visible, array('id' => $course->id)); // we set the old flag when user manually changes visibility of course
            }
        }


    /// Move a course up or down
        if ((!empty($moveup) or !empty($movedown)) and confirm_sesskey()) {
            require_capability('moodle/category:manage', $context);

            // Ensure the course order has continuous ordering
            fix_course_sortorder();
            $swapcourse = NULL;

            if (!empty($moveup)) {
                if ($movecourse = $DB->get_record('course', array('id' => $moveup))) {
                    $swapcourse = $DB->get_record('course', array('sortorder' => $movecourse->sortorder - 1));
                }
            } else {
                if ($movecourse = $DB->get_record('course', array('id' => $movedown))) {
                    $swapcourse = $DB->get_record('course', array('sortorder' => $movecourse->sortorder + 1));
                }
            }
            if ($swapcourse and $movecourse) {
                // check course's category
                if ($movecourse->category != $id) {
                    print_error('coursedoesnotbelongtocategory');
                }
                $DB->set_field('course', 'sortorder', $swapcourse->sortorder, array('id' => $movecourse->id));
                $DB->set_field('course', 'sortorder', $movecourse->sortorder, array('id' => $swapcourse->id));
            }
        }

    } // End of editing stuff

    // Print headings
    $numcategories = $DB->count_records('course_categories');

    $stradministration = get_string('administration');
    $strcategories = get_string('categories');
    $strcategory = get_string('category');
    $strcourses = get_string('courses');

    if ($editingon && can_edit_in_category()) {
        // Integrate into the admin tree only if the user can edit categories at the top level,
        // otherwise the admin block does not appear to this user, and you get an error.
        require_once($CFG->libdir . '/adminlib.php');
        admin_externalpage_setup('coursemgmt', '', $urlparams, $CFG->wwwroot . '/course/category.php');
        $PAGE->set_context($context);   // Ensure that we are actually showing blocks etc for the cat context
        echo $OUTPUT->header();
    } else {
        $PAGE->navbar->add($strcategories, new moodle_url('/course/index.php'));
        $PAGE->navbar->add($category->name);
        $PAGE->navbar->add($strcourses);
        $PAGE->set_title("$site->shortname: $category->name");
        $PAGE->set_heading($site->fullname);
        $PAGE->set_button(print_course_search('', true, 'navbar'));
        $PAGE->set_pagelayout('coursecategory');
        echo $OUTPUT->header();
    }

/// Print link to roles
    if (has_capability('moodle/role:assign', $context)) {
        echo '<div class="rolelink"><a href="'.$CFG->wwwroot.'/'.$CFG->admin.'/roles/assign.php?contextid='.
         $context->id.'">'.get_string('assignroles','role').'</a></div>';
    }

/// Print the category selector
    $displaylist = array();
    $notused = array();
    make_categories_list($displaylist, $notused);

    echo '<div class="categorypicker">';
    $select = new single_select(new moodle_url('category.php'), 'id', $displaylist, $category->id, null, 'switchcategory');
    $select->set_label($strcategories.':');
    echo $OUTPUT->render($select);
    echo '</div>';

/// Print current category description
    if (!$editingon && $category->description) {
        echo $OUTPUT->box_start();
        $options = new stdClass;
        $options->noclean = true;
        $options->para = false;
        if (!isset($category->descriptionformat)) {
            $category->descriptionformat = FORMAT_MOODLE;
        }
        $text = file_rewrite_pluginfile_urls($category->description, 'pluginfile.php', $context->id, 'category_description', $category->id);
        echo format_text($text, $category->descriptionformat, $options);
        echo $OUTPUT->box_end();
    }

    if ($editingon && has_capability('moodle/category:manage', $context)) {
        echo $OUTPUT->container_start('buttons');

        // Print button to update this category
        $options = array('id' => $category->id);
        echo $OUTPUT->single_button(new moodle_url('/course/editcategory.php', $options), get_string('editcategorythis'), 'get');

        // Print button for creating new categories
        $options = array('parent' => $category->id);
        echo $OUTPUT->single_button(new moodle_url('/course/editcategory.php', $options), get_string('addsubcategory'), 'get');

        echo $OUTPUT->container_end();
    }

/// Print out all the sub-categories
    if ($subcategories = $DB->get_records('course_categories', array('parent' => $category->id), 'sortorder ASC')) {
        $firstentry = true;
        foreach ($subcategories as $subcategory) {
            if ($subcategory->visible || has_capability('moodle/category:viewhiddencategories', $context)) {
                $subcategorieswereshown = true;
                if ($firstentry) {
                    echo '<table border="0" cellspacing="2" cellpadding="4" class="generalbox boxaligncenter">';
                    echo '<tr><th scope="col">'.get_string('subcategories').'</th></tr>';
                    echo '<tr><td style="white-space: nowrap">';
                    $firstentry = false;
                }
                $catlinkcss = $subcategory->visible ? '' : ' class="dimmed" ';
                echo '<a '.$catlinkcss.' href="category.php?id='.$subcategory->id.'">'.
                     format_string($subcategory->name).'</a><br />';
            }
        }
        if (!$firstentry) {
            echo '</td></tr></table>';
            echo '<br />';
        }
    }

/// Print out all the courses
    $courses = get_courses_page($category->id, 'c.sortorder ASC',
            'c.id,c.sortorder,c.shortname,c.fullname,c.summary,c.visible',
            $totalcount, $page*$perpage, $perpage);
    $numcourses = count($courses);

    if (!$courses) {
        if (empty($subcategorieswereshown)) {
            echo $OUTPUT->heading(get_string("nocoursesyet"));
        }

    } else if ($numcourses <= COURSE_MAX_SUMMARIES_PER_PAGE and !$page and !$editingon) {
        echo $OUTPUT->box_start('courseboxes');
        print_courses($category);
        echo $OUTPUT->box_end();

    } else {
        echo $OUTPUT->paging_bar($totalcount, $page, $perpage, "/mod/course/category.php?id=$category->id&perpage=$perpage");

        $strcourses = get_string('courses');
        $strselect = get_string('select');
        $stredit = get_string('edit');
        $strdelete = get_string('delete');
        $strbackup = get_string('backup');
        $strrestore = get_string('restore');
        $strmoveup = get_string('moveup');
        $strmovedown = get_string('movedown');
        $strupdate = get_string('update');
        $strhide = get_string('hide');
        $strshow = get_string('show');
        $strsummary = get_string('summary');
        $strsettings = get_string('settings');
        $strallowguests = get_string('allowguests');
        $strrequireskey = get_string('requireskey');


        echo '<form id="movecourses" action="category.php" method="post"><div>';
        echo '<input type="hidden" name="sesskey" value="'.sesskey().'" />';
        echo '<table border="0" cellspacing="2" cellpadding="4" class="generalbox boxaligncenter"><tr>';
        echo '<th class="header" scope="col">'.$strcourses.'</th>';
        if ($editingon) {
            echo '<th class="header" scope="col">'.$stredit.'</th>';
            echo '<th class="header" scope="col">'.$strselect.'</th>';
        } else {
            echo '<th class="header" scope="col">&nbsp;</th>';
        }
        echo '</tr>';


        $count = 0;
        $abletomovecourses = false;  // for now

        // Checking if we are at the first or at the last page, to allow courses to
        // be moved up and down beyond the paging border
        if ($totalcount > $perpage) {
            $atfirstpage = ($page == 0);
            if ($perpage > 0) {
                $atlastpage = (($page + 1) == ceil($totalcount / $perpage));
            } else {
                $atlastpage = true;
            }
        } else {
            $atfirstpage = true;
            $atlastpage = true;
        }

        $spacer = '<img src="'.$CFG->wwwroot.'/pix/spacer.gif" class="iconsmall" alt="" /> ';
        foreach ($courses as $acourse) {
            $coursecontext = get_context_instance(CONTEXT_COURSE, $acourse->id);

            $count++;
            $up = ($count > 1 || !$atfirstpage);
            $down = ($count < $numcourses || !$atlastpage);

            $linkcss = $acourse->visible ? '' : ' class="dimmed" ';
            echo '<tr>';
            echo '<td><a '.$linkcss.' href="view.php?id='.$acourse->id.'">'. format_string($acourse->fullname) .'</a></td>';
            if ($editingon) {
                echo '<td>';
                if (has_capability('moodle/course:update', $coursecontext)) {
                    echo '<a title="'.$strsettings.'" href="'.$CFG->wwwroot.'/course/edit.php?id='.$acourse->id.'">'.
                            '<img src="'.$OUTPUT->pix_url('t/edit') . '" class="iconsmall" alt="'.$stredit.'" /></a> ';
                } else {
                    echo $spacer;
                }

                // role assignment link
                if (has_capability('moodle/role:assign', $coursecontext)) {
                    echo '<a title="'.get_string('assignroles', 'role').'" href="'.$CFG->wwwroot.'/'.$CFG->admin.'/roles/assign.php?contextid='.$coursecontext->id.'">'.
                            '<img src="'.$OUTPUT->pix_url('i/roles') . '" class="iconsmall" alt="'.get_string('assignroles', 'role').'" /></a> ';
                } else {
                    echo $spacer;
                }

                if (can_delete_course($acourse->id)) {
                    echo '<a title="'.$strdelete.'" href="delete.php?id='.$acourse->id.'">'.
                            '<img src="'.$OUTPUT->pix_url('t/delete') . '" class="iconsmall" alt="'.$strdelete.'" /></a> ';
                } else {
                    echo $spacer;
                }

                // MDL-8885, users with no capability to view hidden courses, should not be able to lock themselves out
                if (has_capability('moodle/course:visibility', $coursecontext) && has_capability('moodle/course:viewhiddencourses', $coursecontext)) {
                    if (!empty($acourse->visible)) {
                        echo '<a title="'.$strhide.'" href="category.php?id='.$category->id.'&amp;page='.$page.
                            '&amp;perpage='.$perpage.'&amp;hide='.$acourse->id.'&amp;sesskey='.sesskey().'">'.
                            '<img src="'.$OUTPUT->pix_url('t/hide') . '" class="iconsmall" alt="'.$strhide.'" /></a> ';
                    } else {
                        echo '<a title="'.$strshow.'" href="category.php?id='.$category->id.'&amp;page='.$page.
                            '&amp;perpage='.$perpage.'&amp;show='.$acourse->id.'&amp;sesskey='.sesskey().'">'.
                            '<img src="'.$OUTPUT->pix_url('t/show') . '" class="iconsmall" alt="'.$strshow.'" /></a> ';
                    }
                } else {
                    echo $spacer;
                }

                if (has_capability('moodle/backup:backupcourse', $coursecontext)) {
                    echo '<a title="'.$strbackup.'" href="../backup/backup.php?id='.$acourse->id.'">'.
                            '<img src="'.$OUTPUT->pix_url('t/backup') . '" class="iconsmall" alt="'.$strbackup.'" /></a> ';
                } else {
                    echo $spacer;
                }

                if (has_capability('moodle/restore:restorecourse', $coursecontext)) {
                    echo '<a title="'.$strrestore.'" href="../files/index.php?id='.$acourse->id.
                         '&amp;wdir=/backupdata">'.
                         '<img src="'.$OUTPUT->pix_url('t/restore') . '" class="iconsmall" alt="'.$strrestore.'" /></a> ';
                } else {
                    echo $spacer;
                }

                if (has_capability('moodle/category:manage', $context)) {
                    if ($up) {
                        echo '<a title="'.$strmoveup.'" href="category.php?id='.$category->id.'&amp;page='.$page.
                             '&amp;perpage='.$perpage.'&amp;moveup='.$acourse->id.'&amp;sesskey='.sesskey().'">'.
                             '<img src="'.$OUTPUT->pix_url('t/up') . '" class="iconsmall" alt="'.$strmoveup.'" /></a> ';
                    } else {
                        echo $spacer;
                    }

                    if ($down) {
                        echo '<a title="'.$strmovedown.'" href="category.php?id='.$category->id.'&amp;page='.$page.
                             '&amp;perpage='.$perpage.'&amp;movedown='.$acourse->id.'&amp;sesskey='.sesskey().'">'.
                             '<img src="'.$OUTPUT->pix_url('t/down') . '" class="iconsmall" alt="'.$strmovedown.'" /></a> ';
                    } else {
                        echo $spacer;
                    }
                    $abletomovecourses = true;
                } else {
                    echo $spacer, $spacer;
                }

                echo '</td>';
                echo '<td align="center">';
                echo '<input type="checkbox" name="c'.$acourse->id.'" />';
                echo '</td>';
            } else {
                echo '<td align="right">';
                //TODO: show some icons for plugins - such as guest, pasword, etc.
                if (!empty($acourse->summary)) {
                    $link = new moodle_url("/course/info.php?id=$acourse->id");
                    echo $OUTPUT->action_link($link, '<img alt="'.get_string('info').'" class="icon" src="'.$OUTPUT->pix_url('i/info') . '" />',
                        new popup_action('click', $link, 'courseinfo'), array('title'=>$strsummary));
                }
                echo "</td>";
            }
            echo "</tr>";
        }

        if ($abletomovecourses) {
            $movetocategories = array();
            $notused = array();
            make_categories_list($movetocategories, $notused, 'moodle/category:manage');
            $movetocategories[$category->id] = get_string('moveselectedcoursesto');
            echo '<tr><td colspan="3" align="right">';
            echo html_writer::select($movetocategories, 'moveto', $category->id, null, array('id'=>'movetoid'));
            $PAGE->requires->js_init_call('M.util.init_select_autosubmit', array('movecourses', 'movetoid', false));
            echo '<input type="hidden" name="id" value="'.$category->id.'" />';
            echo '</td></tr>';
        }

        echo '</table>';
        echo '</div></form>';
        echo '<br />';
    }

    echo '<div class="buttons">';
    if (has_capability('moodle/category:manage', $context) and $numcourses > 1) {
    /// Print button to re-sort courses by name
        unset($options);
        $options['id'] = $category->id;
        $options['resort'] = 'name';
        $options['sesskey'] = sesskey();
        echo $OUTPUT->single_button(new moodle_url('category.php', $options), get_string('resortcoursesbyname'), 'get');
    }

    if (has_capability('moodle/course:create', $context)) {
    /// Print button to create a new course
        unset($options);
        $options['category'] = $category->id;
        echo $OUTPUT->single_button(new moodle_url('edit.php', $options), get_string('addnewcourse'), 'get');
    }

    if (!empty($CFG->enablecourserequests) && $category->id == $CFG->enablecourserequests) {
        print_course_request_buttons(get_context_instance(CONTEXT_SYSTEM));
    }
    echo '</div>';

    print_course_search();

    echo $OUTPUT->footer();

