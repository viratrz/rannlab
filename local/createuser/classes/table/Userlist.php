<?php
namespace local_createuser\table;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/tablelib.php');

class Userlist extends \table_sql {

    public function __construct($userfrom,$select) {
        global $DB;
        parent::__construct('userlist_table');
        if($userfrom != 'course'){
            $this->countsql = 'SELECT 0';
            die();
        }
        list($insql, $insqlparam) = $DB->get_in_or_equal($select,SQL_PARAMS_NAMED);
        $fields = "DISTINCT ue.userid, u.username, u.firstname, u.lastname, u.email";
        $from = "{user_enrolments} ue LEFT JOIN {user} u on u.id=ue.userid";
        $where = "enrolid $insql";
        $this->set_sql($fields, $from, $where, $insqlparam);

        $columns = array(
            'username','firstname','lastname','email','action',
        );
        $headers = array(
            'User name','First Name','Last Name','Email-id','Action'
        );
        $this->define_columns($columns);
        $this->define_headers($headers);
        $this->sortable(false);
        $this->collapsible(false);
    }

    public function col_action($data){
        return "<a href='#' class='p-2' onclick=\"editUser($data->userid)\"><i class='fa fa-pencil' aria-hidden='true' title='Edit' style='color:#000;'></i></a>
                    <a href='#' onclick=\"deleteUser($data->userid)\" class='' style='padding:8px;' ><i class='fa fa-trash text-danger' title='Delete'  aria-hidden='true' style='color:#000;'></i></a>";
    }
}