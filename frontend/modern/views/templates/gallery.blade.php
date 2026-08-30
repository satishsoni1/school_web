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
            <div class="pg-prose">{{ htmlspecialchars_decode($page->content) }}</div>
        </div>
    </section>

@endsection
