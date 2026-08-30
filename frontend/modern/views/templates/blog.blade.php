@layout('views/layouts/master')

@section('content')

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

    @if(strlen($page->content) > 0)
        <section class="pg-section--tight">
            <div class="pg-container">
                <div class="pg-prose" style="max-width:760px;">{{ htmlspecialchars_decode($page->content) }}</div>
            </div>
        </section>
    @endif

    <section class="pg-section" style="padding-top:24px;">
        <div class="pg-container">
            <div class="pg-grid" style="grid-template-columns: 2fr 1fr;">
                <div>
                    <div class="pg-grid pg-grid--2">
                        @if(customCompute($posts))
                            @foreach($posts as $post)
                                <article class="pg-card pg-blog-card">
                                    @if(isset($featured_image[$post->featured_image]))
                                        <a href="{{ base_url('frontend/post/'.$post->url) }}" class="pg-blog-thumb">
                                            <img src="{{ imageLinkWithDefatulImage($featured_image[$post->featured_image]->file_name, 'holiday.png', 'uploads/gallery/') }}" alt="">
                                        </a>
                                    @endif
                                    <div class="pg-blog-body">
                                        <span class="pg-blog-meta">{{ date('dS F, Y', strtotime($post->publish_date)) }} &middot; {{ frontendData::get_user($post->create_usertypeID, $post->create_userID) }}</span>
                                        <h3><a href="{{ base_url('frontend/post/'.$post->url) }}">{{ $post->title }}</a></h3>
                                        <p>
                                            @if(strlen(strip_tags($post->content)) > 160)
                                                {{ namesorting(strip_tags($post->content), 160) }}
                                            @else
                                                {{ strip_tags($post->content) }}
                                            @endif
                                        </p>
                                        <a class="pg-read-more" href="{{ base_url('frontend/post/'.$post->url) }}">Read article <i class="fa fa-long-arrow-right"></i></a>
                                    </div>
                                </article>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div>
                    <div class="pg-blog-sidebar">
                        <h4>Recent Posts</h4>
                        <ul>
                            @if(customCompute($posts))
                                <?php $i=1; ?>
                                @foreach($posts as $post)
                                    <li><a href="{{ base_url('frontend/post/'.$post->url) }}"><i class="fa fa-arrow-right"></i> {{ namesorting(strip_tags($post->title), 60) }}</a></li>
                                    @if($i == 6)
                                        <?php break; ?>
                                    @endif
                                    <?php $i++; ?>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
