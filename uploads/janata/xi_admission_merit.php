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
$xi_a_query = "SELECT * FROM temp_xi_a";
$xi_a_result = $conn->query($xi_a_query);
$xi_b_query = "SELECT * FROM temp_xi_b";
$xi_b_result = $conn->query($xi_b_query);
$xi_c_query = "SELECT * FROM temp_xi_c";
$xi_c_result = $conn->query($xi_c_query);
$xi_d_geo_query = "SELECT * FROM temp_xi_d_geo";
$xi_d_geo_result = $conn->query($xi_d_geo_query);
$xi_e_elec_query = "SELECT * FROM temp_xi_e_elec";
$xi_e_elec_result = $conn->query($xi_e_elec_query);
$xi_e_it_query = "SELECT * FROM temp_xi_e_it";
$xi_e_it_result = $conn->query($xi_e_it_query);
$xi_g_hindi_query = "SELECT * FROM temp_xi_g_hindi";
$xi_g_hindi_result = $conn->query($xi_g_hindi_query);
$xi_g_marathi_query = "SELECT * FROM temp_xi_g_marathi";
$xi_g_marathi_result = $conn->query($xi_g_marathi_query);
$xi_h_maths_query = "SELECT * FROM temp_xi_h_maths";
$xi_h_maths_result = $conn->query($xi_h_maths_query);
$xi_h_sp_query = "SELECT * FROM temp_xi_h_sp";
$xi_h_sp_result = $conn->query($xi_h_sp_query);
$xi_i_query = "SELECT * FROM temp_xi_i";
$xi_i_result = $conn->query($xi_i_query);
$xi_j_query = "SELECT * FROM temp_xi_j";
$xi_j_result = $conn->query($xi_j_query);
$xi_k_query = "SELECT * FROM temp_xi_k";
$xi_k_result = $conn->query($xi_k_query);
$xi_m_query = "SELECT * FROM temp_xi_m";
$xi_m_result = $conn->query($xi_m_query);
$xi_n_query = "SELECT * FROM temp_xi_n";
$xi_n_result = $conn->query($xi_n_query);
$xi_o_query = "SELECT * FROM temp_xi_o";
$xi_o_result = $conn->query($xi_o_query);
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
                                <h2>MERIT LIST XI 2023-2024</h2>
                            </div>
                        </div>
                    </div>
                    <div class="breadcrumb-wrap2">

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">MERIT LIST XI 2023-2024</li>
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
                                <h3 class="text-center">MERIT LIST XI 2023-2024</h3>


                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-9 mx-auto">
                            <div class="inner-column3">
                                <h4 class="text-center">Instruction</h4>
                                <p>All students are informed that Class 11 admissions are starting online from Tuesday 13th June 2023. The admission process will be completely online. The last date of entry will be 17th June.</p>
                                <ul>
                                    <li>1. First students to open the website www.janatavidyalaya.com.</li>
                                    <li>2. After opening the website, click on the merit list option to see whether your name is in the merit list or not.</li>
                                    <li>3. Students whose name is in the merit list should click on Admission and then click on Sign in option.</li>
                                    <li>4. After that you have to enter your registration number in place of username and in place of password you have to enter the mobile number which you have given in the form as password.</li>
                                    <li>5. After entering the registration number and password, your 11th form will appear.</li>
                                    <li>6.On the top right corner of the form you will see the amount of fee to be paid and there you will see the option Pay Now. Clicking on it.</li>
                                    <li>7. After clicking on Pay Now click on HDFC and click on Add Payment option there.</li>
                                    <li>8. After clicking on Add Payment, various options for payment of fees will appear eg. HDFC Debit Card, HDFC Credit Card, Other Bank Debit Card, UPI Payment. However, students have to choose the option of the facility available to them and pay the fee.</li>
                                    <li>9. After paying the fee, the students will see the message Payment Successful. You will also see the fee payment receipt. Print option will appear below the receipt. However, taking a printout of the receipt.</li>
                                    <li>10. Students have to submit the form along with the original living certificate and 1 xerox of marklist, 1 xerox of marklist, caste certificate, xerox of Aadhaar card and fee payment receipt along with the form after starting the college.</li>
                                    <li>11. To note that all are to be admitted within the allotted time.</li>
                                    <li>12. Those whose names are on waiting to inquire on Monday 19th June.</li>
                                </ul>
                                <h4 class="text-center">सूचना</h4>
                                <p>सर्व विद्यार्थ्यांना कळविण्यात येते की, इयत्ता 11 वी चे प्रवेश मंगळवार दिनांक 13 जून 2023 पासून ऑनलाईन सुरू होत आहेत. प्रवेश प्रक्रिया संपूर्णपणे ऑनलाईन असणार आहे. प्रवेशाची अंतिम तारीख 17 जून असणार आहे.</p>
                                <ul>
                                    <li>1. प्रथम विद्यार्थ्यांने www.janatavidyalaya.com ही वेबसाईट ओपन करणे.</li>
                                    <li>2. वेबसाईट ओपन केल्यावर मेरिट लिस्ट ह्या ऑप्शनवर क्लिक करुन आपले नाव मेरिटलिस्ट मध्ये आहे की नाही हे पाहणे.</li>
                                    <li>3. ज्या विद्यार्थ्यांचे नाव मेरिटलिस्ट मध्ये आहे त्यांनी Admission वर क्लिक करून Sign in ह्या ऑप्शन वर क्लिक करणे.</li>
                                    <li>4. त्यांनतर username च्या जागी तुमचा रजिस्ट्रेशन नंबर टाकायचा आहे आणि Password च्या जागी आपण फॉर्म मध्ये जे मोबाईल नंबर दिले आहेत ते पासवर्ड म्हणून टाकणेचा आहे.</li>
                                    <li>5. रजिस्ट्रेशन नंबर आणि पासवर्ड टाकल्यावर तुमचा 11 वी चा फॉर्म दिसेल. </li>
                                    <li>6.फॉर्म च्या वरती उजव्या कोपऱ्यात तुम्हाला भरावयाची फी ची रक्कम दिसेल आणि तिथेच Pay Now हा ऑप्शन दिसेल. त्यावर क्लिक करणे.</li>
                                    <li>7. Pay Now वर क्लिक केल्यावर HDFC ह्यावर क्लिक करणे आणि तिथेच Add Payment हा ऑप्शन दिसेल त्यावर क्लिक करणे.</li>
                                    <li>8. Add Payment वर क्लिक केल्यावर फी भरणेसाठी विविध पर्याय दिसतील उदा. HDFC Debit Card, HDFC Credit Card, Other Bank Debit Card, UPI Payment. तरी विद्यार्थ्यांनी त्यांच्याकडे असलेल्या सुविधेचा पर्याय निवडून फी भरणेची आहे.</li>
                                    <li>9.फी भरल्यावर विद्यार्थ्यांला Payment Successful असा मेसेज दिसेल. तसेच फी भरलेची पावती पण दिसेल. पावती खाली Print हा ऑप्शन दिसेल. तरी पावतीची प्रिंट काढणे.</li>
                                    <li>10. विद्यार्थ्यांनी कॉलेज चालू झाल्यावर फॉर्म सोबत ओरीजनल लिव्हिंग सर्टीफिकेट व 1 झेरॉक्स, मार्कलिस्टची 1 झेरॉक्स,जातीचा दाखला असेल तर, आधारकार्डची झेरॉक्स व फी भरल्याची पावती जोडून वर्गशिक्षकांकडे फॉर्म जमा करणेचा आहे. </li>
                                    <li>11. सगळ्यांनी दिलेल्या वेळेत प्रवेश घेणेचे आहेत याची नोंद घेणे.</li>
                                    <li>12.ज्यांची नावे waiting वरती आहेत त्यांनी सोमवार दिनांक 19 जून रोजी चौकशी करणे.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">
                            <aside class="sidebar-widget info-column">
                                <div class="inner-column3">
                                    <h3>XI Merit List</h3>
                                    <ul class="project-info clearfix">
                                        <li>
                                            <div class="slider-btn">
                                                <a href="#a_div" class="btn ss-btn smoth-scroll" style="padding: 8px 6px;">Plain Science XI A<i class="fal fa-long-arrow-right"></i></a>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="slider-btn">
                                                <a href="#b_div" class="btn ss-btn smoth-scroll" style="padding: 8px 6px;">Plain Science XI B<i class="fal fa-long-arrow-right"></i></a>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="slider-btn">
                                                <a href="#c_div" class="btn ss-btn smoth-scroll" style="padding: 8px 6px;">Plain Science XI C<i class="fal fa-long-arrow-right"></i></a>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="slider-btn">
                                                <a href="#d_geo_div" class="btn ss-btn smoth-scroll" style="padding: 8px 6px;">IT Science XI D<i class="fal fa-long-arrow-right"></i></a>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="slider-btn">
                                                <a href="#e_elec_div" class="btn ss-btn smoth-scroll" style="padding: 8px 6px;">Electronic/IT Science XI E (Electronic)<i class="fal fa-long-arrow-right"></i></a>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="slider-btn">
                                                <a href="#e_it_div" class="btn ss-btn smoth-scroll" style="padding: 8px 6px;">Electronic/IT Science XI E (IT)<i class="fal fa-long-arrow-right"></i></a>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="slider-btn">
                                                <a href="#g_hindi_div" class="btn ss-btn smoth-scroll" style="padding: 8px 6px;">English Commerce XI G (HINDI)<i class="fal fa-long-arrow-right"></i></a>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="slider-btn">
                                                <a href="#g_marathi_div" class="btn ss-btn smoth-scroll" style="padding: 8px 6px;">English Commerce XI G (Marathi)<i class="fal fa-long-arrow-right"></i></a>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="slider-btn">
                                                <a href="#h_sp_div" class="btn ss-btn smoth-scroll" style="padding: 8px 6px;">IT English Commerce XI H (SP)<i class="fal fa-long-arrow-right"></i></a>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="slider-btn">
                                                <a href="#h_maths_div" class="btn ss-btn smoth-scroll" style="padding: 8px 6px;">IT English Commerce XI H (Maths)<i class="fal fa-long-arrow-right"></i></a>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="slider-btn">
                                                <a href="#i_div" class="btn ss-btn smoth-scroll" style="padding: 8px 6px;">Marathi Commerce XI I<i class="fal fa-long-arrow-right"></i></a>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="slider-btn">
                                                <a href="#j_div" class="btn ss-btn smoth-scroll" style="padding: 8px 6px;">Marathi Commerce XI J<i class="fal fa-long-arrow-right"></i></a>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="slider-btn">
                                                <a href="#k_div" class="btn ss-btn smoth-scroll" style="padding: 8px 6px;">Marathi Commerce XI K<i class="fal fa-long-arrow-right"></i></a>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="slider-btn">
                                                <a href="#m_div" class="btn ss-btn smoth-scroll" style="padding: 8px 6px;">English Commerce XI M<i class="fal fa-long-arrow-right"></i></a>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="slider-btn">
                                                <a href="#n_div" class="btn ss-btn smoth-scroll" style="padding: 8px 6px;">English Commerce XI N<i class="fal fa-long-arrow-right"></i></a>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="slider-btn">
                                                <a href="#o_div" class="btn ss-btn smoth-scroll" style="padding: 8px 6px;">English Commerce XI O<i class="fal fa-long-arrow-right"></i></a>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="slider-btn">
                                                <a href="/xi_admission_waiting.php" class="btn ss-btn smoth-scroll" style="padding: 8px 6px;">Waiting List<i class="fal fa-long-arrow-right"></i></a>

                                            </div>
                                        </li>


                                    </ul>
                                </div>
                            </aside>



                        </div>
                        <div class="text-column col-lg-9 col-md-9 col-sm-12">

                            <div class="inner-column" id="a_div">
                                <h3 class="text-center">MERIT LIST XI Plain Science 2023-2024</h3>
                                <h4 class="text-center">XI A 2023-2024</h4>
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
                                        if ($xi_a_result->num_rows > 0) {
                                            // output data of each row
                                            $i = 1;
                                            while ($row = $xi_a_result->fetch_assoc()) {
                                                echo "<tr><td>" . $i++ . "</td><td>" . $row["reg_no"] . "</td><td>" . $row["student_name"] . "</td><td>" . $row["total"] . "</td><td>" . $row["category"] . "</td></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>


                            <div class="inner-column" id="b_div">
                                <h3 class="text-center">MERIT LIST XI Plain Science 2023-2024</h3>
                                <h3 class="text-center">XI B 2023-2024</h3>
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
                                        if ($xi_b_result->num_rows > 0) {
                                            // output data of each row
                                            $i = 1;
                                            while ($row = $xi_b_result->fetch_assoc()) {
                                                echo "<tr><td>" . $i++ . "</td><td>" . $row["reg_no"] . "</td><td>" . $row["student_name"] . "</td><td>" . $row["total"] . "</td><td>" . $row["category"] . "</td></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>


                            <div class="inner-column" id="c_div">
                                <h3 class="text-center">MERIT LIST Plain Science 2023-2024</h3>
                                <h4 class="text-center">XI C 2023-2024</h4>
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
                                        if ($xi_c_result->num_rows > 0) {
                                            // output data of each row
                                            $i = 1;
                                            while ($row = $xi_c_result->fetch_assoc()) {
                                                echo "<tr><td>" . $i++ . "</td><td>" . $row["reg_no"] . "</td><td>" . $row["student_name"] . "</td><td>" . $row["total"] . "</td><td>" . $row["category"] . "</td></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>


                            <div class="inner-column" id="d_geo_div">
                                <h3 class="text-center">MERIT LIST XI IT Science 2023-2024</h3>
                                <h4 class="text-center">XI D 2023-2024</h4>
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
                                        if ($xi_d_geo_result->num_rows > 0) {
                                            // output data of each row
                                            $i = 1;
                                            while ($row = $xi_d_geo_result->fetch_assoc()) {
                                                echo "<tr><td>" . $i++ . "</td><td>" . $row["reg_no"] . "</td><td>" . $row["student_name"] . "</td><td>" . $row["total"] . "</td><td>" . $row["category"] . "</td></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>


                            <div class="inner-column" id="e_elec_div">
                                <h3 class="text-center">MERIT LIST XI Electronic/IT Science 2023-2024</h3>
                                <h4 class="text-center">XI E 2023-2024</h4>
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
                                        if ($xi_e_elec_result->num_rows > 0) {
                                            // output data of each row
                                            $i = 1;
                                            while ($row = $xi_e_elec_result->fetch_assoc()) {
                                                echo "<tr><td>" . $i++ . "</td><td>" . $row["reg_no"] . "</td><td>" . $row["student_name"] . "</td><td>" . $row["total"] . "</td><td>" . $row["category"] . "</td></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>


                            <div class="inner-column" id="e_it_div">
                                <h3 class="text-center">MERIT LIST XI ELECTRONIC/IT Science 2023-2024</h3>
                                <h4 class="text-center">XI E 2023-2024</h4>
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
                                        if ($xi_e_it_result->num_rows > 0) {
                                            // output data of each row
                                            $i = 1;
                                            while ($row = $xi_e_it_result->fetch_assoc()) {
                                                echo "<tr><td>" . $i++ . "</td><td>" . $row["reg_no"] . "</td><td>" . $row["student_name"] . "</td><td>" . $row["total"] . "</td><td>" . $row["category"] . "</td></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>


                            <div class="inner-column" id="g_hindi_div">
                                <h3 class="text-center">MERIT LIST XI English Commerce(HINDI Subject) 2023-2024</h3>
                                <h4 class="text-center">XI G 2023-2024</h4>
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
                                        if ($xi_g_hindi_result->num_rows > 0) {
                                            // output data of each row
                                            $i = 1;
                                            while ($row = $xi_g_hindi_result->fetch_assoc()) {
                                                echo "<tr><td>" . $i++ . "</td><td>" . $row["reg_no"] . "</td><td>" . $row["student_name"] . "</td><td>" . $row["total"] . "</td><td>" . $row["category"] . "</td></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>


                            <div class="inner-column" id="g_marathi_div">
                                <h3 class="text-center">MERIT LIST XI English Commerce(Marathi Subject) 2023-2024</h3>
                                <h4 class="text-center">XI G 2023-2024</h4>
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
                                        if ($xi_g_marathi_result->num_rows > 0) {
                                            // output data of each row
                                            $i = 1;
                                            while ($row = $xi_g_marathi_result->fetch_assoc()) {
                                                echo "<tr><td>" . $i++ . "</td><td>" . $row["reg_no"] . "</td><td>" . $row["student_name"] . "</td><td>" . $row["total"] . "</td><td>" . $row["category"] . "</td></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>


                            <div class="inner-column" id="h_sp_div">
                                <h3 class="text-center">MERIT LIST XI IT English Commerce (SP)2023-2024</h3>
                                <h4 class="text-center">XI H 2023-2024</h4>
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
                                        if ($xi_h_sp_result->num_rows > 0) {
                                            // output data of each row
                                            $i = 1;
                                            while ($row = $xi_h_sp_result->fetch_assoc()) {
                                                echo "<tr><td>" . $i++ . "</td><td>" . $row["reg_no"] . "</td><td>" . $row["student_name"] . "</td><td>" . $row["total"] . "</td><td>" . $row["category"] . "</td></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>


                            <div class="inner-column" id="h_maths_div">
                                <h3 class="text-center">MERIT LIST XI IT English Commerce (Maths)2023-2024</h3>
                                <h4 class="text-center">XI H 2023-2024</h4>
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
                                        if ($xi_h_maths_result->num_rows > 0) {
                                            // output data of each row
                                            $i = 1;
                                            while ($row = $xi_h_maths_result->fetch_assoc()) {
                                                echo "<tr><td>" . $i++ . "</td><td>" . $row["reg_no"] . "</td><td>" . $row["student_name"] . "</td><td>" . $row["total"] . "</td><td>" . $row["category"] . "</td></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>


                            <div class="inner-column" id="i_div">
                                <h3 class="text-center">MERIT LIST XI Marathi Commerce 2023-2024</h3>
                                <h4 class="text-center">XI I 2023-2024</h4>
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
                                        if ($xi_i_result->num_rows > 0) {
                                            // output data of each row
                                            $i = 1;
                                            while ($row = $xi_i_result->fetch_assoc()) {
                                                echo "<tr><td>" . $i++ . "</td><td>" . $row["reg_no"] . "</td><td>" . $row["student_name"] . "</td><td>" . $row["total"] . "</td><td>" . $row["category"] . "</td></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>


                            <div class="inner-column" id="j_div">
                                <h3 class="text-center">MERIT LIST XI Marathi Commerce 2023-2024</h3>
                                <h4 class="text-center">XI J 2023-2024</h4>
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
                                        if ($xi_j_result->num_rows > 0) {
                                            // output data of each row
                                            $i = 1;
                                            while ($row = $xi_j_result->fetch_assoc()) {
                                                echo "<tr><td>" . $i++ . "</td><td>" . $row["reg_no"] . "</td><td>" . $row["student_name"] . "</td><td>" . $row["total"] . "</td><td>" . $row["category"] . "</td></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>


                            <div class="inner-column" id="k_div">
                                <h3 class="text-center">MERIT LIST XI Marathi Commerce 2023-2024</h3>
                                <h4 class="text-center">XI K 2023-2024</h4>
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
                                        if ($xi_k_result->num_rows > 0) {
                                            // output data of each row
                                            $i = 1;
                                            while ($row = $xi_k_result->fetch_assoc()) {
                                                echo "<tr><td>" . $i++ . "</td><td>" . $row["reg_no"] . "</td><td>" . $row["student_name"] . "</td><td>" . $row["total"] . "</td><td>" . $row["category"] . "</td></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>


                            <div class="inner-column" id="m_div">
                                <h3 class="text-center">MERIT LIST XI UNAIDED English Commerce 2023-2024</h3>
                                <h4 class="text-center">XI M 2023-2024</h4>
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
                                        if ($xi_m_result->num_rows > 0) {
                                            // output data of each row
                                            $i = 1;
                                            while ($row = $xi_m_result->fetch_assoc()) {
                                                echo "<tr><td>" . $i++ . "</td><td>" . $row["reg_no"] . "</td><td>" . $row["student_name"] . "</td><td>" . $row["total"] . "</td><td>" . $row["category"] . "</td></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>


                            <div class="inner-column" id="n_div">
                                <h3 class="text-center">MERIT LIST UNAIDED English Commerce 2023-2024</h3>
                                <h4 class="text-center">XI N 2023-2024</h4>
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
                                        if ($xi_n_result->num_rows > 0) {
                                            // output data of each row
                                            $i = 1;
                                            while ($row = $xi_n_result->fetch_assoc()) {
                                                echo "<tr><td>" . $i++ . "</td><td>" . $row["reg_no"] . "</td><td>" . $row["student_name"] . "</td><td>" . $row["total"] . "</td><td>" . $row["category"] . "</td></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>


                            <div class="inner-column" id="o_div">
                                <h3 class="text-center">MERIT LIST XI UNAIDED English Commerce 2023-2024</h3>
                                <h4 class="text-center">XI O 2023-2024</h4>
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
                                        if ($xi_o_result->num_rows > 0) {
                                            // output data of each row
                                            $i = 1;
                                            while ($row = $xi_o_result->fetch_assoc()) {
                                                echo "<tr><td>" . $i++ . "</td><td>" . $row["reg_no"] . "</td><td>" . $row["student_name"] . "</td><td>" . $row["total"] . "</td><td>" . $row["category"] . "</td></tr>";
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