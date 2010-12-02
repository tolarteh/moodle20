<?php

require_once(dirname(__FILE__).'/experiment.php');

class Content {
  var $id;
  var $name;
  var $filepath;
  var $experiment_id;
  var $type;

  function Content($id, $name, $filepath, $type, $experiment_id) {
    $this->id = $id;
    $this->type = $type;
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
    return new Content($record->id, $record->name, $record->filepath, $record->type, $record->experiment_id);
  }

  function experiment() {
    global $DB;
    $record = $DB->get_record("experiments", array("id" => $id));
    return Experiment::db_obj_to_experiment($record);
  }

  static function create($name, $file, $experiment_id) {
    global $DB, $CFG;

    /* Dirty hack: Please read the explanation behind this in Laboratory::create. */
    $tmp = new object();
    $tmp->name = $name;
    $tmp->type = $file["type"];
    $tmp_location = $file["tmp_name"];

    /* /var/data/moodledata/SomeImportantFile_somefile.doc */
    $filepath = $CFG->dataroot . "/" . $name . "_" . basename($file["name"]);

    if(!move_uploaded_file($tmp_location, $filepath)) {
      die("Error subiendo el archivo");
    }

    $tmp->filepath = $filepath;
    $tmp->experiment_id = $experiment_id;
    $id = $DB->insert_record("contents", $tmp);
    return new Content($id, $name, $filepath, $type, $experiment_id);
  }

  function update() {
    $DB->update_record('contents', $this);
  }
}

?>