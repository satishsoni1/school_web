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

    <section class="pg-section">
        <div class="pg-container">
            <div class="pg-grid pg-grid--4">
                @if(customCompute($teachers))
                    @foreach($teachers as $teacher)
                        <div class="pg-card pg-staff-card">
                            <div class="pg-staff-photo"><img src="{{ imagelink($teacher->photo) }}" alt=""></div>
                            <h4>{{ namesorting($teacher->name, 18) }}</h4>
                            <div class="pg-staff-role">{{ $teacher->designation }}</div>
                            <div class="pg-staff-meta">
                                @if(frontendData::get_frontend('teacher_email_status'))
                                    @if($teacher->email)<div>{{ $teacher->email }}</div>@endif
                                @endif
                                @if(frontendData::get_frontend('teacher_phone_status'))
                                    @if($teacher->phone)<div>{{ $teacher->phone }}</div>@endif
                                @endif
                            </div>
                            <div class="pg-staff-social">
                                @if(isset($sociallink[$teacher->usertypeID][$teacher->teacherID]))
                                    <a href="{{ $sociallink[$teacher->usertypeID][$teacher->teacherID]->facebook }}"><i class="fa fa-facebook"></i></a>
                                    <a href="{{ $sociallink[$teacher->usertypeID][$teacher->teacherID]->twitter }}"><i class="fa fa-twitter"></i></a>
                                    <a href="{{ $sociallink[$teacher->usertypeID][$teacher->teacherID]->linkedin }}"><i class="fa fa-linkedin"></i></a>
                                    <a href="{{ $sociallink[$teacher->usertypeID][$teacher->teacherID]->googleplus }}"><i class="fa fa-google-plus"></i></a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    @if(strlen($page->content) > 0)
        <section class="pg-section--tight pg-section--alt">
            <div class="pg-container">
                <div class="pg-prose">{{ htmlspecialchars_decode($page->content) }}</div>
            </div>
        </section>
    @endif

@endsection
