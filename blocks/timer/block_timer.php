<?php

defined('MOODLE_INTERNAL') || die();

class block_timer extends block_base {

    /**
     * Init.
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_timer');
    }

    /**
     * Returns the contents.
     *
     * @return stdClass contents of block
     */
    public function get_content() {
        global $COURSE, $DB, $PAGE;

        if (isset($this->content)) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text .= html_writer::div(get_string('tcontent', 'block_timer'), 'status');

        if ($PAGE->cm && $PAGE->cm->modname === 'lesson') {
            $lessonid = $PAGE->cm->instance;
            $this->content->text .= html_writer::div("Lesson ID: <strong>{$lessonid}</strong>", 'lesson-id');
        }

        return $this->content;  
    }

    /**
     * Locations where block can be displayed.
     *
     * @return array
     */
    public function applicable_formats() {
        return array('mod-lesson' => true);
    }

    public function instance_allow_multiple() {
        return false;
    }

    public function instance_allow_config() {
        return false; // Prevent users from removing the block
    }
}
