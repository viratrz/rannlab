<?php

require_once('../../config.php');
require_once('lib.php');
require_login();

global $USER, $DB;

$title = 'CUE Summary';
$pagetitle = $title;
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standard');

?>
   <?php echo $OUTPUT->header(); ?>
<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <title>CUE Summary </title>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">    
   <style>
      .theme-table-wrap
      {
         margin-top: 0px !important;
      }
   </style>  
</head>

<body>
   <div class="border">
      <table class="table mb-0">
         <thead>
            <tr>
               <th scope="col" colspan="10" class="bg-dark"><h3 class="text-center text-white">Client Unit Enrolment Summary</h3></th>
            </tr>
         </thead>
         <thead>
            <tr>
               <td scope="col" colspan="2">Client Name:</td>
               <td colspan="6">Jose IREMAR DA SILVA FILHO</td>
               <td scope="col" colspan="1">Student ID:</td>
               <td>MIT01004U0</td>
            </tr>
         </thead>
         <thead>
            <tr>
               <th scope="col">Unit Code</th>
               <th scope="col">Unit Description</th>
            </tr>
         </thead>
         <thead>
            <tr>
               <th scope="col">Unit Code</th>
               <th scope="col">Unit Description</th>
               <th scope="col">Start Date</th>
               <th scope="col">End Date</th>
               <th scope="col">Trainer</th>
               <th scope="col">Final</th>
               <th scope="col">Theory</th>
               <th scope="col">Practical</th>
               <th scope="col">Result Code</th>
               <th scope="col">Comments</th>
            </tr>
         </thead>
         <tbody>
            
         </tbody>
      </table>
   </div>
</body>
</html>

<?php echo $OUTPUT->footer(); ?>