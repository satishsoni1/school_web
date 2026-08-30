@layout('views/layouts/master')

@section('content')

    <div class="pg-pagehead">
        <div class="pg-container pg-pagehead-inner">
            <h1>{{ $post->title }}</h1>
            <nav class="pg-breadcrumb">
                <a href="{{ base_url('frontend/'.$homepageType.'/'.$homepage->url) }}">{{ $homepageTitle }}</a>
                <span class="sep">/</span>
                <span class="current">{{ $post->title }}</span>
            </nav>
        </div>
    </div>

    <section class="pg-section">
        <div class="pg-container">
            <div class="pg-grid" style="grid-template-columns: 2fr 1fr;">
                <div>
                    @if(customCompute($post))
                        <article class="pg-card pg-blog-card">
                            @if(customCompute($featured_image))
                                <a href="{{ base_url('frontend/post/'.$post->url) }}">
                                    <img class="fixedsize" src="{{ base_url('uploads/gallery/'.$featured_image->file_name) }}" alt="">
                                </a>
                            @endif
                            <div class="pg-blog-body">
                                <span class="pg-blog-meta">{{ date('dS F, Y', strtotime($post->publish_date)) }} &middot; {{ frontendData::get_user($post->create_usertypeID, $post->create_userID) }}</span>
                                <div class="pg-prose">{{ htmlspecialchars_decode($post->content) }}</div>
                            </div>
                        </article>
                    @endif
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
