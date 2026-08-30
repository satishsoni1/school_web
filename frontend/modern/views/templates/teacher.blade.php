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
                                <h1>{{ sentenceMap(htmlspecialchars_decode($slider->file_title), 17, '<span>', '</span>') }}</h1>
                                <p>{{ htmlspecialchars_decode($slider->file_description) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $i++; ?>
            @endforeach
            <div class="pg-hero-dots"></div>
        </div>
    @endif

    <div class="pg-pagehead">
        <div class="pg-container pg-pagehead-inner">
            <h1>{{ $page->title }}</h1>
            <nav class="pg-breadcrumb">
                <a href="{{ base_url('frontend/'.$homepageType.'/'.$homepage->url) }}">{{ $homepageTitle }}</a>
                <span class="sep">/</span>
                <span class="current">{{ $page->title }}</span>
            </nav>
        </div>
    </div>

    @if(customCompute($websiteStaffTeaching))
        <section class="pg-section">
            <div class="pg-container">
                <div class="pg-section-head text-center">
                    <span class="pg-eyebrow">Meet the Team</span>
                    <h2>Staff</h2>
                </div>
                <div class="pg-grid pg-grid--4">
                    @foreach($websiteStaffTeaching as $staffMember)
                        <div class="pg-card pg-staff-card">
                            <div class="pg-staff-photo"><img src="{{ imagelink($staffMember->photo, 'uploads/gallery') }}" alt=""></div>
                            <h4>{{ $staffMember->name }}</h4>
                            <div class="pg-staff-role">{{ $staffMember->designation }}</div>
                            <div class="pg-staff-meta">
                                @if($staffMember->email)<div>{{ $staffMember->email }}</div>@endif
                                @if($staffMember->phone)<div>{{ $staffMember->phone }}</div>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if(customCompute($websiteStaffNonTeaching))
        <section class="pg-section pg-section--alt">
            <div class="pg-container">
                <div class="pg-section-head text-center">
                    <span class="pg-eyebrow">Behind the Scenes</span>
                    <h2>Non-Teaching Staff</h2>
                </div>
                <div class="pg-grid pg-grid--4">
                    @foreach($websiteStaffNonTeaching as $staffMember)
                        <div class="pg-card pg-staff-card">
                            <div class="pg-staff-photo"><img src="{{ imagelink($staffMember->photo, 'uploads/gallery') }}" alt=""></div>
                            <h4>{{ $staffMember->name }}</h4>
                            <div class="pg-staff-role">{{ $staffMember->designation }}</div>
                            <div class="pg-staff-meta">
                                @if($staffMember->email)<div>{{ $staffMember->email }}</div>@endif
                                @if($staffMember->phone)<div>{{ $staffMember->phone }}</div>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if(!customCompute($websiteStaffTeaching) && !customCompute($websiteStaffNonTeaching))
        <section class="pg-section">
            <div class="pg-container">
                <p class="text-muted" style="text-align:center;">Staff details will be published soon.</p>
            </div>
        </section>
    @endif

    @if(strlen($page->content) > 0)
        <section class="pg-section--tight pg-section--alt">
            <div class="pg-container">
                <div class="pg-prose">{{ htmlspecialchars_decode($page->content) }}</div>
            </div>
        </section>
    @endif

@endsection
