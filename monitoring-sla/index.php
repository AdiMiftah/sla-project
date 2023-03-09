<?php
session_start();
include "db_conn.php";

if (!isset($_SESSION['username'])) {
   header("Location: login.php");
   exit();
}

// Query the latest data from the database
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM spv WHERE username='".$_SESSION['username']."'"));

// Function to upload the image file
function uploadImage($file) {
    // Check if the file was uploaded successfully
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Get the file extension
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

        // Generate a unique file name
        $newFileName = uniqid() . '.' . $extension;

        // Set the destination path
        $destination = 'images/layout_img/' . $newFileName;

        // Move the uploaded file to the destination
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $newFileName;
        }
    }
    // Handle errors
    return false;
}

if (isset($_POST['submit'])) {
   $file = $_FILES['image'];
   $newFileName = uploadImage($file);

   if ($newFileName) {
       // Prepare the SQL statement
       $sql = "UPDATE spv SET profile = ? WHERE username = ?";
       $stmt = mysqli_prepare($conn, $sql);

       // Bind the parameters
       mysqli_stmt_bind_param($stmt, 'ss', $newFileName, $_SESSION['username']);

       // Execute the statement
       if (mysqli_stmt_execute($stmt)) {
           $_SESSION['success'] = 'The image was uploaded and its information was saved to the database';
           header("Location: http://localhost/monitoring-sla/index.php");
           exit();
       } else {
           $_SESSION['error'] = 'There was an error saving the image information to the database';
           header("Location: index.php");
           exit();
       }
   } else {
       $_SESSION['error'] = 'There was an error uploading the image';
       header("Location: index.php");
       exit();
   }
}
if (isset($_POST['submit1'])) {
   // Get form data
   $file = $_FILES['image'];
   $newFileName = uploadImage($file);

   $nama = mysqli_real_escape_string($conn, $_POST['nama']); // define $nama variable
   $task = mysqli_real_escape_string($conn, $_POST['task']); // define $task variable
   $prog = mysqli_real_escape_string($conn, $_POST['prog']); // define $prog variable
   $area = mysqli_real_escape_string($conn, $_POST['area']); // define $prog variable
   $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']); // define $deskripsi variable
   $tngl = mysqli_real_escape_string($conn, $_POST['tngl']); // define $tngl variable

   // Check if data already exists in the table
   $query_cek = "SELECT * FROM task WHERE nama='$nama' AND task='$task' AND prog='$prog' AND tngl='$tngl' AND area='$area'";
   $result_cek = mysqli_query($conn, $query_cek);

   if (!$result_cek) {
      $_SESSION['error'] = 'There was an error while checking if the data already exists';
      header("Location: index.php");
      exit();
   }

   // If data exists, update it
   if (mysqli_num_rows($result_cek) > 0) {
       $row = mysqli_fetch_array($result_cek);
       $query_update = "UPDATE task SET deskripsi='$deskripsi'";
       if ($newFileName) {
           $filePath = 'images/layout_img/' . $newFileName;
           $query_update .= ", gambare='$filePath'";
           unlink('images/layout_img/' . $row['gambare']);
       }
       $query_update .= " WHERE id=".$row['id'];

       if (mysqli_query($conn, $query_update)) {
           $_SESSION['success'] = 'The data was updated successfully';
           header("Location: index.php");
           exit();
       } else {
           $_SESSION['error'] = 'There was an error while updating the data';
           header("Location: index.php");
           exit();
       }
   } else {
       // If data doesn't exist, insert it
       $query_insert = "INSERT INTO task (nama, task, prog, deskripsi, tngl, gambare, area) VALUES ('$nama', '$task', '$prog', '$deskripsi', '$tngl', '$newFileName','$area')";

       if (mysqli_query($conn, $query_insert)) {
           $_SESSION['success'] = 'The data was inserted successfully';
           header("Location: index.php");
           exit();
       } else {
           $_SESSION['error'] = 'There was an error while inserting the data';
           header("Location: index.php");
           exit();
       }
   }
}
?>

<!-- Display the username in an h6 element -->


<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>Monitoring SLA</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- site icon -->
      <link rel="icon" href="images/fevicon.png" type="image/png" />
      <!-- bootstrap css -->
      <link rel="stylesheet" href="css/bootstrap.min.css" />
      <!-- site css -->
      <link rel="stylesheet" href="style.css" />
      <!-- responsive css -->
      <link rel="stylesheet" href="css/responsive.css" />
      <!-- color css -->
      <link rel="stylesheet" href="css/colors.css" />
      <!-- select bootstrap -->
      <link rel="stylesheet" href="css/bootstrap-select.css" />
      <!-- scrollbar css -->
      <link rel="stylesheet" href="css/perfect-scrollbar.css" />
      <!-- custom css -->
      <link rel="stylesheet" href="css/custom.css" />
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
      <style>
         .login_form form .field textarea {
    border-top: none;
    border-left: none;
    border-right: none;
    border-bottom: solid #ddd 2px;
    width: 395px;
    float: right;
    padding: 10px;
    line-height: normal;
    font-weight: 300;
    transition: ease all 0.5s;
}
.login_form form .field select {
    border-top: none;
    border-left: none;
    border-right: none;
    border-bottom: solid #ddd 2px;
    width: 395px;
    float: right;
    padding: 10px;
    line-height: normal;
    font-weight: 300;
    transition: ease all 0.5s;
}
.login_form form .field radio {
    border-top: none;
    border-left: none;
    border-right: none;
    border-bottom: solid #ddd 2px;
    width: 395px;
    float: right;
    padding: 10px;
    line-height: normal;
    font-weight: 300;
    transition: ease all 0.5s;
}
#open-modal {
  padding: 10px 20px;
  font-size: 16px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}


/* Gaya CSS untuk modal */
.modal {
  display: none; /* sembunyikan modal secara default */
  position: fixed; /* posisi tetap saat scroll */
  z-index: 1; /* letakkan modal di atas konten lain */
  left: 0;
  top: 0;
  width: 100%; /* lebar modal sama dengan lebar layar */
  height: 100%; /* tinggi modal sama dengan tinggi layar */
  overflow: auto; /* aktifkan scroll jika konten modal melebihi layar */
  background-color: rgba(0, 0, 0, 0.4); /* transparansi hitam pada latar belakang */
}

/* Gaya CSS untuk konten modal */
.modal-content {
  background-color: #fff;
  margin: 10% auto; /* posisi konten modal */
  padding: 20px;
  border: none;
  border-radius: 5px;
  width: 80%; /* lebar konten modal */
  max-width: 600px; /* lebar maksimum konten modal */
}

/* Gaya CSS untuk tombol tutup */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

/* Gaya CSS untuk tampilan ponsel */
@media only screen and (max-width: 767px) {
  /* Gaya CSS untuk konten modal */
  .modal-content {
    margin: 20px;
    padding: 10px;
  }
}
      </style>
   </head>
   <body class="dashboard dashboard_1">
      <div class="full_container">
         <div class="inner_container">
            <!-- Sidebar  -->
 <!-- Sidebar  -->
 <nav id="sidebar">
   <div class="sidebar_blog_1">
      <div class="sidebar-header">
         <div class="logo_section">
            <a href="index.html"><img class="logo_icon img-responsive" src="images/logo/logo_black.png" alt="#" /></a>
         </div>
      </div>
      <div class="sidebar_user_info">
         <div class="icon_setting"></div>
         <div class="user_profle_side">
            <div class="user_img"><img class="img-responsive" src="images/layout_img/<?php echo $user['profile']; ?>" alt="#" /></div>
            <div class="user_info">
            <h6><?php echo $_SESSION['username']; ?></h6>
            <h6><?php echo $_SESSION['area']; ?></h6>
            <h6><?php echo $_SESSION['nrp']; ?></h6>
               <p><span class="online_animation"></span> Online</p>
            </div>
         </div>
      </div>
   </div>
   <div class="sidebar_blog_2">
      <h4>General</h4>
      <ul class="list-unstyled components">
         <li class="active">
            <a id="section1" ><i class="fa fa-dashboard yellow_color"></i> <span>Task</span></a>
         </li>
         <li><a id="section2" data-toggle="collapse" aria-expanded="false"><i class="fa fa-globe orange_color"></i> <span>Area</span></a></li>
         <li><a id="section3" data-toggle="collapse" aria-expanded="false"><i class="fa fa-clock-o orange_color"></i> <span>My Performance</span></a></li>
         </li>
      </ul>
   </div>
</nav>
<!-- end sidebar -->
            <!-- end sidebar -->
            <!-- right content -->
            <div id="content">
               <!-- topbar -->
               <div class="topbar">
                  <nav class="navbar navbar-expand-lg navbar-light">
                     <div class="full">
                        <button type="button" id="sidebarCollapse" class="sidebar_toggle"><i class="fa fa-bars"></i></button>
                        <div class="logo_section">
                           <a href="index.html"><img class="img-responsive" src="images/logo/logo.png" alt="#" /></a>
                        </div>
                        <div class="right_topbar">
                           <div class="icon_info">
                              <form action="logout.php">
                              <ul class="user_profile_dd">
                                 <li>
                                    <a class="dropdown-toggle" data-toggle="dropdown"><img class="img-responsive rounded-circle" src="images/layout_img/<?php echo $user['profile']; ?>" alt="#" /><span class="name_user"><?php echo $_SESSION['username']?></span></a>
                                    <div class="dropdown-menu">
                                       <a class="dropdown-item" id="section4">My Profile</a>
                                       <input type="submit" value="Logout"><span>Log Out</span> <i class="fa fa-sign-out"></i></a>
                                    </div>
                                 </li>
                              </ul>
                              </form>
                           </div>
                        </div>
                     </div>
                  </nav>
               </div>
               <!-- end topbar -->
                <!-- dashboard inner -->
                <section id="section11">
                <div class="midde_cont" >
                  <div class="container-fluid">
                     <div class="row column_title">
                        <div class="col-md-12">
                           <div class="page_title">
                              <h2>Task</h2>
                           </div>
                        </div>
                     </div>
                     <!-- row -->
                     <div class="row">
                        <!-- table section -->
                        <div class="col-md-12">
                           <div class="white_shd full margin_bottom_30">
                              <div class="full graph_head">
                                 <div class="heading1 margin_0">
                                    <h2>Daily activity</h2>
                                    <h2 style="text-align:left"><?php echo date('D-d-M-Y');?></h2>
                                 </div>
                              </div>
                              <div class="full inbox_inner_section">
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div class="full padding_infor_info">
                                          <div class="mail-box">
                                             <aside class="sm-side">
                                                <div class="user-head">
                                                   <a class="inbox-avatar" href="javascript:;">
                                                   <img  width="65" src="images/layout_img/<?php echo $user['profile']; ?>" alt="#" />
                                                   </a>
                                                   <div class="user-name">
                                                      <h5><a href="#"><?php echo $_SESSION['username']; ?></a></h5>
                                                      <h5><a><?php echo $_SESSION['nrp']; ?></a></h5>
                                                      <span><a href="#">Infotest@gmail.com</a></span>
                                                   </div>
                                                </div>
                                                <div class="inbox-body">
                                                   <a href="#myModal" data-toggle="modal" title="Compose" class="btn btn-compose">
                                                   Compose
                                                   </a>
                                                   <!-- Modal -->
                                                </div>
                                                <ul class="nav nav-pills nav-stacked labels-category inbox-divider">
                                                   <li>
                                                      <h4>Area</h4>
                                                   </li>
                                                   <li><a href="#"><i class="fa fa-circle"></i> MBUT</a></li>
                                                   <li><a href="#"><i class="fa fa-circle"></i> YKBUT</a></li>
                                                   <li><a href="#"><i class="fa fa-circle"></i> UTNB</a></li>
                                                   <li><a href="#"><i class="fa fa-circle"></i> KAMAJU</a></li>
                                                   <li><a href="#"><i class="fa fa-circle"></i> </a></li>
                                                </ul>
                                                <ul class="nav nav-pills nav-stacked labels-category">
                                                   <li>
                                                      <h4>Labels</h4>
                                                   </li>
                                                   <li>
                                                      <a href="#">
                                                         <i class="fa fa-circle"></i> Daily
                                                         
                                                      </a>
                                                   </li>
                                                   <li>
                                                      <a href="#">
                                                         <i class="fa fa-circle"></i> Weekly 
                                                         
                                                      </a>
                                                   </li>
                                
                                                </ul>
                                             </aside>
                                             <aside class="lg-side">
                                                <div class="inbox-head">
                                                   <h3>Inbox (10)</h3>
                                                   <form action="" method="post" class="pull-right position search_inbox">
                                                      <div class="input-append">
                                                      <input type="date" id="filter" name="filter" class="form-group">
                                                               <input type="submit" value="Filter" class="btn sr-btn"> 
                                                      </div>
                                                   </form>
                                                </div>
                                                <div class="inbox-body">
                                                   <div class="mail-option">
                                                      <div class="chk-all">
                                                         <div class="btn-group">
                                                            <a data-toggle="dropdown" href="#" class="btn mini all" aria-expanded="false"> All <i class="fa fa-angle-down "></i></a>
                                                            <ul class="dropdown-menu">
                                                               <li><a href="#"> None</a></li>
                                                               <li><a href="#"> Read</a></li>
                                                               <li><a href="#"> Unread</a></li>
                                                            </ul>
                                                         </div>
                                                      </div>
                                                      <div class="btn-group">
                                                         <a data-original-title="Refresh" data-placement="top" data-toggle="dropdown" href="#" class="btn mini tooltips">
                                                         <i class=" fa fa-refresh"></i>
                                                         </a>
                                                      </div>
                                                      <div class="btn-group hidden-phone">
                                                         <a data-toggle="dropdown" href="#" class="btn mini blue" aria-expanded="false">
                                                         More
                                                         <i class="fa fa-angle-down "></i>
                                                         </a>
                                                         <ul class="dropdown-menu">
                                                            <li><a href="#"><i class="fa fa-pencil"></i> Mark as Read</a></li>
                                                            <li><a href="#"><i class="fa fa-ban"></i> Spam</a></li>
                                                            <li class="divider"></li>
                                                            <li><a href="#"><i class="fa fa-trash-o"></i> Delete</a></li>
                                                         </ul>
                                                      </div>
                                                      <div class="btn-group">
                                                         <a data-toggle="dropdown" href="#" class="btn mini blue">
                                                         Move to
                                                         <i class="fa fa-angle-down "></i>
                                                         </a>
                                                         <ul class="dropdown-menu">
                                                            <li><a href="#"><i class="fa fa-pencil"></i> Mark as Read</a></li>
                                                            <li><a href="#"><i class="fa fa-ban"></i> Spam</a></li>
                                                            <li class="divider"></li>
                                                            <li><a href="#"><i class="fa fa-trash-o"></i> Delete</a></li>
                                                         </ul>
                                                      </div>
                                                      <button id="open-modal">Buka Modal</button>
                                                      <ul class="unstyled inbox-pagination">
                                                         <li><span>1-50 of 234</span></li>
                                                         <li>
                                                            <a class="np-btn" href="#"><i class="fa fa-angle-left  pagination-left"></i></a>
                                                         </li>
                                                         <li>
                                                            <a class="np-btn" href="#"><i class="fa fa-angle-right pagination-right"></i></a>
                                                         </li>
                                                      </ul>
                                                   </div>
                                                   <table class="table table-inbox table-hover">
                                                   <tbody>
    <?php
    $db = mysqli_connect("localhost", "root", "", "sla1");

    if (isset($_POST['filter'])) {
        $filter = $_POST['filter'];
        $area = $_SESSION['area'];
        $sql = "SELECT * FROM task WHERE tngl = '$filter' AND area = '$area'";
    } else {
        $area = $_SESSION['area'];
        $sql = "SELECT * FROM task WHERE area = '$area'";
    }

    $result = mysqli_query($db, $sql);

    while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr class="unread">
            <td class="inbox-small-cells">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                    <label class="custom-control-label" for="customCheck1"></label>
                </div>
            </td>
            <td class="inbox-small-cells"><i class="fa fa-star"></i></td>
            <td class="view-message dont-show"><?php echo htmlspecialchars($row['nama']); ?></td>
            <td class="view-message"><?php echo htmlspecialchars($row['task']); ?></td>
            <td class="project_progress">
                <div class="progress progress_sm">
                    <div class="progress-bar progress-bar-animated progress-bar-striped" role="progressbar" aria-valuenow="<?php echo htmlspecialchars($row['prog']); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo htmlspecialchars($row['prog']); ?>%;"></div>
                </div>
            </td>
            <td class="view-message"><?php echo htmlspecialchars($row['tngl']); ?></td>
        </tr>
    <?php } ?>
</tbody>
                                                   </table>

                                                </div>
                                             </aside>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </section>
                        <!-- table section -->
                        <!-- right content -->

               <!-- dashboard inner -->
               <section id="section22" >
               <div class="midde_cont">
                  <div class="container-fluid">
                     <div class="row column_title">
                        <div class="col-md-12">
                           <div class="page_title">
                              <h2>Media Gallery</h2>
                           </div>
                        </div>
                     </div>
                     <!-- row -->
                     <div class="row column4 graph">
                        <!-- Gallery section -->
                        <div class="col-md-12">
                           <div class="white_shd full margin_bottom_30">
                              <div class="full graph_head">
                                 <div class="heading1 margin_0">
                                    <h2>Media Gallery Design Elements</h2>
                                 </div>
                              </div>
                              <div class="full gallery_section_inner padding_infor_info">
                                 <div class="row">
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g1.jpg"><img class="img-responsive" src="images/layout_img/g1.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g2.jpg"><img class="img-responsive" src="images/layout_img/g2.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g3.jpg"><img class="img-responsive" src="images/layout_img/g3.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g4.jpg"><img class="img-responsive" src="images/layout_img/g4.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g5.jpg"><img class="img-responsive" src="images/layout_img/g5.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g6.jpg"><img class="img-responsive" src="images/layout_img/g6.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g7.jpg"><img class="img-responsive" src="images/layout_img/g7.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g8.jpg"><img class="img-responsive" src="images/layout_img/g8.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g9.jpg"><img class="img-responsive" src="images/layout_img/g9.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g10.jpg"><img class="img-responsive" src="images/layout_img/g10.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g11.jpg"><img class="img-responsive" src="images/layout_img/g11.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g12.jpg"><img class="img-responsive" src="images/layout_img/g12.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g13.jpg"><img class="img-responsive" src="images/layout_img/g13.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g14.jpg"><img class="img-responsive" src="images/layout_img/g14.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g15.jpg"><img class="img-responsive" src="images/layout_img/g15.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g16.jpg"><img class="img-responsive" src="images/layout_img/g16.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g1.jpg"><img class="img-responsive" src="images/layout_img/g1.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g2.jpg"><img class="img-responsive" src="images/layout_img/g2.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g3.jpg"><img class="img-responsive" src="images/layout_img/g3.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 margin_bottom_30">
                                       <div class="column">
                                          <a data-fancybox="gallery" href="images/layout_img/g4.jpg"><img class="img-responsive" src="images/layout_img/g4.jpg" alt="#" /></a>
                                       </div>
                                       <div class="heading_section">
                                          <h4>Sed ut perspiciatis</h4>
                                       </div>
                                    </div>                                      
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            <section id="section33">
                <!-- dashboard inner -->
                <div class="midde_cont">
                  <div class="container-fluid">
                     <div class="row column_title">
                        <div class="col-md-12">
                           <div class="page_title">
                              <h2>Dashboard</h2>
                           </div>
                        </div>
                     </div>
                     <div class="row column1">
                        <div class="col-md-6 col-lg-3">
                           <div class="full counter_section margin_bottom_30">
                              <div class="couter_icon">
                                 <div> 
                                    <i class="fa fa-user yellow_color"></i>
                                 </div>
                              </div>
                              <div class="counter_no">
                                 <div>
                                    <p class="total_no">2500</p>
                                    <p class="head_couter">Welcome</p>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                           <div class="full counter_section margin_bottom_30">
                              <div class="couter_icon">
                                 <div> 
                                    <i class="fa fa-clock-o blue1_color"></i>
                                 </div>
                              </div>
                              <div class="counter_no">
                                 <div>
                                    <p class="total_no">123.50</p>
                                    <p class="head_couter">Average Time</p>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                           <div class="full counter_section margin_bottom_30">
                              <div class="couter_icon">
                                 <div> 
                                    <i class="fa fa-cloud-download green_color"></i>
                                 </div>
                              </div>
                              <div class="counter_no">
                                 <div>
                                    <p class="total_no">1,805</p>
                                    <p class="head_couter">Collections</p>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                           <div class="full counter_section margin_bottom_30">
                              <div class="couter_icon">
                                 <div> 
                                    <i class="fa fa-comments-o red_color"></i>
                                 </div>
                              </div>
                              <div class="counter_no">
                                 <div>
                                    <p class="total_no">54</p>
                                    <p class="head_couter">Comments</p>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row column1 social_media_section">
                        <div class="col-md-6 col-lg-3">
                           <div class="full socile_icons fb margin_bottom_30">
                              <div class="social_icon">
                                 <i class="fa fa-facebook"></i>
                              </div>
                              <div class="social_cont">
                                 <ul>
                                    <li>
                                       <span><strong>35k</strong></span>
                                       <span>Friends</span>
                                    </li>
                                    <li>
                                       <span><strong>128</strong></span>
                                       <span>Feeds</span>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                           <div class="full socile_icons tw margin_bottom_30">
                              <div class="social_icon">
                                 <i class="fa fa-twitter"></i>
                              </div>
                              <div class="social_cont">
                                 <ul>
                                    <li>
                                       <span><strong>584k</strong></span>
                                       <span>Followers</span>
                                    </li>
                                    <li>
                                       <span><strong>978</strong></span>
                                       <span>Tweets</span>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                           <div class="full socile_icons linked margin_bottom_30">
                              <div class="social_icon">
                                 <i class="fa fa-linkedin"></i>
                              </div>
                              <div class="social_cont">
                                 <ul>
                                    <li>
                                       <span><strong>758+</strong></span>
                                       <span>Contacts</span>
                                    </li>
                                    <li>
                                       <span><strong>365</strong></span>
                                       <span>Feeds</span>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                           <div class="full socile_icons google_p margin_bottom_30">
                              <div class="social_icon">
                                 <i class="fa fa-google-plus"></i>
                              </div>
                              <div class="social_cont">
                                 <ul>
                                    <li>
                                       <span><strong>450</strong></span>
                                       <span>Followers</span>
                                    </li>
                                    <li>
                                       <span><strong>57</strong></span>
                                       <span>Circles</span>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- graph -->
                     <div class="row column2 graph margin_bottom_30">
                        <div class="col-md-l2 col-lg-12">
                           <div class="white_shd full">
                              <div class="full graph_head">
                                 <div class="heading1 margin_0">
                                    <h2>Extra Area Chart</h2>
                                 </div>
                              </div>
                              <div class="full graph_revenue">
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div class="content">
                                          <div class="area_chart">
                                             <canvas height="120" id="canvas"></canvas>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- end graph -->
                     <div class="row column3">
                        <!-- testimonial -->
                        <div class="col-md-6">
                           <div class="dark_bg full margin_bottom_30">
                              <div class="full graph_head">
                                 <div class="heading1 margin_0">
                                    <h2>Testimonial</h2>
                                 </div>
                              </div>
                              <div class="full graph_revenue">
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div class="content testimonial">
                                          <div id="testimonial_slider" class="carousel slide" data-ride="carousel">
                                             <!-- Wrapper for carousel items -->
                                             <div class="carousel-inner">
                                                <div class="item carousel-item active">
                                                   <div class="img-box"><img src="images/layout_img/<?php echo $user['profile']; ?>" alt=""></div>
                                                   <p class="testimonial">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae..</p>
                                                   <p class="overview"><b>Michael Stuart</b>Seo Founder</p>
                                                </div>
                                                <div class="item carousel-item">
                                                   <div class="img-box"><img src="images/layout_img/<?php echo $user['profile']; ?>" alt=""></div>
                                                   <p class="testimonial">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae..</p>
                                                   <p class="overview"><b>Michael Stuart</b>Seo Founder</p>
                                                </div>
                                                <div class="item carousel-item">
                                                   <div class="img-box"><img src="images/layout_img/<?php echo $user['profile']; ?>" alt=""></div>
                                                   <p class="testimonial">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae..</p>
                                                   <p class="overview"><b>Michael Stuart</b>Seo Founder</p>
                                                </div>
                                             </div>
                                             <!-- Carousel controls -->
                                             <a class="carousel-control left carousel-control-prev" href="#testimonial_slider" data-slide="prev">
                                             <i class="fa fa-angle-left"></i>
                                             </a>
                                             <a class="carousel-control right carousel-control-next" href="#testimonial_slider" data-slide="next">
                                             <i class="fa fa-angle-right"></i>
                                             </a>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- end testimonial -->
                        <!-- progress bar -->
                        <div class="col-md-6">
                           <div class="white_shd full margin_bottom_30">
                              <div class="full graph_head">
                                 <div class="heading1 margin_0">
                                    <h2>Progress Bar</h2>
                                 </div>
                              </div>
                              <div class="full progress_bar_inner">
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div class="progress_bar">
                                          <!-- Skill Bars -->
                                          <span class="skill" style="width:73%;">Facebook <span class="info_valume">73%</span></span>                  
                                          <div class="progress skill-bar ">
                                             <div class="progress-bar progress-bar-animated progress-bar-striped" role="progressbar" aria-valuenow="73" aria-valuemin="0" aria-valuemax="100" style="width: 73%;">
                                             </div>
                                          </div>
                                          <span class="skill" style="width:62%;">Twitter <span class="info_valume">62%</span></span>   
                                          <div class="progress skill-bar">
                                             <div class="progress-bar progress-bar-animated progress-bar-striped" role="progressbar" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100" style="width: 62%;">
                                             </div>
                                          </div>
                                          <span class="skill" style="width:54%;">Instagram <span class="info_valume">54%</span></span>
                                          <div class="progress skill-bar">
                                             <div class="progress-bar progress-bar-animated progress-bar-striped" role="progressbar" aria-valuenow="54" aria-valuemin="0" aria-valuemax="100" style="width: 54%;">
                                             </div>
                                          </div>
                                          <span class="skill" style="width:82%;">Google plus <span class="info_valume">82%</span></span>
                                          <div class="progress skill-bar">
                                             <div class="progress-bar progress-bar-animated progress-bar-striped" role="progressbar" aria-valuenow="82" aria-valuemin="0" aria-valuemax="100" style="width: 82%;">
                                             </div>
                                          </div>
                                          <span class="skill" style="width:48%;">Other <span class="info_valume">48%</span></span>
                                          <div class="progress skill-bar">
                                             <div class="progress-bar progress-bar-animated progress-bar-striped" role="progressbar" aria-valuenow="48" aria-valuemin="0" aria-valuemax="100" style="width: 48%;">
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- end progress bar -->
                     </div>
                     <div class="row column4 graph">
                        <div class="col-md-6">
                           <div class="dash_blog">
                              <div class="dash_blog_inner">
                                 <div class="dash_head">
                                    <h3><span><i class="fa fa-calendar"></i> 6 July 2018</span><span class="plus_green_bt"><a href="#">+</a></span></h3>
                                 </div>
                                 <div class="list_cont">
                                    <p>Today Tasks for Ronney Jack</p>
                                 </div>
                                 <div class="task_list_main">
                                    <ul class="task_list">
                                       <li><a href="#">Meeting about plan for Admin Template 2018</a><br><strong>10:00 AM</strong></li>
                                       <li><a href="#">Create new task for Dashboard</a><br><strong>10:00 AM</strong></li>
                                       <li><a href="#">Meeting about plan for Admin Template 2018</a><br><strong>11:00 AM</strong></li>
                                       <li><a href="#">Create new task for Dashboard</a><br><strong>10:00 AM</strong></li>
                                       <li><a href="#">Meeting about plan for Admin Template 2018</a><br><strong>02:00 PM</strong></li>
                                    </ul>
                                 </div>
                                 <div class="read_more">
                                    <div class="center"><a class="main_bt read_bt" href="#">Read More</a></div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="dash_blog">
                              <div class="dash_blog_inner">
                                 <div class="dash_head">
                                    <h3><span><i class="fa fa-comments-o"></i> Updates</span><span class="plus_green_bt"><a href="#">+</a></span></h3>
                                 </div>
                                 <div class="list_cont">
                                    <p>User confirmation</p>
                                 </div>
                                 <div class="msg_list_main">
                                    <ul class="msg_list">
                                       <li>
                                          <span><img src="images/layout_img/<?php echo $user['profile']; ?>" class="img-responsive" alt="#" /></span>
                                          <span>
                                          <span class="name_user"><?php echo $_SESSION['username']?></span>
                                          <span class="msg_user">Sed ut perspiciatis unde omnis.</span>
                                          <span class="time_ago">12 min ago</span>
                                          </span>
                                       </li>
                                       <li>
                                          <span><img src="images/layout_img/<?php echo $user['profile']; ?>" class="img-responsive" alt="#" /></span>
                                          <span>
                                          <span class="name_user"><?php echo $_SESSION['username']?></span>
                                          <span class="msg_user">On the other hand, we denounce.</span>
                                          <span class="time_ago">12 min ago</span>
                                          </span>
                                       </li>
                                       <li>
                                          <span><img src="images/layout_img/<?php echo $user['profile']; ?>" class="img-responsive" alt="#" /></span>
                                          <span>
                                          <span class="name_user"><?php echo $_SESSION['username']?></span>
                                          <span class="msg_user">Sed ut perspiciatis unde omnis.</span>
                                          <span class="time_ago">12 min ago</span>
                                          </span>
                                       </li>
                                       <li>
                                          <span><img src="images/layout_img/<?php echo $user['profile']; ?>" class="img-responsive" alt="#" /></span>
                                          <span>
                                          <span class="name_user"><?php echo $_SESSION['username']?></span>
                                          <span class="msg_user">On the other hand, we denounce.</span>
                                          <span class="time_ago">12 min ago</span>
                                          </span>
                                       </li>
                                    </ul>
                                 </div>
                                 <div class="read_more">
                                    <div class="center"><a class="main_bt read_bt" href="#">Read More</a></div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
            </section>
            <section id="section44">
               <!-- dashboard inner -->
               <div class="midde_cont">
                  <div class="container-fluid">
                     <div class="row column_title">
                        <div class="col-md-12">
                           <div class="page_title">
                              <h2>Profile</h2>
                           </div>
                        </div>
                     </div>
                     <!-- row -->
                     <div class="row column1">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                           <div class="white_shd full margin_bottom_30">
                              <div class="full graph_head">
                                 <div class="heading1 margin_0">
                                    <h2>User profile</h2>
                                 </div>
                              </div>
                              <div class="full price_table padding_infor_info">
                                 <div class="row">
                                    <!-- user profile section --> 
                                    <!-- profile image -->
                                    <div class="col-lg-12">
                                       <div class="full dis_flex center_text">
                                       <div class="profile_img">
    <img width="180" id="profile-image" class="rounded-circle" src="images/layout_img/<?php echo $user['profile']; ?>" alt="Profile Image" />
</div>
                                          <div class="profile_contant">
                                             <div class="contact_inner">
                                                <p><strong>About: </strong>Office Boy</p>
                                                <ul class="list-unstyled">
                                                   <li><i class="fa fa-id-badge"></i>5012020</li>
                                                   <li><i class="fa fa-in"></i>Jakarta</li>
                                                   <li><i class="fa fa-address-card"></i>PT Mitra Bakti UT</li>
                                                   <li><i class="fa fa-address-book"></i><?php echo $_SESSION['username']?></li>
                                                   <form action="http://localhost/monitoring-sla/index.php" method="post" enctype="multipart/form-data">
                                                      <div class="row">
                                                         <div class="form-group mt-3">
                                                            <div class="form-group col-md-6 mt-3 mt-md-0">
              <label >image</label>
              <input class="form-control" type="file" name="image"/>
            </div> 
            <br></br> 
            <div class="text-center"><button type="submit" name="submit">Send Message</button></div>    
          </div>           
        </form>
                                                </ul>
                                             </div>
                                             <div class="user_progress_bar">
                                                <div class="progress_bar">
                                                   <!-- Skill Bars -->
                                                   <span class="skill" style="width:85%;">Rating Supervisor <span class="info_valume">70%</span></span>                   
                                                   <div class="progress skill-bar ">
                                                      <div class="progress-bar progress-bar-animated progress-bar-striped" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 85%;">
                                                      </div>
                                                   </div>
                                                   <!-- <span class="skill" style="width:78%;">Disiplin <span class="info_valume">78%</span></span>   
                                                   <div class="progress skill-bar">
                                                      <div class="progress-bar progress-bar-animated progress-bar-striped" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100" style="width: 78%;">
                                                      </div>
                                                   </div> -->
                                                   <span class="skill" style="width:47%;">Achievement SLA <span class="info_valume">47%</span></span>
                                                   <div class="progress skill-bar">
                                                      <div class="progress-bar progress-bar-animated progress-bar-striped" role="progressbar" aria-valuenow="54" aria-valuemin="0" aria-valuemax="100" style="width: 54%;">
                                                      </div>
                                                   </div>
                                                   <!-- <span class="skill" style="width:65%;">Ramah <span class="info_valume">65%</span></span>
                                                   <div class="progress skill-bar">
                                                      <div class="progress-bar progress-bar-animated progress-bar-striped" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                                                      </div>
                                                   </div> -->
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <!-- profile contant section -->
                                       <div class="full inner_elements margin_top_30">
                                          <div class="tab_style2">
                                             <div class="tabbar">
                                                <nav>
                                                   <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                      <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#recent_activity" role="tab" aria-selected="true">Recent Activity</a>
                                                      <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#project_worked" role="tab" aria-selected="false">Scope Area Kerja</a>
                                                      <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#profile_section" role="tab" aria-selected="false">Profile</a>
                                                   </div>
                                                </nav>
                                                <div class="tab-content" id="nav-tabContent">
                                                   <div class="tab-pane fade show active" id="recent_activity" role="tabpanel" aria-labelledby="nav-home-tab">
                                                      <div class="msg_list_main">
                                                         <ul class="msg_list">
                                                            <li>
                                                               <span><img src="images/layout_img/image (6).png" class="img-responsive" alt="#"></span>
                                                               <span>
                                                               <span class="name_user">Hasbiallah</span>
                                                               <span class="msg_user">Masih ada Noda tertinggal disudut ruangan</span>
                                                               <span class="time_ago">30 min ago</span>
                                                               </span>
                                                            </li>
                                                            <li>
                                                               <span><img src="images/layout_img/msg3.png" class="img-responsive" alt="#"></span>
                                                               <span>
                                                               <span class="name_user">Hasbiallah</span>
                                                               <span class="msg_user">Ruangan sudah wangi</span>
                                                               <span class="time_ago">15 min ago</span>
                                                               </span>
                                                            </li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <div class="tab-pane fade" id="project_worked" role="tabpanel" aria-labelledby="nav-profile-tab">
                                                      <p>Scope Area Kerja Meliputi Kantor PT Mitra Bakti UT
                                                      </p>
                                                   </div>
                                                   <div class="tab-pane fade" id="profile_section" role="tabpanel" aria-labelledby="nav-contact-tab">
                                                      <p>May Juniardi biasa disapa Mas May memulai karirnya sebagai Office Boy di PT Mitra Bakti UT sejak 2019
                                                      </p>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <!-- end user profile section -->
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-2"></div>
                        </div>
                        <!-- end row -->
                     </div>
                  </div>
                  <!-- end dashboard inner -->
            </section>
            <div id="modal" class="modal login_form">
	<div class="modal-content">
		<span class="close-btn ">&times;</span>
		<h2 style="text-align: center;">Daily task</h2>
		<br>
		<br>
		<form method="post" action="" enctype="multipart/form-data">
      <div class="field">
    <label for="nama">Nama:</label>
    <select id="nama" name="nama">
    <option value="">-- Pilih Salah Satu --</option>
    <?php
    $db = mysqli_connect("localhost", "root", "", "sla1");
    $area = $_SESSION['area'];
    $sql = "SELECT username FROM staff WHERE area = '$area'";
    $result = mysqli_query($db, $sql);
    while ($row1 = mysqli_fetch_assoc($result)) {
        echo '<option value="' . $row1['username'] . '">' . $row1['username'] . '</option>';
    }
    ?>
</select><br>

</div>

			<div class="field">
				<label for="task">Task:</label>
				<select id="task" name="task">
					<option value="">-- Pilih Salah Satu --</option>
					<option value="mengepel">Mengepel</option>
					<option value="membersikan jendela">Membersikan Jendela</option>
					<option value="membersihkan kamar mandi">Membersihkan Kamar Mandi</option>
				</select><br>
			</div>

			<div class="field">
				<label for="deskripsi">Deskripsi:</label>
				<textarea id="deskripsi" name="deskripsi" required></textarea><br>
			</div>
			<br>
         
         <div class="field">
				<label for="area">Area:</label>
				<input id="area" name="area" value="<?php echo $_SESSION['area']; ?>" readonly><br>
			</div>

			<div class="field">
				<label for="prog">Progress:</label>
				<select id="prog" name="prog">
					<option value="0">Open</option>
					<option value="100">Close</option>
				</select><br>
			</div>

			<div class="field">
				<label for="tngl">Tanggal:</label>
				<input id="tngl" name="tngl" required type="date"><br>
			</div>
         <div class="field">
            <label for="photo">photo:</label>
            <input id="photo" type="file" name="image">
         </div>

			<button type="submit" name="submit1" value="submit1">Submit</button>

		</form>
	</div>
</div>
		</div>
	</div>
            </div>
         </div>
      </div>
      
                  <!-- footer -->
                  <div class="container-fluid">
                     <div class="footer">
                        <p>Copyright © 2018 Designed by html.design. All rights reserved.</p>
                     </div>
                  </div>
               </div>
               <!-- end dashboard inner -->
               <!-- end dashboard inner -->
            </div>
         </div>
      </div>
      <!-- jQuery -->
      <script>
function updateTime() {
    var today = new Date();
    var jam = today.getHours();
    var menit = today.getMinutes();
    var detik = today.getSeconds();
    jam = checkTime(jam);
    menit = checkTime(menit);
    detik = checkTime(detik);
    document.getElementById("jam").innerHTML = jam + ":" + menit + ":" + detik;
    setTimeout(updateTime, 1000);
}
function checkTime(i) {
    if (i < 10) {i = "0" + i};  // menambahkan angka 0 di depan bilangan kurang dari 10
    return i;
}
updateTime();
</script>
      <script>
         var toggleButton = document.getElementById("section1");
         var hiddenSection = document.getElementById("section11");
         var hiddenSection1 = document.getElementById("section22");
         var hiddenSection2 = document.getElementById("section33");
         var hiddenSection3 = document.getElementById("section44");
         toggleButton.addEventListener("click", function() {
             hiddenSection.style.display = "block";
             hiddenSection1.style.display = "none";
             hiddenSection2.style.display = "none";
             hiddenSection3.style.display = "none";
            
         });
       </script>

      <script>
         var toggleButton = document.getElementById("section2");
         var hiddenSection = document.getElementById("section11");
         var hiddenSection1 = document.getElementById("section22");
         var hiddenSection2 = document.getElementById("section33");
         var hiddenSection3 = document.getElementById("section44");
         toggleButton.addEventListener("click", function() {
             hiddenSection.style.display = "none";
             hiddenSection1.style.display = "block";
             hiddenSection2.style.display = "none";
             hiddenSection3.style.display = "none";
         });
       </script>

<script>
   var toggleButton = document.getElementById("section3");
   var hiddenSection = document.getElementById("section11");
   var hiddenSection1 = document.getElementById("section22");
   var hiddenSection2 = document.getElementById("section33");
   var hiddenSection3 = document.getElementById("section44");
   toggleButton.addEventListener("click", function() {
       hiddenSection.style.display = "none";
       hiddenSection1.style.display = "none";
       hiddenSection2.style.display = "block";
       hiddenSection3.style.display = "none";
   });
 </script>
 <script>
   var toggleButton = document.getElementById("section4");
   var hiddenSection = document.getElementById("section11");
   var hiddenSection1 = document.getElementById("section22");
   var hiddenSection2 = document.getElementById("section33");
   var hiddenSection3 = document.getElementById("section44");
   toggleButton.addEventListener("click", function() {
       hiddenSection.style.display = "none";
       hiddenSection1.style.display = "none";
       hiddenSection2.style.display = "none";
       hiddenSection3.style.display = "block";
   });
 </script>
<script>
		// Dapatkan elemen-elemen yang diperlukan
		var modal = document.getElementById("modal");
		var openBtn = document.getElementById("open-modal");
		var closeBtn = document.getElementsByClassName("close-btn")[0];

		// Fungsi untuk membuka modal
		function openModal() {
			modal.style.display = "block";
		}

		// Fungsi untuk menutup modal
		function closeModal() {
			modal.style.display = "none";
		}

		// Ketika tombol "Buka Modal" di klik, tampilkan modal
		openBtn.onclick = function() {
			openModal();
		}

		// Ketika tombol "X" di klik, tutup modal
		closeBtn.onclick = function() {
			closeModal();
		}

		// Ketika pengguna mengklik di luar modal, tutup modal
		window.onclick = function(event) {
			if (event.target == modal) {
				closeModal();
			}
		}
	</script>

      <script src="js/jquery.min.js"></script>
      <script src="js/popper.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <!-- wow animation -->
      <script src="js/animate.js"></script>
      <!-- select country -->
      <script src="js/bootstrap-select.js"></script>
      <!-- owl carousel -->
      <script src="js/owl.carousel.js"></script> 
      <!-- chart js -->
      <script src="js/Chart.min.js"></script>
      <script src="js/Chart.bundle.min.js"></script>
      <script src="js/utils.js"></script>
      <script src="js/analyser.js"></script>
      <!-- nice scrollbar -->
      <script src="js/perfect-scrollbar.min.js"></script>
      <script>
         var ps = new PerfectScrollbar('#sidebar');
      </script>
      <!-- custom js -->
      <script src="js/custom.js"></script>
      <script src="js/chart_custom_style1.js"></script>
   </body>
</html>

