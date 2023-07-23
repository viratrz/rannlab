<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This is the core helpdesk library. This contains the building blocks of the
 * entire helpdesk.
 *
 * @package     block_helpdesk
 * @copyright   2010-2011 VLACS
 * @author      Jonathan Doane <jdoane@vlacs.org>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');

require_once("$CFG->dirroot/blocks/helpdesk/lib.php");

require_login(0, false);

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/helpdesk/search.php');

$nav = array (
    array (
        'name' => get_string('helpdesk', 'block_helpdesk'),
    ),
    array (
        'name' => get_string('search'),
    )
);
$hd             = helpdesk::get_helpdesk();
$cap            = helpdesk_is_capable();

$httpdata       = optional_param('sd', null, PARAM_RAW);
$count          = optional_param('count', 10, PARAM_INT);
$page           = optional_param('page', 0, PARAM_INT);

$defaultrel     = $hd->get_default_relation($cap);
$rel            = optional_param('rel', $defaultrel, PARAM_TEXT);

$form           = $hd->search_form();
$data           = $form->get_data();

// print_object($data);die;

if (!isset($data)) { $data = new stdClass; }
if(!helpdesk_is_capable(HELPDESK_CAP_ANSWER)) {
    $data->submitter = $USER->id;
}

$title = get_string('helpdesksearch', 'block_helpdesk');
//helpdesk_print_header($nav, $title);
helpdesk::page_init($title, $nav);
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('helpdesk', 'block_helpdesk'));


// Create ticket button
echo '<button id="create-ticket-btn">Create Ticket</button>';
echo '<script>
document.getElementById("create-ticket-btn").onclick = function() {
    window.location.href = "' . new moodle_url('/blocks/helpdesk/new.php') . '";
};
</script>';




// Do we have a relation to use? Lets us it!
if(!$form->is_submitted() and empty($httpdata)) {
    // TODO: Use search preset from database.
    // TODO: Implement all of this.
    if(is_numeric($rel)) {
        print_error("This feature has not been implemented.");
    }
    if(is_string($rel)) {
        $data = $hd->get_ticket_relation_search($rel);
        
        // we are forcefully violating the relationship thing and setting answerer to all 
        $data->answerer = -1;
        if(!$data) {
            print_error('relationnosearchpreset', 'block_helpdesk');
        }
        if($cap !== HELPDESK_CAP_ANSWER) {
            $user = helpdesk_get_user($USER->id);
            $data->watcher = $user->hd_userid;
        }
    }
} else {
    $rel = null;
}

// Do we have a special search? Lets use it instead of anything else!
if(!empty($httpdata)) {
    $data = unserialize(base64_decode($httpdata));
    // Override "rel" if it exists. This will make the menu option selectable
    // again
    $rel = null;
}

$fd = clone $data;
if (!empty($fd->status)) {
    $fd->status = implode(',', $fd->status);
}
unset($fd->submitter);
// print_object($fd);die;
$form->set_data($fd);

$options = $hd->get_ticket_relations($cap);
if ($options == false) {
    print_error('nocapabilities', 'block_helpdesk');
}

// At this point we have an $options with all the available ticket relation
// views available for the user's capability. We may want to write
// a function to handle this automatically incase we want these options to
// be dynamic. So we must view the options to the user, except for the
// current one. (which is already stored in $rel)

// We want to have links for all relations except for the current one.

// Let's use a table!
$relhelp = $OUTPUT->help_icon('relations', 'block_helpdesk');
$table = new html_table();
$table->head = array(get_string('changerelation', 'block_helpdesk') . $relhelp);
$table->data = array();

foreach ($options as $option) {
    // If we're using a relation, we want don't want to make it selectable, so
    // just set the text and move on to the next one.
    if ($rel == $option) {
        $table->data[] = array(get_string($option, 'block_helpdesk'));
        continue;
    }
    $url = new moodle_url("$CFG->wwwroot/blocks/helpdesk/search.php");
    $url->param('rel', $option);
    $url = $url->out();
    $table->data[] = array("<a href=\"$url\">" . get_string($option, 'block_helpdesk') . '</a>');
}

echo "<div id=\"ticketlistoptions\">
<div class=\"left2div\">";
$form->display();
echo "</div>";



// echo '<button onclick="redirectToPage(\'blocks/helpdesk/new.php\');">Create Ticket</button>';





//echo "<div class=\"right2div\">";
//echo html_writer::table($table);
//echo "</div></div>";

if ($form->is_validated() or !empty($httpdata) or $rel !== null) {
    $result = $hd->search($data, $count, $page);
    $tickets = $result->results;

    if (empty($result->count)) {
        echo $OUTPUT->notification(get_string('noticketstodisplay', 'block_helpdesk'));
    } else {
        // We want paging and page counts at the front AND back.
        $url = new moodle_url(qualified_me());
        $url->remove_params(); // We don't really care about what we don't have.
        if ($rel !== null) {
            $url->param('rel', $rel);
        } else {
            $url->param('sd', $result->httpdata);
        }
        if ($count != 10) { $url->param('count', $count); }
        if ($count > 10) {
            $pagingbar = new paging_bar($result->count, $page, $count, $url, 'page');
            echo $OUTPUT->render($pagingbar);
        }
        $qppstring = get_string('questionsperpage', 'block_helpdesk');
        $defaultcounts = array(10, 25, 50, 100, 250);
        $links = array();
        foreach ($defaultcounts as $d) {
            if ($result->count < $d) {
                continue;
            }
            $url->param('count', $d);
            $curl = $url->out();
            if ($count == $d) {
                $links[] = $d;
            } else {
                $links[] = "<a href=\"$curl\">$d</a>";
            }
        }

        // This is a table that will display generic information that any help
        // desk should have.
        $ticketnamestr = get_string('summary', 'block_helpdesk');
        $ticketstatusstr = get_string('status', 'block_helpdesk');
        $lastupdatedstr = get_string('lastupdated', 'block_helpdesk');
        $userstr = get_string('user');
        $table = new html_table();
        $head = array();
        $head[] = $ticketnamestr;
        $head[] = $userstr;
        $head[] = $ticketstatusstr;
        $head[] = $lastupdatedstr;
        $table->head = $head;

        foreach ($tickets as $ticket) {
            $user       = helpdesk_get_hd_user($ticket->get_hd_userid());
            $url        = new moodle_url("$CFG->wwwroot/blocks/helpdesk/view.php");
            $url->param('id', $ticket->get_idstring());
            $url        = $url->out();
            $row        = array();
            $row[]      = "<a href=\"$url\">" . $ticket->get_summary() . '</a>';
            $row[]      = helpdesk_user_link($user);
            $row[]      = $ticket->get_status_string();
            $row[]      = helpdesk_get_date_string($ticket->get_timemodified());
            $table->data[] = $row;
        }
        echo '<div class=\'questionlisttable\'>';
        echo html_writer::table($table);
        echo '</div>';
        $url = new moodle_url(qualified_me());
        $url->remove_params(); // We don't really care about what we don't have.
        if ($rel !== null) {
            $url->param('rel', $rel);
        } else {
            $url->param('sd', $result->httpdata);
        }
        if ($count != 10) { $url->param('count', $count); }
        $pagingbar = new paging_bar($result->count, $page, $count, $url, 'page');
        echo $OUTPUT->render($pagingbar);
        if ($result->count >= 25) {
            print "<p style=\"text-align: center;\">{$qppstring}: " . implode(', ', $links) . '</p>';
        }
    }
}

echo $OUTPUT->footer();


