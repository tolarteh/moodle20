<?php

require_once(dirname(__FILE__).'/content.php');

class Experiment {
    var $id;
    var $name;
    var $html;
    var $laboratory_id;
    var $description;
    var $is_active;

    var $introduction;
    var $theory;
    var $setup;
    var $proc;

    function Experiment($id, $name, $description, $html, $is_active=0, $laboratory_id=0, $texts) {
        $this->id = $id;
        $this->name = (string) $name;
        $this->description = (string) $description;
        $this->html = (string) $html;
        $this->laboratory_id = $laboratory_id;
        $this->is_active = $is_active;

        $default = array("introduction" => "", "theory" => "", "setup" => "", "proc" => "");
        $texts = array_merge($default, $texts);

        $this->introduction = $texts["introduction"];
        $this->theory = $texts["theory"];
        $this->proc = $texts["proc"];
        $this->setup = $texts["setup"];
    }

    static function db_obj_to_experiment($record) {
        if (!$record) {
            return $record;
        }
        else {
            $texts = array("introduction" => $record->introduction,
                "theory" => $record->theory,
                "setup"  => $record->setup,
                "proc"   => $record->proc);
            $exp = new Experiment($record->id, $record->name, $record->description,
                $record->html, $record->is_active, $record->laboratory_id, $texts);
            return $exp;
        }
    }

    function activation_link($show, $hide) {
        if ($this->is_active)
            return "<a href='activate.php?experiment_id=" . $this->id . "'><img title='Desactivar' alt='Desactivar' src='" . $hide . "'/></a>";
        else
            return "<a href='activate.php?experiment_id=" . $this->id . "'><img title='Activar' alt='Activar' src='" . $show . "'/></a>";
    }

    function contents() {
        global $DB;
        $sql = "SELECT * FROM  `mdl_contents` WHERE `experiment_id`=" . $this->id;
        $records = $DB->get_records_sql($sql);
        return array_map( "Content::db_obj_to_content", $records );
    }

    function add_content($content) {
        global $DB;
        $content->experiment_id = $this->id;
        return $DB->insert_record('contents', $content);
    }

    function laboratory() {
        global $DB;
        $record = $DB->get_record("laboratories", array("id" => $this->laboratory_id));
        return Laboratory::db_obj_to_laboratory($record);
    }

    static function create($name, $description, $html, $laboratory_id, $texts) {
        global $DB;

        /* Dirty hack: Please read the explanation behind this in Laboratory::create. */
        $tmp = new object();
        $tmp->name = $name;
        $tmp->html = $html;
        $tmp->description = $description;
        $tmp->laboratory_id = $laboratory_id;
        $tmp->is_active = 0;
        $tmp->introduction = $texts["introduction"];
        $tmp->setup = $texts["setup"];
        $tmp->proc = $texts["proc"];
        $tmp->theory = $texts["theory"];

        $id = $DB->insert_record("experiments", $tmp);
        return new Experiment($id, $name, $description, $html, 0, $laboratory_id, $texts);
    }

    /* PHP hack to be able to access callbacks on objects */
    static function is_active($exp) {
        return $exp->is_active == 1;
    }

    function update() {
        global $DB;
        $DB->update_record('experiments', $this);
    }

    function delete(){
        global $DB;
        if ($DB->delete_records("experiments", array("id" => $this->id)))
            return true;

        return false;
    }

    static function find_by_id($id) {
        global $DB;
        $record = $DB->get_record("experiments", array("id" => $id));
        return Experiment::db_obj_to_experiment($record);
    }
}
?>
