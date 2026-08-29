<!doctype html>
<html class="no-js" lang="zxx">
<?php
$servername = "localhost";
$username = "u768414476_school";
$password = "School@123";
$dbname = "u768414476_school";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} 
$xi_waiting_query = "SELECT * FROM temp_xi_waiting";
$xi_waiting_result = $conn->query($xi_waiting_query);
?>

<?php include "head.php"; ?>

<body>
    <?php include "header.php"; ?>
    <style>
        @media (max-width: 767px) {
            .section-title h4 {
                text-align: center;
            }
        }
    </style>
    <!-- main-area -->
    <main>

        <!-- breadcrumb-area -->
        <section class="breadcrumb-area d-flex  p-relative align-items-center" style="background-image:url('img/bg/bdrc-bg.png')">

            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-12 col-lg-12">
                        <div class="breadcrumb-wrap text-left">
                            <div class="breadcrumb-title">
                                <h2>Waiting LIST XI 2023-2024</h2>
                            </div>
                        </div>
                    </div>
                    <div class="breadcrumb-wrap2">

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Waiting LIST XI 2023-2024</li>
                            </ol>
                        </nav>
                    </div>

                </div>
            </div>
        </section>
        <!-- breadcrumb-area-end -->
        <!-- Project Detail -->
        <section class="project-detail">
            <div class="container">
                <!-- Lower Content -->
                <div class="lower-content">
                    <div class="row">
                        <div class="text-column col-lg-12 col-md-12 col-sm-12">

                            <div class="inner-column">
                                <h4 class="text-center">K.T.S.P. MANDAL'S</h4>
                                <h3 class="text-center">JANATA VIDHYALAYA JR.COLLEGE KHOPOLI TAL-KHALAPUR, DIST-RAIGAD</h3>
                                <h3 class="text-center">Waiting LIST XI 2023-2024</h3>
                                <!-- <div class="button-group text-center filter-button-group ">
                                    <button class="active">XI Plain Science</button>
                                     <button class="">XI IT Science</button>
                                    <button class="">XI Electronics Science</button>	
                                    <button class="">XI Plain English Commerce </button>
                                    <button class="">XI IT English Commerce</button>
                                    <button class="">XI Plain Marathi Commerce</button>
							</div> -->
                            </div>
                        </div>
                    
                    <!-- <div class="col-lg-12">
                         <aside class="sidebar-widget info-column">
                             <div class="inner-column3">
                                    <h3>Admission Open</h3>
                                    <ul class="project-info clearfix">
                                        <li>
                                            <div class="slider-btn">                                          
                                                 <a href="/xi_admission.php" class="btn ss-btn smoth-scroll">XI Admission <i class="fal fa-long-arrow-right"></i></a>				
                                                 			
                                            </div>
                                        </li>
                                        <li>
                                            <div class="slider-btn">                                          
                                                 <a href="/erp/signin/index" class="btn ss-btn smoth-scroll">XII Admission <i class="fal fa-long-arrow-right"></i></a>				
                                            </div>
                                        </li>
                                       
                                    </ul>
                                </div>
                       </aside>
                    
                      
                        
                    </div> -->
                    </div> 
                    <div class="row" class="xi_plain_science">
                        <div class="text-column col-lg-12 col-md-12 col-sm-12">

                            <div class="inner-column">
                                <h3 class="text-center">Waiting LIST XI Plain Science 2023-2024</h3>
                                <h4 class="text-center">XI 2023-2024</h4>
                                
                                    
                                    <?php
                                    if ($xi_waiting_result->num_rows > 0) {
                                        // output data of each row
                                        
                                        $arr = [];
                                        while($row = $xi_waiting_result->fetch_assoc()) {
                                            $arr[$row["classes"]][] = $row;
                                        }
                                        foreach($arr as $key => $rows) {
                                            //echo "<tr><td colspan=5 class='text-center'>".$key."</td></tr>";
                                            $i=1;
                                            ?>
                                            <h4 class="text-center"><?= $key ?></h4>
                                            <table class="table table-bordered mb-40">
                                                <thead>
                                                    <tr>
                                                        <th>SR.NO</th>
                                                        <th>APP.NO/REG. NO</th>
                                                        <th>STUDENT NAME</th>
                                                        <th>TOTAL</th>
                                                        <th>CATEGORY</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                            <?php
                                            foreach($rows as $k => $row) {
                                                echo "<tr><td>".$i++."</td><td>".$row["reg_no"]."</td><td>".$row["name"]."</td><td>".$row["total"]."</td><td>".$row["category"]."</td></tr>";
                                            }
                                            ?>
                                                </tbody>
                                            </table>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End Project Detail -->
    </main>
    <!-- main-area-end -->

    <?php include("footer.php"); ?>
</body>

</html>