<?php

$ADMIN->add('reports', new admin_externalpage('reportquestioninstances', get_string('questioninstances', 'report_questioninstances'), "$CFG->wwwroot/$CFG->admin/report/questioninstances/index.php", 'report/questioninstances:view'));
