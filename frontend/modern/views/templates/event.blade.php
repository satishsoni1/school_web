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
                @if(customCompute($events))
                    <?php $i = 1; ?>
                    @foreach($events as $event)
                        @if($i <= 9)
                            <div class="pg-card pg-event-card">
                                <div class="pg-event-thumb">
                                    <a href="{{ base_url('frontend/event/'.$event->eventID) }}"><img src="{{ imageLinkWithDefatulImage($event->photo, 'holiday.png') }}" alt=""></a>
                                    <div class="pg-event-date-badge">
                                        <strong>{{ date('d', strtotime($event->fdate)) }}</strong>
                                        <span>{{ date('M', strtotime($event->fdate)) }}</span>
                                    </div>
                                </div>
                                <div class="pg-event-body">
                                    <h4><a href="{{ base_url('frontend/event/'.$event->eventID) }}">{{ $event->title }}</a></h4>
                                    <div class="pg-event-time"><i class="fa fa-clock-o"></i> {{ date('h:i A', strtotime($event->ftime)) }} - {{ date('h:i A', strtotime($event->ttime)) }}</div>
                                    <a id="{{ $event->eventID }}" href="#" class="pg-event-going going-event"><i class="fa fa-check-circle"></i> Going now</a>
                                </div>
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

@section('footerAssetPush')

    <script type="text/javascript">
        $(document).on('click', '.going-event', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            if(id) {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('frontend/eventGoing')?>",
                    data: { 'id':id },
                    dataType: "html",
                    success: function(data) {
                        var response = jQuery.parseJSON(data);
                        if(response.status == true) {
                            toastr["success"](response.message);
                        } else {
                            toastr["error"](response.message);
                        }
                    }
                });
            }
        });

    </script>

@endsection
