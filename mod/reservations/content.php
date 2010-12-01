<?php

require_once('./experiment.php');

class Content {
  var $id;
  var $name;
  var $filepath;
  var $experiment_id;

  function Content($id, $name, $filepath, $experiment_id) {
    $this->id = $id;
    $this->name = (string) $name;
    $this->filepath = (string) $filepath;
    $this->experiment_id = $experiment_id;
  }

  static function find_by_id($id) {
    global $DB;
    $record = $DB->get_record("contents", array("id" => $id));
    return Content::db_obj_to_content($record);
  }

  static function db_obj_to_content($record) {
    return new Content($record->id, $record->name, $record->filepath, $record->experiment_id);
  }

  function experiment() {
    global $DB;
    $record = $DB->get_record("experiments", array("id" => $id));
    return Experiment::db_obj_to_experiment($record);
  }

  static function create($name, $filepath, $experiment_id) {
    global $DB;

    /* Dirty hack: Please read the explanation behind this in Laboratory::create. */
    $tmp = new object();
    $tmp->name = $name;
    $tmp->filepath = $filepath;
    $tmp->experiment_id = $experiment_id;
    $id = $DB->insert_record("contents", $tmp);
    return new Content($id, $name, $filepath, $experiment_id);
  }

  function update() {
    $DB->update_record('contents', $this);
  }
}

?>