<?php
namespace local_dashboard;


defined('MOODLE_INTERNAL') || die();
require_once(dirname(__DIR__).'/lib.php');
class observers
{
    public static function createcat(\core\event\course_category_created $event){     
        if (empty($event)) 
        {
            return;
        }
        else{
            // create_category($event);
        }
    }
    public static function createcourse(\core\event\course_created $event2){    
        if (empty($event2)) 
        {
            return;
        }
        else
        {
            create_coursess($event2);
        }
    }
    public static function modulecreate(\core\event\course_module_created $event3){
        if (empty($event3)) 
        {
            return;
        }
        else
        {
            module_create($event3);
        }        
    }
      
}