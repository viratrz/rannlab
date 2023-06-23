// <?php
// require_once(__DIR__ . '/../../config.php'); // assuming this file is in the root directory of your Moodle installation
// require_login(); // require the user to be logged in to access this page

// // retrieve the name of the school to filter the student list
// $school_name = optional_param('school_name', '', PARAM_TEXT);

// // construct the SQL query to retrieve the student list
// $sql = "SELECT firstname, lastname
//         FROM {user} u
//         INNER JOIN {school} s ON u.id = s.user_id
//         WHERE u.deleted = 0 AND u.suspended = 0 AND s.name = :school_name";

// // execute the SQL query with the school name parameter
// $students = $DB->get_records_sql($sql, ['school_name' => $school_name]);

// // display the student list as an unordered list
// if (!empty($students)) {
//     echo "<ul>";
//     foreach ($students as $student) {
//         echo "<li>{$student->firstname} {$student->lastname}</li>";
//     }
//     echo "</ul>";
// } else {
//     echo "No students found for the specified school.";
// }
