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

    <section class="pg-section pg-section--alt">
        <div class="pg-container">
            <div class="pg-grid pg-grid--3">
                @if(customCompute($notices))
                    <?php $i = 1; ?>
                    @foreach($notices as $notice)
                        @if($i <= 9)
                            <div class="pg-card pg-notice-card">
                                <span class="pg-notice-date"><i class="fa fa-calendar"></i> {{ date('d M Y', strtotime($notice->date)) }}</span>
                                <h3><a href="{{ base_url('frontend/notice/'.$notice->noticeID) }}">{{ namesorting($notice->title, 45) }}</a></h3>
                                <p>{{ namesorting($notice->notice, 140) }}</p>
                                <a class="pg-read-more" href="{{ base_url('frontend/notice/'.$notice->noticeID) }}">Read more <i class="fa fa-long-arrow-right"></i></a>
                            </div>
                        @endif
                        <?php $i++; ?>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    @if(strlen($page->content) > 0)
        <section class="pg-section--tight">
            <div class="pg-container">
                <div class="pg-prose">{{ htmlspecialchars_decode($page->content) }}</div>
            </div>
        </section>
    @endif
@endsection
