@layout('views/layouts/master')

@section('content')

    <div class="pg-pagehead">
        <div class="pg-container pg-pagehead-inner">
            <h1>{{ $eventView->title }}</h1>
            <nav class="pg-breadcrumb">
                <a href="{{ base_url('frontend') }}">Home</a>
                <span class="sep">/</span>
                <span class="current">Event</span>
            </nav>
        </div>
    </div>

    <section class="pg-section">
        <div class="pg-container">
            <div class="pg-grid" style="grid-template-columns: 2.2fr 1fr;">
                <div class="pg-detail-card">
                    <div class="pg-event-detail-media">
                        <img src="{{ imageLinkWithDefatulImage($eventView->photo, 'holiday.png') }}" alt="">
                    </div>
                    <div class="pg-detail-meta">
                        <span><i class="fa fa-calendar"></i>
                            {{ date("d M Y", strtotime($eventView->fdate)) }}
                            @if($eventView->fdate != $eventView->tdate)
                                &ndash;
                                {{ date("d M Y", strtotime($eventView->tdate)) }}
                            @endif
                        </span>
                        <span><i class="fa fa-clock-o"></i> {{ date("h:i A", strtotime($eventView->ftime)) }} - {{ date("h:i A", strtotime($eventView->ttime)) }}</span>
                    </div>
                    <div class="pg-prose">{{ $eventView->details }}</div>
                </div>

                <div>
                    <h4 style="margin-bottom:16px;">Recent Events</h4>
                    <div class="pg-sidebar-list">
                        @if(customCompute($events))
                            <?php $i = 1; ?>
                            @foreach($events as $event)
                                @if($i <= 6)
                                    <a href="{{ base_url('frontend/event/'.$event->eventID) }}" class="pg-sidebar-event">
                                        <img src="{{ imageLinkWithDefatulImage($event->photo, 'holiday.png') }}" alt="">
                                        <div>
                                            <h4>{{ namesorting($event->title, 40) }}</h4>
                                            <span>{{ date("h:i A", strtotime($event->ftime)) }} - {{ date("h:i A", strtotime($event->ttime)) }}</span>
                                        </div>
                                    </a>
                                @endif
                                <?php $i++; ?>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
