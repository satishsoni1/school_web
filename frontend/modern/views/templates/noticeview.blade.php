@layout('views/layouts/master')

@section('content')

    <div class="pg-pagehead">
        <div class="pg-container pg-pagehead-inner">
            <h1>{{ $noticeView->title }}</h1>
            <nav class="pg-breadcrumb">
                <a href="{{ base_url('frontend') }}">Home</a>
                <span class="sep">/</span>
                <span class="current">Notice</span>
            </nav>
        </div>
    </div>

    <section class="pg-section">
        <div class="pg-container">
            <div class="pg-detail-card" style="max-width:820px;margin:0 auto;">
                <div class="pg-detail-meta">
                    <span><i class="fa fa-calendar"></i> {{ date('d M Y', strtotime($noticeView->date)) }}</span>
                </div>
                <div class="pg-prose">{{ htmlspecialchars_decode($noticeView->notice) }}</div>
            </div>
        </div>
    </section>

@endsection
