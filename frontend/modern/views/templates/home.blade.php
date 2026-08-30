@layout('views/layouts/master')

@section('content')

    @if(customCompute($sliders))
        <div class="pg-hero">
            <?php $i = 0; ?>
            @foreach($sliders as $slider)
                <div class="pg-hero-slide @if($i == 0) is-active @endif">
                    <img src="{{ base_url('uploads/gallery/'.$slider->file_name) }}" alt="">
                    <div class="pg-hero-overlay">
                        <div class="pg-container">
                            <div class="pg-hero-caption">
                                <span class="pg-eyebrow">{{ namesorting($backend->sname, 40) }}</span>
                                <h1>{{ sentenceMap(htmlspecialchars_decode($slider->file_title), 17, '<span>', '</span>') }}</h1>
                                <p>{{ htmlspecialchars_decode($slider->file_description) }}</p>
                                <div class="pg-hero-ctas">
                                    <a class="pg-btn pg-btn-primary" href="{{ base_url('frontend/page/contact') }}"><i class="fa fa-paper-plane"></i> Enquire Now</a>
                                    <a class="pg-btn pg-btn-invert" href="#about"><i class="fa fa-info-circle"></i> Learn More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $i++; ?>
            @endforeach

            <button type="button" class="pg-hero-arrow pg-hero-arrow--prev" aria-label="Previous slide"><i class="fa fa-angle-left"></i></button>
            <button type="button" class="pg-hero-arrow pg-hero-arrow--next" aria-label="Next slide"><i class="fa fa-angle-right"></i></button>
            <div class="pg-hero-dots"></div>
        </div>

        <div class="pg-quicklinks-wrap">
            <div class="pg-container">
                <div class="pg-quicklinks">
                    <a class="pg-quicklink" href="{{ base_url('frontend/page/admissions') }}">
                        <span class="pg-quicklink-icon"><i class="fa fa-graduation-cap"></i></span>
                        <span><strong>Admissions</strong><span>2026&ndash;27 open now</span></span>
                    </a>
                    <a class="pg-quicklink" href="{{ base_url('frontend/page/academics') }}">
                        <span class="pg-quicklink-icon"><i class="fa fa-book"></i></span>
                        <span><strong>Academics</strong><span>CBSE curriculum</span></span>
                    </a>
                    <a class="pg-quicklink" href="{{ base_url('frontend/page/student-life') }}">
                        <span class="pg-quicklink-icon"><i class="fa fa-futbol-o"></i></span>
                        <span><strong>Student Life</strong><span>Sports, art &amp; more</span></span>
                    </a>
                    <a class="pg-quicklink" href="{{ base_url('frontend/page/contact') }}">
                        <span class="pg-quicklink-icon"><i class="fa fa-map-marker"></i></span>
                        <span><strong>Visit Us</strong><span>Khopoli, Raigad</span></span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <section id="about" class="pg-section pg-principal">
        <div class="pg-principal-decor" aria-hidden="true"></div>
        <div class="pg-container">
            <div class="pg-split">
                @if(customCompute($featured_image))
                    <div class="pg-principal-media">
                        <div class="pg-media-frame">
                            <img src="{{ imageLinkWithDefatulImage($featured_image->file_name, 'holiday.png', 'uploads/gallery/') }}" alt="{{ $featured_image->file_alt_text }}">
                        </div>
                        <div class="pg-principal-badge">
                            <strong>Dr. Gaurav Tiwari</strong>
                            <span>Founder Principal</span>
                        </div>
                    </div>
                @endif
                <?php
                    $aboutColClass = customCompute($featured_image) ? '' : 'pg-section-head';
                    $aboutColStyle = customCompute($featured_image) ? '' : 'max-width:none';
                ?>
                <div class="{{ $aboutColClass }}" style="{{ $aboutColStyle }}">
                    <span class="pg-eyebrow">Welcome</span>
                    <h2>{{ $page->title }}</h2>
                    <div class="pg-prose">{{ htmlspecialchars_decode($page->content) }}</div>
                    <a class="pg-btn pg-btn-outline" href="{{ base_url('frontend/page/about-us') }}">Read the full message <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <section class="pg-section--tight pg-section--alt">
        <div class="pg-container">
            <div class="pg-stat-card">
                <div class="pg-stat-row">
                    <div class="pg-stat"><strong>2019</strong><span>Founded</span></div>
                    <div class="pg-stat"><strong>1131395</strong><span>CBSE Affiliation No.</span></div>
                    <div class="pg-stat"><strong>31384</strong><span>School Code</span></div>
                    <div class="pg-stat"><strong>55+</strong><span>Teachers</span></div>
                </div>
            </div>
        </div>
    </section>

    <section class="pg-section">
        <div class="pg-container">
            <div class="pg-section-head text-center">
                <span class="pg-eyebrow">A School with Difference</span>
                <h2>Why Families Choose PPGMIS</h2>
                <p>A few of the things that set our classrooms apart.</p>
            </div>
            <div class="pg-why-grid">
                <div class="pg-why-tile">
                    <span class="pg-why-icon"><i class="fa fa-laptop"></i></span>
                    <h4>Modern Teaching Aids</h4>
                    <p>Smart classrooms and interactive tools alongside hands-on, experiential learning.</p>
                </div>
                <div class="pg-why-tile">
                    <span class="pg-why-icon"><i class="fa fa-users"></i></span>
                    <h4>Low Teacher&ndash;Student Ratio</h4>
                    <p>Ultimate personal care and attention for every child&rsquo;s personality development.</p>
                </div>
                <div class="pg-why-tile">
                    <span class="pg-why-icon"><i class="fa fa-paint-brush"></i></span>
                    <h4>Art, Craft &amp; Music</h4>
                    <p>A dedicated activity room and motivating environment for creative growth.</p>
                </div>
                <div class="pg-why-tile">
                    <span class="pg-why-icon"><i class="fa fa-bus"></i></span>
                    <h4>Transport &amp; Support</h4>
                    <p>Transport facility, remedial teaching and an affordable fee structure.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="pg-section pg-section--alt">
        <div class="pg-admissions-banner pg-container" style="max-width: var(--pg-container);">
            <div>
                <h3><i class="fa fa-graduation-cap"></i> Admissions are open</h3>
                <p>Join a community built on care, character and CBSE-aligned academics. Seats for the new session are filling fast.</p>
            </div>
            <a class="pg-btn pg-btn-invert" href="{{ base_url('frontend/page/admissions') }}">Enquire Now <i class="fa fa-arrow-right"></i></a>
        </div>
    </section>

    @if(customCompute($notices))
        <section class="pg-section">
            <div class="pg-container">
                <div class="pg-section-head text-center">
                    <span class="pg-eyebrow">Stay Informed</span>
                    <h2>Latest Notices</h2>
                    <p>What's happening around the school this week.</p>
                </div>
                <div class="pg-grid pg-grid--3">
                    <?php $i = 1; ?>
                    @foreach($notices as $notice)
                        @if($i <= 3)
                            <div class="pg-card pg-notice-card">
                                <span class="pg-notice-date"><i class="fa fa-calendar"></i> {{ date('d M Y', strtotime($notice->date)) }}</span>
                                <h3>{{ namesorting($notice->title, 55) }}</h3>
                                <p>{{ namesorting($notice->notice, 110) }}</p>
                                <a class="pg-read-more" href="{{ base_url('frontend/notice/'.$notice->noticeID) }}">Read more <i class="fa fa-long-arrow-right"></i></a>
                            </div>
                        @endif
                        <?php $i++; ?>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <?php $homeRecentPosts = $this->posts_m->get_order_by_posts(['status' => 1]); ?>
    @if(customCompute($homeRecentPosts))
        <section class="pg-section">
            <div class="pg-container">
                <div class="pg-section-head text-center">
                    <span class="pg-eyebrow">From the Blog</span>
                    <h2>Recent Posts</h2>
                    <p>Notes on learning, growth and life at PPGMIS.</p>
                </div>
                <div class="pg-grid pg-grid--3">
                    <?php $homeMedia = pluck($this->media_gallery_m->get_order_by_media_gallery(['media_gallery_type' => 1]), 'obj', 'media_galleryID'); ?>
                    <?php $i = 1; ?>
                    @foreach($homeRecentPosts as $hpost)
                        @if($i <= 3)
                            <article class="pg-card pg-blog-card">
                                @if(isset($homeMedia[$hpost->featured_image]))
                                    <a href="{{ base_url('frontend/post/'.$hpost->url) }}" class="pg-blog-thumb">
                                        <img src="{{ imageLinkWithDefatulImage($homeMedia[$hpost->featured_image]->file_name, 'holiday.png', 'uploads/gallery/') }}" alt="">
                                    </a>
                                @endif
                                <div class="pg-blog-body">
                                    <h3><a href="{{ base_url('frontend/post/'.$hpost->url) }}">{{ $hpost->title }}</a></h3>
                                    <p>{{ namesorting(strip_tags($hpost->content), 120) }}</p>
                                    <a class="pg-read-more" href="{{ base_url('frontend/post/'.$hpost->url) }}">Read article <i class="fa fa-long-arrow-right"></i></a>
                                </div>
                            </article>
                        @endif
                        <?php $i++; ?>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if(customCompute($teachers))
        <section class="pg-section pg-section--alt">
            <div class="pg-container">
                <div class="pg-section-head text-center">
                    <span class="pg-eyebrow">Our People</span>
                    <h2>Meet the Faculty</h2>
                    <p>Experienced educators dedicated to every student's growth.</p>
                </div>
                <div class="pg-grid pg-grid--4">
                    <?php $i = 1; ?>
                    @foreach($teachers as $teacher)
                        @if($i <= 4)
                            <div class="pg-card pg-staff-card">
                                <div class="pg-staff-photo"><img src="{{ imagelink($teacher->photo) }}" alt=""></div>
                                <h4>{{ namesorting($teacher->name, 18) }}</h4>
                                <div class="pg-staff-role">{{ $teacher->designation }}</div>
                                <div class="pg-staff-social">
                                    @if(isset($sociallink[$teacher->usertypeID][$teacher->teacherID]))
                                        <a href="{{ $sociallink[$teacher->usertypeID][$teacher->teacherID]->facebook }}"><i class="fa fa-facebook"></i></a>
                                        <a href="{{ $sociallink[$teacher->usertypeID][$teacher->teacherID]->twitter }}"><i class="fa fa-twitter"></i></a>
                                        <a href="{{ $sociallink[$teacher->usertypeID][$teacher->teacherID]->linkedin }}"><i class="fa fa-linkedin"></i></a>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <?php $i++; ?>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
