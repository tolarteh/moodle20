<?php

require_once(dirname(__FILE__).'/experiment.php');

class Laboratory {
  var $id;
  var $name;
  var $description;

  function Laboratory($id, $name, $description) {
    $this->id = $id;
    $this->name = (string) $name;
    $this->description = (string) $description;
  }

  static function db_obj_to_laboratory($record) {
    return new Laboratory($record->id, $record->name, $record->description);
  }

  static function find_all() {
    global $DB;
    $labs = $DB->get_records("laboratories");
    return array_map("Laboratory::db_obj_to_laboratory", $labs);
  }

  function experiments() {
    global $DB;
    $sql = "SELECT * FROM  `mdl_experiments` WHERE `laboratory_id`=" . $this->id;
    $records = $DB->get_records_sql($sql);
    return array_map("Experiment::db_obj_to_experiment", $records);
  }

  static function find_by_id($id) {
    global $DB;
    $record = $DB->get_record("laboratories", array("id" => $id));
    return Laboratory::db_obj_to_laboratory($record);
  }

  static function create($name, $description) {
    global $DB;
    /* Dirty hack: we can't insert a Laboratory object in the first place
       since it contains an ID field. We'll create a tmp object, feed that
       into the DB and then get create a new Laboratory object with the ID
       returned by the insert statement. */
    $tmp = new object();
    $tmp->name = $name;
    $tmp->description = $description;
    $id = $DB->insert_record("laboratories", $tmp);
    return new Laboratory($id, $name, $description);
  }

  function update() {
    global $DB;
    $DB->update_record('laboratories', $this);
  }

  function delete(){
    global $DB;
    if ($DB->delete_records("laboratories", array("id" => $this->id)))
      return true;

    return false;
  }
}

?>