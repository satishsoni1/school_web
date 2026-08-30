
<?php
    $pgAnnounceStatus = frontendData::get_backend('announcement_status');
    $pgAnnounceTitle  = frontendData::get_backend('announcement_title');
    $pgAnnounceText   = frontendData::get_backend('announcement_text');
    $pgAnnounceLink   = frontendData::get_backend('announcement_link');
    $pgAnnounceImage  = frontendData::get_backend('announcement_image');
    $pgAnnounceHasContent = ($pgAnnounceTitle || $pgAnnounceText || $pgAnnounceImage);
    $pgAnnounceImageOnly  = ($pgAnnounceImage && !$pgAnnounceTitle && !$pgAnnounceText);
?>
@if($pgAnnounceStatus && $pgAnnounceHasContent)
    <?php $pgAnnounceHash = md5($pgAnnounceTitle.'|'.$pgAnnounceText.'|'.$pgAnnounceLink.'|'.$pgAnnounceImage); ?>
    <div class="pg-announce-overlay" id="pgAnnounceOverlay" data-hash="{{ $pgAnnounceHash }}" role="dialog" aria-modal="true" aria-labelledby="pgAnnounceTitle">
        @if($pgAnnounceImageOnly)
            <div class="pg-announce-modal pg-announce-modal--image-only">
                <button type="button" class="pg-announce-close" id="pgAnnounceClose" aria-label="Close announcement"><i class="fa fa-times"></i></button>
                @if($pgAnnounceLink)
                    <a href="{{ $pgAnnounceLink }}" class="pg-announce-media">
                        <img src="{{ base_url('uploads/gallery/'.$pgAnnounceImage) }}" alt="Announcement">
                    </a>
                @else
                    <div class="pg-announce-media">
                        <img src="{{ base_url('uploads/gallery/'.$pgAnnounceImage) }}" alt="Announcement">
                    </div>
                @endif
            </div>
        @else
            <div class="pg-announce-modal">
                <button type="button" class="pg-announce-close" id="pgAnnounceClose" aria-label="Close announcement"><i class="fa fa-times"></i></button>
                @if($pgAnnounceImage)
                    <div class="pg-announce-media">
                        <img src="{{ base_url('uploads/gallery/'.$pgAnnounceImage) }}" alt="{{ $pgAnnounceTitle }}">
                    </div>
                @endif
                <div class="pg-announce-body">
                    @if($pgAnnounceTitle)
                        <h3 id="pgAnnounceTitle">{{ $pgAnnounceTitle }}</h3>
                    @endif
                    @if($pgAnnounceText)
                        <p>{{ $pgAnnounceText }}</p>
                    @endif
                    <div class="pg-announce-ctas">
                        @if($pgAnnounceLink)
                            <a class="pg-btn pg-btn-primary" href="{{ $pgAnnounceLink }}">Learn more <i class="fa fa-arrow-right"></i></a>
                        @endif
                        <button type="button" class="pg-btn pg-btn-outline" id="pgAnnounceDismiss">{{ $pgAnnounceLink ? 'Not now' : 'Close' }}</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif
