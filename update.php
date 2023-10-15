<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SCP CRUD Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body class="container">
      <?php
      
            include "connection.php";
            
            if($_GET['update'])
            {
                $id = $_GET['update'];
                $recordID = $connection->prepare("select * from scp where id = ?");
                if(!$recordID)
                {
                    echo"<div class='alert alert-danger p-3 m-2'>Error preparing record for updating.</div>";
                    exit;
                }
                $recordID->bind_param("i", $id);
                if($recordID->execute())
                {
                    echo "<div class='alert alert-success p-3 m-2'>Record ready for update.</div>";
                    $temp = $recordID->get_result();
                    $row = $temp->fetch_assoc();
                }
                else
                {
                         echo"<div class='alert alert-danger p-3 m-2'>Error:{$recordID->error}</div>";
                }
            }
            
            if(isset($_POST['update']))
            {
                //write a prepared statement to update data
                $update = $connection->prepare("update scp set name=?, class=?, image=?, description=? where id=?");
                $update->bind_param("ssssi", $_POST['name'], $_POST['class'], $_POST['image'], $_POST['description'], $_POST['id']);
                
                if($update->execute())
                {
                    echo"
                        <div class='alert alert-success p-3'>Record successfully updated</div>
                    ";
                }
                else
                {
                    echo"
                        <div class='alert alert-danger p-3'>Error: {$update->error}</div>
                    ";
                }
            }
      
      ?>
    <h1>Update record.</h1>
    <p><a href="index.php" class="btn btn-dark">Back to index page.</a></p>
    <div class="p-3 bg-light border shadow">
        <form method="post" action="update.php" class="form-group">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <label>SCP Name:</label>
            <br>
            <input type="text" name="name" placeholder="Name..." class="form-control" required value="<?php echo $row['name']; ?>">
            <br><br>
            <label>Class:</label>
            <br>
            <input type="text" name="class" placeholder="Class..." class="form-control" value="<?php echo $row['class']; ?>">
            <br><br>
            <label>Image:</label>
            <br>
            <input type="text" name="image" placeholder="images/nameofimagepng..." class="form-control">
            <br><br>
            <label>Description:</label>
            <br>
            <textarea name="description" class="form-control"><?php echo $row['description']; ?></textarea>
            <br><br>
            <input type="submit" name="update" class="btn btn-primary">
            
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>