<!doctype html>
<html class="no-js" lang="zxx">

<?php include("head.php"); ?>
    <body>
        <?php include("header.php"); ?>
    <!-- main-area -->
    <main>

        <!-- breadcrumb-area -->
        <section class="breadcrumb-area d-flex  p-relative align-items-center" style="background-image:url('img/bg/bdrc-bg.png')">

            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-12 col-lg-12">
                        <div class="breadcrumb-wrap text-left">
                            <div class="breadcrumb-title">
                                <h2>Contact Us</h2>
                            </div>
                        </div>
                    </div>
                    <div class="breadcrumb-wrap2">

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                            </ol>
                        </nav>
                    </div>

                </div>
            </div>
        </section>
        <!-- breadcrumb-area-end -->

        <!-- services-area -->
        <section id="services" class="services-area pt-120 pb-90 fix">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title text-center mb-50 wow fadeInDown animated" data-animation="fadeInDown" data-delay=".4s">
                            <h5><i class="fal fa-graduation-cap"></i> Contact Info</h5>
                            <h2>
                                Get In Touch
                            </h2>

                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4">

                        <div class="services-box text-center">
                            <div class="services-icon">
                                <img src="img/bg/contact-icon01.png" alt="image">
                            </div>
                            <div class="services-content2">
                                <h5><a href="tel:+919423094685">+91 94230 94685</a></h5>
                                <p>Phone Support</p>
                            </div>
                        </div>


                    </div>
                    <div class="col-lg-4 col-md-4">

                        <div class="services-box text-center active">
                            <div class="services-icon">
                                <img src="img/bg/contact-icon02.png" alt="image">
                            </div>
                            <div class="services-content2">
                                <h5><a href="mailto:vidyalayajanata1@gmail.com">vidyalayajanata1@gmail.com</a></h5>
                                <p>Email Address</p>

                            </div>
                        </div>


                    </div>
                    <div class="col-lg-4 col-md-4">

                        <div class="services-box text-center">
                            <div class="services-icon">
                                <img src="img/bg/contact-icon03.png" alt="image">
                            </div>
                            <div class="services-content2">
                                <h5>AT.POST.KHOPOLI, TAL.KHALAPUR, DIST.RAIGAD, PIN.410203.</h5>
                                <p>School Address</p>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </section>
        <!-- services-area-end -->
        <!-- map-area-end -->
        <div class="map fix" style="background: #f5f5f5;">
            <div class="container-flud">

                <div class="row">
                    <div class="col-lg-12">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15109.07199935582!2d73.3452264!3d18.7862062!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be8076a8f15fd37%3A0x30ad08602dfe6d6d!2sJanata%20Vidyalaya!5e0!3m2!1sen!2sin!4v1682241553183!5m2!1sen!2sin"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <!-- map-area-end -->
        <!-- contact-area -->
        <section id="contact" class="contact-area after-none contact-bg pt-120 pb-120 p-relative fix" style="background: #e7f0f8;">

            <div class="container">

                <div class="row">


                    <div class="col-lg-12 order-2">
                        <div class="contact-bg">
                            <div class="section-title center-align text-center mb-50">
                                <h2>
                                    Custom Inqure Form
                                </h2>

                            </div>

                            <form action="mail.php" method="post" class="contact-form mt-30 text-center">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="contact-field p-relative c-name mb-30">
                                            <input type="text" id="firstn" name="firstn" placeholder="First Name" required>
                                            <i class="icon fal fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="contact-field p-relative c-subject mb-30">
                                            <input type="text" id="email" name="email" placeholder="Email" required>
                                            <i class="icon fal fa-envelope"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="contact-field p-relative c-subject mb-30">
                                            <input type="text" id="phone" name="phone" placeholder="Phone No." required>
                                            <i class="icon fal fa-phone"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="contact-field p-relative c-message mb-30">
                                            <textarea name="message" id="message" cols="30" rows="50" placeholder="Write comments"></textarea>
                                            <i class="icon fal fa-edit"></i>
                                        </div>
                                        <div class="slider-btn  text-center">
                                            <button class="btn ss-btn" data-animation="fadeInRight" data-delay=".8s">Make An Request <i class="fal fa-long-arrow-right"></i></button>
                                        </div>
                                    </div>
                                </div>

                            </form>

                        </div>

                    </div>
                </div>

            </div>

        </section>

    </main>
    <!-- main-area-end -->
    <?php include("footer.php"); ?>
</body>

</html>