<?php
    require_once('../../config.php');
    require_once('lib.php');
    require_login();

    global $USER, $DB;
    $all_packages = $DB->get_records_sql("SELECT * FROM {package}");
    $title = 'Package List';
    $pagetitle = $title;
    $PAGE->set_title($title);
    $PAGE->set_heading($title);
    $PAGE->set_pagelayout('standard');

?>
<?php echo $OUTPUT->header(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>Package List</title>
    <style>
        #table
        {
            *padding: 0 5% 0 5%;
        }
        #del_package
        {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>
    <span id="del_package" ></span>
    <div id="table">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Rate In Rupees</th>
                <th scope="col">Number of Users</th>
                <th scope="col">Number of Course</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($all_packages as $package){?>
            <tr id="del<?php echo $package->id; ?>">
                <td><?php echo $package->package_value; ?></td>
                <td><?php echo $package->num_of_user; ?></td>
                <td><?php echo $package->num_of_course; ?></td>
                <td><button class="btn bg-danger text-white px-1 py-0" onclick="deletePackage(<?php echo $package->id; ?>)">Delete</button></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<script>
    function deletePackage(package_id) 
   {
      // alert(package_id);
      del="#del"+package_id;
      if (confirm("Are You Sure Do You Want To Delete This Package?")) 
      {
        $.post(
         "<?php echo $CFG->wwwroot ?>" + "/local/createpackage/delete_package.php",
         {id:package_id},
         function(json) 
               {
                  $("#del_package").html(json);  
                  $('#del_package').css("padding","2px 5px");
                  $(del).fadeOut();              
               }
        );
      }
    
   }
</script>
<?php echo $OUTPUT->footer(); ?>