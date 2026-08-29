<style>
    .menu-badge {
	font-size: 9px;
	margin-left: 4px;
	position: relative;
	top: -10px;
	margin-bottom: 13px;
	color: #ffffff;
	background-color: #ff7350;
	padding: 3px 4px;
	border-radius: 3px;
}
</style>
<?php if($_SERVER['REQUEST_URI'] == "/index.php" || $_SERVER['REQUEST_URI'] == "/"){ ?>
<header class="header-area header-two">
<?php } else { ?>
<header class="header-area header-three">
<?php } ?>
        <div class="header-top second-header d-none d-md-block">
            <div class="container-fluid">
                <div class="row align-items-center">

                    <div class="col-lg-4 col-md-4 d-none d-lg-block ">
                        <div class="header-social">
                            <span>
                                    Follow us:-
                                    <a href="contactus.php#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                    <a href="contactus.php#" title="LinkedIn"><i class="fab fa-instagram"></i></a>               
                                    <a href="contactus.php#" title="Twitter"><i class="fab fa-twitter"></i></a>
                                    <a href="contactus.php#" title="Twitter"><i class="fab fa-youtube"></i></a>
                                   </span>
                            <!--  /social media icon redux -->
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 d-none d-lg-block text-right">
                        <div class="header-cta">
                            <ul>
                                <li>
                                    <div class="call-box">
                                        <div class="icon">
                                            <img src="img/icon/phone-call.png" alt="img">
                                        </div>
                                        <div class="text">
                                            <span>Call Now !</span>
                                            <strong><a href="tel:+919423094685">+91 94230 94685</a></strong>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="call-box">
                                        <div class="icon">
                                            <img src="img/icon/mailing.png" alt="img">
                                        </div>
                                        <div class="text">
                                            <span>Email Now</span>
                                            <strong><a href="mailto:vidyalayajanata1@gmail.com"> vidyalayajanata1@gmail.com  </a></strong>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div id="header-sticky" class="menu-area">
            <div class="container-fluid">
                <div class="second-menu">
                    <div class="row align-items-center">
                        <div class="col-xl-3 col-lg-3">
                            <div class="logo">
                                <a href="/index.php">
                                <?php if($_SERVER['REQUEST_URI'] == "/index.php" || $_SERVER['REQUEST_URI'] == "/"){ ?>
                                        <img src="img/logo/f_logo.png" alt="logo">
                                    <?php } else { ?>
                                        <img src="img/logo_1.png" alt="logo">
                                    <?php } ?>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6">

                            <div class="main-menu text-center text-xl-right">
                                <nav id="mobile-menu">
                                    <ul>
                                        <li class="has-sub">
                                            <a href="index.php">Home</a>
                                        </li>
                                        <li><a href="teams.php"> Teacher</a></li>
                                        <li><a href="prospectus.php"> Prospectus <span class="menu-badge badge-bounce">NEW</span></a></li>
                                        <!-- <li><a href="xi_admission_merit.php"> XI Merit List <span class="menu-badge badge-bounce">NEW</span></a></li> -->
                                        <li><a href="contactus.php">Contact</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 text-right d-none d-lg-block text-right text-xl-right">
                            <div class="login">
                                <ul>
                                    
                                    <li>
                                        <div class="second-header-btn">
                                            <a href="/erp/signin/index" class="btn">Admission</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mobile-menu"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header-end -->
    <!-- offcanvas-area -->
    <div class="offcanvas-menu">
        <span class="menu-close"><i class="fas fa-times"></i></span>
        <!-- <form role="search" method="get" id="searchform"   class="searchform" action="http://wordpress.zcube.in/xconsulta/">
          <input type="text" name="s" id="search" placeholder="Search"/>
          <button><i class="fa fa-search"></i></button>
        </form> -->
        <div id="cssmenu3" class="menu-one-page-menu-container">
            <ul class="menu">
                <li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="index.php">Home</a></li>
                <li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="teams.php">Teacher</a></li>
                <li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="prospectus.php">Prospectus <span class="menu-badge badge-bounce">NEW</span></a></li>
                <li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="contactus.php">Contact</a></li>
            </ul>
        </div>
        <div id="cssmenu2" class="menu-one-page-menu-container">
            <ul id="menu-one-page-menu-12" class="menu">
                <li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="index-3.php#home"><span>+91 904 916 7187</span></a></li>
                <li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="index-3.php#howitwork"><span>vidyalayajanata1@gmail.com</span></a></li>
            </ul>
        </div>
    </div>
    <div class="offcanvas-overly"></div>