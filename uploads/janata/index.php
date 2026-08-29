<!doctype html>
<html class="no-js" lang="zxx">

<?php include "head.php"; ?>

<body>
    <?php include "header.php"; ?>
    <style>
        @media (max-width: 767px) {
            .section-title h4 {
                text-align: center;
            }
        }
        .deal {
    background-color: #fff;
    background-position: 100%;
    background-repeat: no-repeat;
    background-size: contain;
    display: flex;
    flex-flow: row wrap;
    min-height:420px
}

.deal, .deal > div {
    width:100%
}

.deal h2 {
    color: var(--color-danger);
    font-size: 26px;
    font-weight: 600;
    line-height: 1.1;
    margin-bottom:5px
}

.deal h5 {
    color: var(--color-grey-2);
    font-weight: 400;
    letter-spacing: 0;
    margin-bottom: 20px;
    max-width:240px
}

.deal .deal-content {
    align-self:center
}

.deal .product-title {
    font-size: 45px;
    line-height: 1.23;
    margin-bottom: 20px;
    max-width:57%
}

.deal .product-title a {
    color:var(--color-grey-1)
}

/* .deal .btn {
    background: var(--color-brand);
    border: 0;
    border-radius: 4px;
    color: #fff;
    font-size: 14px;
    padding:10px 24px
}

.deal .btn i {
    font-size: 12px;
    margin-left: 5px;
    transition-duration:.3s
}

.deal .btn:hover i {
    margin-left: 10px;
    transition-duration:.3s
} */

.deal .deal-bottom {
    align-self:flex-end
}

.deal .deal-bottom .deals-countdown {
    margin-bottom: 20px;
    margin-left:-12px
}

.deal .deal-bottom .deals-countdown .countdown-section {
    border: 2px solid var(--color-brand);
    box-shadow:20px 20px 54px rgba(0, 0, 0, .03)
}
.custom-modal .modal-dialog {
    border: 0;
    border-radius: 0;
    max-width: 888px !important;
    overflow:hidden
}

.custom-modal .modal-dialog .modal-content {
    border: 1px solid rgba(var(--color-brand-rgb), .4);
    border-radius: 25px;
    padding:40px
}

.custom-modal .modal-dialog .btn-close {
    position: absolute;
    right: 30px;
    top: 30px;
    z-index:2
}


    </style>
    <!-- main-area -->
    <main>
        <!-- slider-area -->
        <section id="parallax" class="slider-area slider-three fix p-relative">
            <div class="slider-active">
                <div class="single-slider slider-bg d-flex align-items-center" style="background: url('img/slider/slider_img03.png') no-repeat; ">
                    <div class="container">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-lg-7 col-md-9">
                                <div class="slider-content s-slider-content pt-20 text-center">
                                    <h5 data-animation="fadeInUp" data-delay=".4s">Welcome To Janata Vidyalaya</h5>
                                    <h2 data-animation="fadeInUp" data-delay=".4s">Better <span>education</span> for Beautiful world. </h2>
                                    <p data-animation="fadeInUp" data-delay=".6s">To create a happy, secure and stimulating learning environment in which all the members of the school community grow in self- esteem and develop their potentials as human beings.</p>
                                    <div class="slider-btn mt-30 text-center">
                                        <a href="/prospectus.php" class="mt-1 btn ss-btn mr-15 animated fadeInLeft" data-animation="fadeInLeft" data-delay=".4s" tabindex="-1" style="animation-delay: 0.4s;">Prospectus<i class="fal fa-long-arrow-right"></i></a>
                                        <a href="/erp/signin/index" class="mt-1 btn ss-btn mr-15 animated fadeInLeft" data-animation="fadeInLeft" data-delay=".4s" tabindex="-1" style="animation-delay: 0.4s;">XI & XII Admission<i class="fal fa-long-arrow-right"></i></a>
                                        <a href="/xi_admission.php" class="mt-1 btn ss-btn mr-15 animated fadeInLeft" data-animation="fadeInLeft" data-delay=".4s" tabindex="-1" style="animation-delay: 0.4s;">XI Admission Form fill up <i class="fal fa-long-arrow-right"></i></a>
                                        <!-- <a href="/xi_admission_merit.php" class="mt-1 btn ss-btn mr-15 animated fadeInLeft" data-animation="fadeInLeft" data-delay=".4s" tabindex="-1" style="animation-delay: 0.4s;">XI Merit List <i class="fal fa-long-arrow-right"></i></a> -->
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>


        </section>
        <!-- slider-area-end -->
        <!-- service-area -->
        <section class="service-details-three p-relative fix">
            <div class="container">
                <div class="row sbox">

                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="services-box mb-30 text-center wow fadeInUp animated" data-animation="fadeInUp" data-delay=".4s">

                            <div class="sr-contner">
                                <div class="icon">
                                    <img src="img/icon/sve-icon7.png" alt="icon01">
                                </div>
                                <div class="text">
                                    <h5><a href="#">The Pursuit of Excellence</a></h5>
                                    <p>A commitment to strive continuously to improve ourselves and our systems with the aim of becoming the best in our field.</p>

                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="services-box mb-30 text-center wow fadeInUp animated" data-animation="fadeInUp" data-delay=".4s">
                            <div class="sr-contner">
                                <div class="icon">
                                    <img src="img/icon/sve-icon8.png" alt="icon01">
                                </div>
                                <div class="text">
                                    <h3><a href="#">Fairness</a></h3>
                                    <p>A commitment of objectivity and impartiality, to eam the trust and respect of society.</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="services-box mb-30 text-center wow fadeInUp animated" data-animation="fadeInUp" data-delay=".4s">
                            <div class="sr-contner">
                                <div class="icon">
                                    <img src="img/icon/sve-icon9.png" alt="icon01">
                                </div>
                                <div class="text">
                                    <h3><a href="#">Leadership</a></h3>
                                    <p>A commitment to lead responsibility and creatively in educational and research process.</p>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="services-box mb-30 text-center wow fadeInUp animated" data-animation="fadeInUp" data-delay=".4s">
                            <div class="sr-contner">
                                <div class="icon">
                                    <img src="img/icon/sve-icon10.png" alt="icon01">
                                </div>
                                <div class="text">
                                    <h5><a href="#">Integrity and Transparency</a></h5>
                                    <p>A commitment to be ethical, sincere and transparent in all activities and to treat all individuals with dignity and respect.</p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!-- service-details2-area-end -->
        <!-- about-area -->
        <section class="about-area about-p pt-90 pb-120 p-relative fix">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="s-about-img3 p-relative  wow fadeInLeft animated" data-animation="fadeInLeft" data-delay=".4s">
                            <img src="img/close-up-students-indoors.jpg" alt="img">
                        </div>

                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="about-content s-about-content pl-15 wow fadeInRight  animated" data-animation="fadeInRight" data-delay=".4s">
                            <div class="about-title second-title pb-25">
                                <h5><i class="fal fa-graduation-cap"></i> About Our Kids</h5>
                                <h2>We Are Junior College Since 48 Years Experience</h2>
                            </div>
                            <p class="txt-clr">Our Junior College was set up in 1975 It is the oldest & biggest Junior College of Raigad District. Last Academic Year we met the educational requirement of 2000 students. We have a highly qualified teaching staff of 56 and
                                good non-teaching 2 staff members.</p>
                            <p>The result have Engineers, Charted Accountants, Company Secretary, Advocates, Scientists, Teachers, been excellent and many of our past students are Doctors, entrepreneurs etc. and are occupying top position in various field
                                in India as well as abroad.</p>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- <section class="class-area pt-60 pb-120 p-relative fix">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="section-title text-center mb-50">
                            <h5>K.T.S.P Mandal Khopoli</h5>
                            

                        </div>
                    </div>
                </div>
                <div class="team-active class-scroll">
                    
                    <div class="class-item mb-30">
                        
                        <div class="class-img">
                            <div class="class-img-outer">
                                <a href="#"> <img src="img/chairman1.jpeg" alt="class image"></a>

                            </div>
                        </div>
                        
                        <ul class="schedule">
                            <li>
                                <span>,</span>
                                <span class="class-age">K.T.S.P.Mandal, Khopoli</span>
                            </li>
                            <li>
                                <span>Medium:</span>
                                <span class="class-size">English Medium</span>
                            </li>
                            <li>
                               <span>Class Size:</span>
                               <span class="class-size">28</span>
                            </li>
                            <li>
                               <span>Fee:</span>
                               <span class="class-size">$50</span>
                            </li>
                        </ul>
                        <div class="class-content">

                            <h4 class="title text-center"><a href="#">Shri.Sanjay H. Patil </a><h4>
                            <h5 class="text-center">Vice Chairman</h5>
                            <p class="text-center">K.T.S.P.Mandal, Khopoli</p>
                        </div>
                        
                    </div>

                    
                    <div class="class-item mb-30">

                        <div class="class-img">
                            <div class="class-img-outer">
                                <a href="#"> <img src="img/chairman2.jpeg" alt="class image"></a>

                            </div>
                        </div>

                        <ul class="schedule">
                            <li>
                               <span>Age:</span>
                               <span class="class-age">5-10 Years</span>
                            </li>
                            <li>
                                <span>Stream:</span>
                                <span class="class-size">Only Science Stream</span>
                            </li>
                            <li>
                               <span>Class Size:</span>
                               <span class="class-size">28</span>
                            </li>
                            <li>
                               <span>Fee:</span>
                               <span class="class-size">$50</span>
                            </li>
                        </ul>
                        <div class="class-content">
                            <h4 class="title text-center"><a href="#">Shri.Kishor B. Patil</a></h4>
                            <h5 class="text-center">Secretary</h5>
                            <p class="text-center">K.T.S.P.Mandal, Khopoli</p>
                            
                        </div>
                    </div>


                </div>
            </div>
        </section> -->
        <!-- class area start -->
        <!-- event-area -->
        <section class="about-area about-p pt-40 pb-40 p-relative fix" style="background: #eff7ff;">
                <div class="animations-02"><img src="img/bg/an-img-02.png" alt="contact-bg-an-01"></div>
                <div class="container">
                    <div class="row justify-content-center align-items-center">
                         <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="s-about-img p-relative  wow fadeInLeft  animated" data-animation="fadeInLeft" data-delay=".4s" style="visibility: visible; animation-name: fadeInLeft;">
                                <img src="img/Jangam.jpeg" alt="img" style="width:100%">   
                            </div>
                          
                        </div>
                        
					<div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="about-content s-about-content pl-15 wow fadeInRight   animated" data-animation="fadeInRight" data-delay=".4s" style="visibility: visible; animation-name: fadeInRight;">
                                <div class="about-title second-title pb-25">  
                                    <h5><i class="fal fa-graduation-cap"></i> From The Chairman's Desk</h5>
                                    <h2>Mr. Santosh G. Jangam</h2>         
                                    <h4>Chairman - K.T.S.P. Mandal, Khopoli.</h4>                          
                                </div>
                                   <p class="txt-clr">Dear Students,</p>
                                    <p>We are proud to offer top ranige in employment services such and asser payroll and benefits administrato managemen and asistance with global business range ployment employer  readings from religious texts or literature are also commonly inc compliance.</p>
                                    <p>"We cannot always build the future for our youth, but we can build our youth for the future".
                                - Franklin D. Roosevelt.</p>
                            <p>These words perfectly describe our aim at Janata Vidyalaya Junior College. Beyond providing sound education, we wish to provide our students a holistic learning experience for life. Our aim is to teach students to LEARN, not just STUDY. Hence, we strive to travel beyond the boundaries of mere books. We know that the future is abstract and unknown but the youth in our hands are real and can be moulded.</p>
                            <p>You are the nation builders and movers of technology. You are the agents of change. It is our fervent hope that the years you spend in our educational institution would enable you to equip with leadership and managerial skills. The knowledge that you will gain, the fine qualities that you will imbibe and the skills that you will learn to apply will be your major contribution to your parents, society and the nation.</p>
                            <p>We invest our trust in you. We bank all our efforts on you. We are aware that there are strong challenges to great efforts, but always remember, great effort bears the sweet fruit of success. "You don't have to be great to start, but you have to start to be great".</p>
                            <p>Best Wishes.<br />

                                Mr. S. G. Jangam<br />

                                Chairman<br />
                                K.T.S.P Mandal, Khopoli.</p>
                            </div>
                        </div>
                     
                    </div>
                </div>
            </section>

        <section class="steps-area pt-40 pb-40  p-relative" style="background-color: #032e3f;">
            <div class="animations-10"><img src="img/bg/an-img-10.png" alt="an-img-01"></div>
            <div class="container">

                <div class="row align-items-center">
                <div class="col-lg-6 col-md-12">
                        <div class="wow fadeInLeft  animated" data-animation="fadeInLeft" data-delay=".4s" style="visibility: visible; animation-name: fadeInLeft;">
                            <img src="img/chairman3.jpg" width="60%" alt="class image">
                        </div>

                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="section-title mb-35 wow fadeInDown  animated" data-animation="fadeInDown" data-delay=".4s" style="visibility: visible; animation-name: fadeInDown;">
                        <h5><i class="fal fa-graduation-cap"></i> From The Vice Chairman's Desk</h5>
                                    <h2>Shri.Sanjay H. Patil</h2>         
                                    <h4 style="color:#fff">Vice Chairman - K.T.S.P. Mandal, Khopoli.</h4>        

<p>I hope this message finds you all in good health and high spirits. As the Secretary of our esteemed school, I wanted to take a moment to address everyone and provide an update on recent developments and upcoming events.</p>

<p>Firstly, I would like to extend my heartfelt gratitude to all the students for their dedication and hard work throughout the academic year. Your enthusiasm for learning and active participation in various school activities have been truly commendable. Your achievements, both inside and outside the classroom, have made us proud as an institution.</p>

<p>To the parents, thank you for your continued support and trust in our school. Your involvement in your child's education plays a crucial role in their overall growth, and we are grateful for your cooperation in fostering a positive learning environment.</p>

<p>I would also like to express my appreciation to the dedicated staff members who work tirelessly to provide an excellent educational experience for our students. Your commitment, professionalism, and passion for teaching have made a lasting impact on the lives of our students.</p>

<p>Wishing you all success and happiness in the days to come.</p>
                            <p>Shri. Sanjay H. Patil<br />

Vice Chairman<br />
K.T.S.P Mandal, Khopoli.</p>
                        </div>
                    </div>
                   



                </div>

            </div>
        </section>

        <section class="about-area about-p pt-40 pb-40 p-relative fix" style="background: #eff7ff;">
                <div class="animations-02"><img src="img/bg/an-img-02.png" alt="contact-bg-an-01"></div>
                <div class="container">
                    <div class="row justify-content-center align-items-center">
                         <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="s-about-img p-relative  wow fadeInLeft  animated" data-animation="fadeInLeft" data-delay=".4s" style="visibility: visible; animation-name: fadeInLeft;">
                                <img src="img/chairman2.jpeg" alt="img" style="width:100%">   
                            </div>
                          
                        </div>
                        
					<div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="about-content s-about-content pl-15 wow fadeInRight   animated" data-animation="fadeInRight" data-delay=".4s" style="visibility: visible; animation-name: fadeInRight;">
                                <div class="about-title second-title pb-25">  
                                    
                                    <h5>From The Secretary's Desk</h5>
                            <h2>Shri. Kishor B. Patil</h2>
                            <h4>Secretary - K.T.S.P.Mandal, Khopoli</h4>
                            <p>Dear Students, Parents, and Staff,</p>                  
                                </div>
                                   <p class="txt-clr">Dear Students,</p>
                                    <p>We are proud to offer top ranige in employment services such and asser payroll and benefits administrato managemen and asistance with global business range ployment employer  readings from religious texts or literature are also commonly inc compliance.</p>
                                    <p>"We cannot always build the future for our youth, but we can build our youth for the future".
                                - Franklin D. Roosevelt.</p>
                            <p>These words perfectly describe our aim at Janata Vidyalaya Junior College. Beyond providing sound education, we wish to provide our students a holistic learning experience for life. Our aim is to teach students to LEARN, not just STUDY. Hence, we strive to travel beyond the boundaries of mere books. We know that the future is abstract and unknown but the youth in our hands are real and can be moulded.</p>
                            <p>You are the nation builders and movers of technology. You are the agents of change. It is our fervent hope that the years you spend in our educational institution would enable you to equip with leadership and managerial skills. The knowledge that you will gain, the fine qualities that you will imbibe and the skills that you will learn to apply will be your major contribution to your parents, society and the nation.</p>
                            <p>We invest our trust in you. We bank all our efforts on you. We are aware that there are strong challenges to great efforts, but always remember, great effort bears the sweet fruit of success. "You don't have to be great to start, but you have to start to be great".</p>
                            <p>Best Wishes.<br />
                            <p>Shri.Kishor B. Patil,<br />
                            Secretary<br />
                            K.T.S.P.Mandal,Khopoli</p>
                            </div>
                        </div>
                     
                    </div>
                </div>
            </section>

        <section class="steps-area p-relative" style="background-color: #032e3f;">
            <div class="animations-10"><img src="img/bg/an-img-10.png" alt="an-img-01"></div>
            <div class="container">

                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12">
                        <div class="section-title mb-35 wow fadeInDown  animated" data-animation="fadeInDown" data-delay=".4s" style="visibility: visible; animation-name: fadeInDown;">
                            <h5>From The Chairman's Desk</h5>
                            <h2>Mr. Dinesh R. Gurav</h2>
                            <h4 style="color:#fff">Chairman - Janata Vidyalaya Jr. College, Khopoli</h4>
                            <p>Dear Parents & Students,<br />
                                Gretings !!</p>

                            <p>Education is the basis of all progress. The entire purpose of education is not to restrict itself
                                for imparting bookish knowledge only but to inculcate humanitarianvalues such as wisdom,
                                compassion, courage, humility, integrity and reliability in my students.</p>

                            <p>Janata Junior College encourages students to learn about themselves
                                and their constantly changing environment, while at the same time offering
                                support and guidance as they practice decision-making and social skills.</p>

                            <p>I am confident that this college is the best place for your child. I welcome your active
                                interest and involvement in the progress and activities of your child. I look forward to your
                                continuous efforts.</p>

                            <p>“Success comes to those who work hard and stay with those, who don’t rest on the laurels of the past.”</p>

                            <p>Mr. D. R. Gurav<br />
                                Chairman<br />
                                JVJC - School Committee.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="step-img wow fadeInLeft  animated" data-animation="fadeInLeft" data-delay=".4s" style="visibility: visible; animation-name: fadeInLeft;">
                            <img src="img/dinesh.jpeg" alt="class image">
                        </div>

                    </div>



                </div>

            </div>
        </section>

        <section class="about-area about-p pt-120 pb-120 p-relative fix" style="background: #eff7ff;">
            <div class="animations-02"><img src="img/bg/an-img-02.png" alt="contact-bg-an-01"></div>
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="s-about-img p-relative  wow fadeInLeft  animated" data-animation="fadeInLeft" data-delay=".4s" style="visibility: visible; animation-name: fadeInLeft;">
                            <img src="img/prici.jpeg" alt="img">
                        </div>

                    </div>

                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="about-content s-about-content pl-15 wow fadeInRight   animated" data-animation="fadeInRight" data-delay=".4s" style="visibility: visible; animation-name: fadeInRight;">
                            <div class="about-title second-title pb-25">
                                <h5><i class="fal fa-graduation-cap"></i> From the Principal's Desk</h5>
                                <h2>Mrs. Disha Rane</h2>
                            </div>
                            <p class="txt-clr">It gives me a great pleasure in welcoming you to Janata Vidyalaya Junior College, Khopoli. We believe in providing education that ignites students passion of learning and opens the doors to many opportunities to succeed in life. We aim to equip our students with a strong foundation in the knowledge, skills, values and attitudes for which the well need to take on the future challenges of the rapidly changing world. The college has emerged as a reputed brand with proven education trade record. College takes efforts to import quality education through traditional and innovative teaching - learning practices as well as inculcating good moral values. Our well qualified and experienced teaching faculty impart knowledge to students devotedly and whole heartedly. The defining quality of our college is its signature emphasis on enabling each and every individual to learn so they may lead and serve as committed individuals.</p>
                            <p class="txt-clr">We believe in 'Building Quality Students through Quality Education'.</p>
                            <p class="txt-clr">With warm regards and good wishes,</p>
                            <p class="txt-clr">Mrs. Disha Rane Principal<br />
                                Janata Vidyalaya Jr. College, Khopoli.</p>

                        </div>
                    </div>

                </div>
            </div>
        </section>
        <section class="steps-area p-relative" style="background-color: #032e3f;">
            <div class="animations-10"><img src="img/bg/an-img-10.png" alt="an-img-01"></div>
            <div class="container">

                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12">
                        <div class="section-title mb-35 mt-35 wow fadeInDown  animated" data-animation="fadeInDown" data-delay=".4s" style="visibility: visible; animation-name: fadeInDown;">
                            <h5>From the Vice Principal's Desk</h5>
                            <!--<h2>Mr. Santosh G. Jangam</h2>-->
                            <!--<h4 style="color:#fff">Chairman - K.T.S.P. Mandal, Khopoli.</h4>-->


                            <p class="txt-clr">Dear Students & Parents,</p>
                            <p class="txt-clr">An educational institute is the backbone of the society. K.T.S.P. Mandal is renowned institute in Raigad district. Since 1975, our Junior College has been achieving name and fame and this possible only because of your trust in teacher's and the management members of K.T.S.P. Mandals.</p>
                            <p class="txt-clr">Our well established Junior College gives its students quality based education and value oriented treatment. we have highly qualified and experienced staff who is always ready to impart knowledge to the students. The students are taught positive thinking and time management. Although our goal is to achieve success, we still emphasize on the all round development of our students. We have maintained the standard of our college and shall continue to do so. In this long way process your valuable co-operation is much solicited. We are trying to make our students responsible citizens of tomorrow.</p>
                            <p class="txt-clr">I, hereby humbly request you parents to give us a helping hand to build a responsible generation. Parents should be watchful of their ward's behaviour. Let's join our hands to create a healthy citizen in a developed nation.</p>
                            <p class="txt-clr">Thank you!</p>
                            <p class="txt-clr">Mr. M. R. Marathe&nbsp;&nbsp;&nbsp;&nbsp;Mr. G. G. Nalage</p>
                            <p class="txt-clr">Vice Principal's</p>
                            <p class="txt-clr">Janata Vidyalaya Junior College</p>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="s-about-img p-relative  wow fadeInLeft  animated" data-animation="fadeInLeft" data-delay=".4s" style="visibility: visible; animation-name: fadeInLeft;">
                            <img src="img/vice_princi.png" alt="img">
                        </div>

                    </div>



                </div>

            </div>
        </section>
        <!-- <section class="event event03 pt-120 pb-90 p-relative fix" style="background: #f7f9ff;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-12  wow fadeInUp animated" data-animation="fadeInUp" data-delay=".4s">
                        <div class="section-title center-align mb-50 wow fadeInDown animated" data-animation="fadeInDown" data-delay=".4s">
                            <h5><i class="fal fa-graduation-cap"></i> Video Tour</h5>
                            <h2>
                                School Video Tour
                            </h2>

                        </div>
                        <div class="s-video-wrap2" style="background-image:url('img/bg/video-img2.png')">
                            <div class="s-video-content text-center">
                                <h6>
                                    <a href="https://www.youtube.com/watch?v=7e90gBu4pas" class="popup-video mb-50"><img src="img/bg/play-button2.png" alt="circle_right"></a>
                                </h6>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12  wow fadeInUp animated" data-animation="fadeInUp" data-delay=".4s">
                        <div class="section-title center-align mb-50 wow fadeInDown animated" data-animation="fadeInDown" data-delay=".4s">
                            <h5><i class="fal fa-graduation-cap"></i> Our Events</h5>
                            <h2>
                                Upcoming Events
                            </h2>

                        </div>
                        <div class="event-item mb-20 hover-zoomin">
                            <div class="event-content">
                                <div class="icon"><i class="fal fa-calendar-alt"></i></div>
                                <div class="date"><strong>22</strong> March, 2023</div>
                                <div class="text">
                                    <h3><a href="#"> Summer High School Journalism Camp Registration Form</a></h3>
                                    <div class="time"><i class="fal fa-alarm-clock"></i> 3:30 pm - 4:30 pm <i class="fal fa-location"></i> <strong>United Kingdom</strong></div>
                                </div>

                            </div>
                        </div>
                        <div class="event-item mb-20 hover-zoomin">
                            <div class="event-content">
                                <div class="icon"><i class="fal fa-calendar-alt"></i></div>
                                <div class="date"><strong>22</strong> March, 2023</div>
                                <div class="text">
                                    <h3><a href="#">Event Summer High School Reunion Alumni Golf Tour</a></h3>
                                    <div class="time"><i class="fal fa-alarm-clock"></i> 3:30 pm - 4:30 pm <i class="fal fa-location"></i> <strong>United Kingdom</strong></div>
                                </div>

                            </div>
                        </div>
                        <div class="event-item mb-20 hover-zoomin">
                            <div class="event-content">
                                <div class="icon"><i class="fal fa-calendar-alt"></i></div>
                                <div class="date"><strong>22</strong> March, 2023</div>
                                <div class="text">
                                    <h3><a href="#">Event Summer High School Reunion Alumni Golf Tour</a></h3>
                                    <div class="time"><i class="fal fa-alarm-clock"></i> 3:30 pm - 4:30 pm <i class="fal fa-location"></i> <strong>United Kingdom</strong></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        -->
        <!-- event-area -->
        <!-- team-area -->
        <section class="team-area2 fix p-relative pt-120 pb-80">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 p-relative">
                        <div class="section-title center-align mb-50 text-center wow fadeInDown animated" data-animation="fadeInDown" data-delay=".4s">
                            <h5><i class="fal fa-graduation-cap"></i> Khalapur Taluka Shikshan Prasarak Mandal's</h5>
                            <h2>
                                School Committee Members
                            </h2>

                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="single-team mb-40">
                            <!-- <div class="team-thumb">
                                <div class="brd">
                                    <a href="#"><img src="img/team/team01.jpg" alt="img"></a>

                                </div>
                            </div> -->
                            <div class="team-info">
                                <h4><a href="#">Shri. Dinesh Ramakant Gurav</a></h4>
                                <p>Chairman,<br />School Committee, JVJC, Khopoli</p>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="single-team mb-40">
                            <!-- <div class="team-thumb">
                                <div class="brd">
                                    <a href="#"><img src="img/team/team02.jpg" alt="img"></a>
                                </div>
                            </div> -->
                            <div class="team-info">
                                <h4><a href="#">Shri. Santosh Gurunath Jangam</a></h4>
                                <p>Chairman,<br />School Committee, JVJC, Khopoli</p>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="single-team mb-40">
                            <!-- <div class="team-thumb">
                                <div class="brd">
                                    <a href="#"><img src="img/team/team03.jpg" alt="img"></a>
                                </div>

                            </div> -->
                            <div class="team-info">
                                <h4><a href="#">Shri. Sanjay Hiraman Patil</a></h4>
                                <p>Vice Chairman,<br />School Committee, JVJC, Khopoli</p>


                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="single-team mb-40">
                            <!-- <div class="team-thumb">
                                <div class="brd">
                                    <a href="#"><img src="img/team/team04.jpg" alt="img"></a>
                                </div>

                            </div> -->
                            <div class="team-info">
                                <h4><a href="#">Shri. Kishor Balkrishna Patil</a></h4>
                                <p>Secretary,<br />K.T.S.P. Mandal</p>


                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="single-team mb-40">
                            <!-- <div class="team-thumb">
                                <div class="brd">
                                    <a href="#"><img src="img/team/team03.jpg" alt="img"></a>
                                </div>

                            </div> -->
                            <div class="team-info">
                                <h4><a href="#">Shri. Abubakar Aadam Jalgaonkar</a></h4>
                                <p>Member,<br />School Committee, JVJC, Khopoli</p>


                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="single-team mb-40">
                            <!-- <div class="team-thumb">
                                <div class="brd">
                                    <a href="#"><img src="img/team/team03.jpg" alt="img"></a>
                                </div>

                            </div> -->
                            <div class="team-info">
                                <h4><a href="#">Shri. Jitendra Mulshankar Raval</a></h4>
                                <p>Member,<br />School Committee, JVJC, Khopoli</p>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="single-team mb-40">
                            <!-- <div class="team-thumb">
                                <div class="brd">
                                    <a href="#"><img src="img/team/team03.jpg" alt="img"></a>
                                </div>

                            </div> -->
                            <div class="team-info">
                                <h4><a href="#">Mrs. Disha Rane</a></h4>
                                <p>Ex-Officio Secretary/Principal,<br />JVJC School Committee, Khopoli</p>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="single-team mb-40">
                            <!-- <div class="team-thumb">
                                <div class="brd">
                                    <a href="#"><img src="img/team/team03.jpg" alt="img"></a>
                                </div>

                            </div> -->
                            <div class="team-info">
                                <h4><a href="#">Shri. Ganpat Gulab Nalage</a></h4>
                                <p>Vice Principal<br />JVJC School Committee, Khopoli</p>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="single-team mb-40">
                            <!-- <div class="team-thumb">
                                <div class="brd">
                                    <a href="#"><img src="img/team/team03.jpg" alt="img"></a>
                                </div>

                            </div> -->
                            <div class="team-info">
                                <h4><a href="#">Shri. Dilip Govind More</a></h4>
                                <p>Teaching Staff,<br />JVJC School Committee, Khopoli</p>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="single-team mb-40">
                            <!-- <div class="team-thumb">
                                <div class="brd">
                                    <a href="#"><img src="img/team/team03.jpg" alt="img"></a>
                                </div>

                            </div> -->
                            <div class="team-info">
                                <h4><a href="#">Shri. Kaluram Navshya Pawar</a></h4>
                                <p>Non-Teaching Staff Representative,<br />JVJC School Committee, Khopoli</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- team-area-end -->
        <!-- brand-area -->
        <!--<div class="brand-area pt-60 pb-60" style="background-color:#125875">-->
        <!--    <div class="container">-->
        <!--        <div class="row brand-active">-->
        <!--            <div class="col-xl-2">-->
        <!--                <div class="single-brand">-->
        <!--                    <img src="img/brand/b-logo1.png" alt="img">-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="col-xl-2">-->
        <!--                <div class="single-brand">-->
        <!--                     <img src="img/brand/b-logo2.png" alt="img">-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="col-xl-2">-->
        <!--                <div class="single-brand">-->
        <!--                     <img src="img/brand/b-logo3.png" alt="img">-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="col-xl-2">-->
        <!--                <div class="single-brand">-->
        <!--                      <img src="img/brand/b-logo4.png" alt="img">-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="col-xl-2">-->
        <!--                <div class="single-brand">-->
        <!--                     <img src="img/brand/b-logo5.png" alt="img">-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
        <!-- brand-area-end -->
        <!-- gallery-area -->
        <section id="portfolio" class="pt-60 pb-90">
            <div class="container">
                <div class="portfolio ">
                    <div class="row align-items-end mb-50">
                        <div class="col-lg-6">
                            <div class="section-title wow fadeInLeft  animated" data-animation="fadeInLeft" data-delay=".4s">
                                <h5><i class="fal fa-graduation-cap"></i> Our Gallery</h5>
                                <h2>
                                    Last Year We Have Completed Gallery School
                                </h2>

                            </div>

                        </div>
                        <!-- <div class="col-lg-6">
                            <div class="my-masonry text-right wow fadeInRight  animated" data-animation="fadeInRight" data-delay=".4s">
                                <div class="button-group filter-button-group ">
                                    <button class="active" data-filter="*">View All</button>
                                    <button data-filter=".financial">Financial</button>
                                    <button data-filter=".banking">Analyzing</button>
                                    <button data-filter=".insurance">Marketing </button>
                                    <button data-filter=".family">Business</button>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <div class="grid col3 wow fadeInUp  animated" data-animation="fadeInUp" data-delay=".4s">
                        <div class="grid-item financial">
                            <a href="#">
                                <figure class="gallery-image">
                                    <img src="img/gallery/1.jpg" alt="img" class="img">

                                </figure>
                            </a>
                        </div>
                        <div class="grid-item financial banking">
                            <a href="#">
                                <figure class="gallery-image">
                                    <img src="img/gallery/2.jpg" alt="img" class="img">
                                </figure>
                            </a>
                        </div>
                        <div class="grid-item insurance">
                            <a href="#">
                                <figure class="gallery-image">
                                    <img src="img/gallery/3.jpg" alt="img" class="img">
                                </figure>
                            </a>
                        </div>
                        <div class="grid-item family">
                            <a href="#">
                                <figure class="gallery-image">
                                    <img src="img/gallery/5.jpg" alt="img" class="img">
                                </figure>
                            </a>
                        </div>
                        <!-- <div class="grid-item business">
                            <a href="#">
                                <figure class="gallery-image">
                                    <img src="img/gallery/5.jpg" alt="img" class="img">
                                </figure>
                            </a>
                        </div>
                        <div class="grid-item financial">
                            <a href="#">
                                <figure class="gallery-image">
                                    <img src="img/gallery/protfolio-img06.jpg" alt="img" class="img">
                                </figure>
                            </a>
                        </div> -->
                    </div>

                </div>
            </div>
        </section>
       


<!-- The Modal -->
<div class="modal fade custom-modal show" id="myModal" tabindex="-1" aria-labelledby="onloadModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body">
                    <div class="deal" style="background-image: url('assets/imgs/banner/popup-1.png')">
                        <div class="deal-top">
                            <h6 class="mb-10 text-brand-2 text-center">इयत्ता 11 वी प्रवेशा बाबत महत्वाची सूचना</h6>
                        </div>
                        <div class="deal-content detail-info">
                            <!-- <p class="text-center"><strong>इयत्ता 11 वी प्रवेशा बाबत महत्वाची सूचना</strong></p> -->
                            <p>ज्या विद्यार्थ्यांचे नाव पहिल्या मेरिलिस्ट मध्ये आले होते तसेच वेटिंगलिस्ट मधील व नवीन ज्या विद्यार्थ्यांना डिव्हिजन ठरवून दिलेली आहे त्यांनी त्वरित फी भरून प्रवेश पक्का करणे अन्यथा प्रवेश रद्द करून वेटिंग मधील विद्यार्थ्यांना प्रवेश दिला जाईल. फी भरणे बाबत कोणतीही अडचण असेल तर प्राचार्य किंवा उपप्राचार्य यांना प्रत्यक्ष येऊन भेटणे.</p>
                            <!-- <p class="text-center"><strong>Important Notice Regarding Class 11 Admission</strong></p> -->
                            <!-- <p>Students whose name appeared in the first merit list as well as those in the waiting list and new students whose division has been alloted should immediately confirm the admission by paying the fees otherwise the admission will be canceled and the students in the waiting list will be admitted. If there is any problem regarding payment of fees, meet the Principal or Vice Principal in person.</p> -->
                        </div>
                        <div class="deal-top">
                            <h6 class="mb-10 text-brand-2 text-center">Important Notice Regarding Class 11 Admission</h6>
                        </div>
                        <div class="deal-content detail-info">
                            <p>Students whose name appeared in the first merit list as well as those in the waiting list and new students whose division has been alloted should immediately confirm the admission by paying the fees otherwise the admission will be canceled and the students in the waiting list will be admitted. If there is any problem regarding payment of fees, meet the Principal or Vice Principal in person.</p>
                        </div>
                        <div class="slider-btn mt-30 text-center">
                            <a href="/prospectus.php" class="mt-1 btn hover-up ss-btn mr-15 animated fadeInLeft" data-animation="fadeInLeft" data-delay=".4s" tabindex="-1" style="animation-delay: 0.4s;">Prospectus <i class="fal fa-long-arrow-right"></i></a>
                            <a href="/erp/signin/index" class="mt-1 btn hover-up ss-btn mr-15 animated fadeInLeft" data-animation="fadeInLeft" data-delay=".4s" tabindex="-1" style="animation-delay: 0.4s;">XI & XII Admission <i class="fal fa-long-arrow-right"></i></a>
                            <!-- <a href="https://nest.botble.com/products/chobani-complete-vanilla-greek" class="btn hover-up">Shop Now <i class="fi-rs-arrow-right"></i></a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="modal right" id="myModal1">
  <div class="modal-dialog modal-lg modal-side modal-bottom-right modal-notify modal-info">
    <div class="modal-content">

      <!-- Modal Header -->
      

      <!-- Modal body -->
      <div class="modal-body">
        
      <div class="modal-header">
        
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        
      </div>
      <div class="row">

          <div class="col-12">
            <p class="text-center"><strong>इयत्ता 11 वी प्रवेशा बाबत महत्वाची सूचना</strong></p>
            <p>ज्या विद्यार्थ्यांचे नाव पहिल्या मेरिलिस्ट मध्ये आले होते तसेच वेटिंगलिस्ट मधील व नवीन ज्या विद्यार्थ्यांना डिव्हिजन ठरवून दिलेली आहे त्यांनी त्वरित फी भरून प्रवेश पक्का करणे अन्यथा प्रवेश रद्द करून वेटिंग मधील विद्यार्थ्यांना प्रवेश दिला जाईल. फी भरणे बाबत कोणतीही अडचण असेल तर प्राचार्य किंवा उपप्राचार्य यांना प्रत्यक्ष येऊन भेटणे.</p>
            <p class="text-center"><strong>Important Notice Regarding Class 11 Admission</strong></p>
            <p>Students whose name appeared in the first merit list as well as those in the waiting list and new students whose division has been alloted should immediately confirm the admission by paying the fees otherwise the admission will be canceled and the students in the waiting list will be admitted. If there is any problem regarding payment of fees, meet the Principal or Vice Principal in person.</p>
            

          </div>
        </div>
      </div>

    </div>
  </div>
</div>
<!-- Button trigger modal-->
    </main>
    
    <!-- main-area-end -->

    <?php include("footer.php"); ?>
    <script type="text/javascript">
    $(window).on('load', function() {
        $('#myModal').modal('show');
    });
</script>
</body>

</html>