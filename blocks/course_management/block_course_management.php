<?php
class block_course_management extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_course_management');
    }
    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.

    public function get_content() {
    if ($this->content !== null) {
      return $this->content;
    }

    $this->content         =  new stdClass;
    $this->content->text   = '<a class="btn btn-secondary" href="http://192.168.2.251/course/management.php?categoryid=2&view=courses">Manage your courses</a><br>';
    
    $this->content->footer = '<br><a class="btn btn-secondary" href="http://192.168.2.251/blocks/course_management/module.php">Manage Modules</a>';
 
    return $this->content;
}
} 