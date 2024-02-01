<?php
      $insert = false;
      $update = false;
      $delete = false;

      $server = "localhost";
      $username = "root";
      $password = "root";
      $database = "Notes";
      //creating the connection
      $conn = mysqli_connect($server, $username, $password, $database, 3308);

      //Check forconection success
      if(!$conn){
          die("Sorry we failed to connect : ". mysqli_connect_error());
      }
  
      if(isset($_GET['delete'])){
        $sno = $_GET['delete'];
        $delete = true;
        $sql = "DELETE FROM `notes` WHERE `sno` = $sno";
        $result = mysqli_query($conn,$sql);
      }
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['snoEdit'])){
          //update the record
          $sno = $_POST["snoEdit"];
          $title = $_POST["titleEdit"];
          $description = $_POST["descriptionEdit"];

          $sql = "UPDATE `notes` SET `title`='$title' , `description`='$description' WHERE `notes`.`sno` = $sno";
          $result = mysqli_query($conn, $sql);
          if($result){
            $update = true;
          }
          else{
            echo "We could not update the note successfully!";
          }
        }
        else{
          $title = $_POST["title"];
          $description = $_POST["description"];

          $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$description')";
          $result = mysqli_query($conn, $sql);

          if($result){
            //echo "The record has been inserted successfully!<br> ";
            $insert = true;
          }
          else{
            echo "The record was not inserted due to ---> ". mysqli_error($conn);
          }
        }
      }
  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <title>iNotes - Notes taking made easy</title>
</head>

    <body>

      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
          <div class="container-fluid">
            <a class="navbar-brand" href="#">iNotes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">Contact Us</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </li>
              </ul>
              <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
              </form>
            </div>
          </div>
      </nav>
        <?php
          if($insert==true){
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success!</strong> Your note has been inserted successfully.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          }
        ?>
        <?php
          if($delete==true){
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success!</strong> Your note has been deleted successfully.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          }
        ?>
        <?php
          if($update==true){
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success!</strong> Your note has been updated successfully.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          }
        ?>
        <div class="container my-4">
          <h2>Add a Note</h2>
          <form action="/CRUD/index.php" method="POST">
              <div class="mb-3">
                <label for="title">Note Title</label>
                <input type="text" class="form-control" id="title" name="title" aria-descriptionribedby="emailHelp">
              </div>
              <div class="form-group">
                  <label for="description">Note description</label>
                  <textarea class="form-control" id="description" name="description" rows="10"></textarea>
              </div>
              <button type="submit" class="btn btn-primary my-3">Add Note</button>
          </form>
        </div>
        <div class="container my-4">
          <?php
            $sql = "SELECT * FROM `notes`";
            $result = mysqli_query($conn,$sql);
            while($row = mysqli_fetch_assoc($result)){
              echo $row['sno']. " Title ". $row['title']." description is ". $row['description'];
              echo "<br>";
            }
            echo "<br><br>";
          ?>
          <table class="table" id="myTable">
            <thead>
              <tr>
                <th scope="col">S.No.</th>
                <th scope="col">Title</th>
                <th scope="col">description</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $sql = "SELECT * FROM `notes`";
                $result = mysqli_query($conn,$sql);
                $sno = 0;
                while($row = mysqli_fetch_assoc($result)){
                  $sno = $sno+1;
                  echo "<tr>
                  <th scope='row'>".$sno."</th>
                  <td>".$row['title']."</td>
                  <td>".$row['description']."</td>
                  <td><button class='edit btn btn-sm btn-primary' id=".$row['sno'].">Edit</button> <button class='delete btn btn-sm btn-primary' id=d".$row['sno'].">Delete</button></td>
                </tr>";              
                }
              ?>
            </tbody>
          </table>
        </div>
        <hr>
      <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">Contact Us</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </li>
        <!-- Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Edit This Note</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="/CRUD/index.php" method="POST">
                <div class="modal-body">
                  <input type="hidden" name="snoEdit" id="snoEdit">
                  <div class="mb-3">
                    <label for="title">Note Title</label>
                    <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-descriptionribedby="emailHelp">
                </div>
                  <div class="form-group">
                      <label for="description">Note description</label>
                      <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="10"></textarea>
                  </div>
                </div>
                <div class="modal-footer d-block mr-auto">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        
        <!-- Include jQuery with updated integrity attribute -->
        <script
            src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
            crossorigin="anonymous"></script>
        
        <!-- Then include DataTables -->
        <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

        <script>
          $(document).ready(function() {
            $('#myTable').DataTable();
          });
        </script>
        <script>
          edits = document.getElementsByClassName('edit');
          Array.from(edits).forEach((element)=>{
          element.addEventListener("click", (e)=>{
              console.log("edit", );
              tr = e.target.parentNode.parentNode
              title = tr.getElementsByTagName("td")[0].innerText;
              description = tr.getElementsByTagName("td")[1].innerText;
              console.log(title, description);
              titleEdit.value = title;
              descriptionEdit.value = description;
              snoEdit.value = e.target.id;
              console.log(e.target.id);
              $('#editModal').modal('toggle');
            })
          })

          // Client-side validation for Add Note form
      $('#addNoteForm').submit(function(event) {
        var title = $('#title').val().trim();
        var description = $('#description').val().trim();

        if (!title || !description) {
          alert('Please fill in all fields');
          event.preventDefault();
          return;
        }

        if (title.length > 255) {
          alert('Title is too long. Maximum length is 255 characters.');
          event.preventDefault();
          return;
        }

        if (description.length > 1000) {
          alert('Description is too long. Maximum length is 1000 characters.');
          event.preventDefault();
        }
      });
//delete operation
          deletes = document.getElementsByClassName('delete');
          Array.from(deletes).forEach((element)=>{
          element.addEventListener("click", (e)=>{
              console.log("delete", );
              sno = e.target.id.substr(1,);
              if(confirm("Are you sure! You want to delete this note.")){
                console.log("yes");
                window.location = `/CRUD/index.php?delete=${sno}`; //security loophole
                //Create a form and use post request to submit a form
              }
              else{
                console.log("no");
              }
            })
          })
        </script>
      </body>
    </html>