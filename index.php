<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SCP CRUD Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body class="container">
      <?php include "connection.php"; ?>
      <div>
          <ul class="nav navbar-dark bg-dark">
              
              <?php foreach($result as $link): ?>
                <li class="nav-item active">
                  <a href="index.php?link=<?php echo $link['name']; ?>" class="nav-link text-light"><?php echo $link['name']; ?></a>
                </li>
              <?php endforeach; ?>
              
              <li class="nav-item active">
                  <a href="create.php" class="nav-link text-light">Create a new SCP record.</a>
            </li>
          </ul>
      </div>
    <h1>SCP CRUD Application</h1>
    <div>
        <?php 
        
            if(isset($_GET['link']))
            {
                $name = $_GET['link'];
                
                //prepared statement
                $stmt = $connection->prepare("select * from scp where name = ?");
                if(!$stmt)
                {
                    echo "<p>Error in preparing SQL statement</p>";
                    exit;
                }
                $stmt->bind_param("s", $name);
                
                if($stmt->execute())
                {
                    $result = $stmt->get_result();
                    
                    // check if a record has been retreived
                    if($result->num_rows > 0)
                    {
                        $array = array_map('htmlspecialchars', $result->fetch_assoc());
                        $update = "update.php?update=" . $array["id"];
                        $delete = "index.php?delete=" . $array["id"];
                        
                        echo "
                            <div class='card card-body shadow mb-3'>
                            <h2 class='card-title'>{$array['name']}</h2>
                            <h4>{$array['class']}</h4>
                            <p><img src='{$array['image']}' alt='{$array['name']}' class='img-fluid'></p>
                            <p>{$array['description']}</p>
                            <p>
                                <a href='{$update}' class='btn btn-info'> Update Record</a>
                                <a href='{$delete}' class='btn btn-warning'> Delete Record</a>
                            </p>
                            </div>
                        
                        ";
                    }
                    else
                    {
                       echo "<p>No record found for name: {$array['name']}</p>";


                    }
                }
                else
                {
                    echo "<p>Error executing the statement</p>";
                }
                
 
            }
            else
            {
                echo "
                    <p>Welcome to this CRUD application.</p>
                    <p><img src='images/logo.png' alt='SCP CRUD Application' class='img-fluid'></p>
                ";
            }
            
            //delete record
            if(isset($_GET['delete']))
            {
                $delID = $_GET['delete'];
                $delete = $connection->prepare("delete from scp where id =?");
                $delete->bind_param("i", $delID);
                
                if($delete->execute())
                {
                    echo "<div class='alert alert-warning'>Recorded Deleted...</div>";
                }
                else
                {
                      echo "<div class='alert alert-danger'>Error deleting record {$delete->error}.</div>";
                }
            }
        
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>