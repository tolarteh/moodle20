<?php

require_once(dirname(__FILE__).'/content.php');

class Experiment {
  var $id;
  var $name;
  var $html;
  var $laboratory_id;
  var $description;
  var $is_active;

  function Experiment($id, $description, $name, $html, $laboratory_id) {
    $this->id = $id;
    $this->name = (string) $name;
    $this->description = (string) $description;
    $this->html = (string) $html;
    $this->laboratory_id = $laboratory_id;
    $this->is_active = false;
  }

  static function db_obj_to_experiment($record) {
    return new Experiment($record->id, $record->name, $record->description, $record->html, $record->laboratory_id);
  }

  function activation_link() {
    if ($this->is_active)
      return '<a href="activate.php?id=' . $this->id . '">Desactivar</a>';
    else
      return '<a href="activate.php?id=' . $this->id . '">Activar</a>';
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

  static function create($name, $description, $html, $laboratory_id) {
    global $DB;

    /* Dirty hack: Please read the explanation behind this in Laboratory::create. */
    $tmp = new object();
    $tmp->name = $name;
    $tmp->html = $html;
    $tmp->description = $description;
    $tmp->laboratory_id = $laboratory_id;
    $id = $DB->insert_record("experiments", $tmp);
    return new Experiment($id, $name, $description, $html, $laboratory_id);
  }


  function update() {
    global $DB;
    return $DB->update_record('experiments', $this);
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