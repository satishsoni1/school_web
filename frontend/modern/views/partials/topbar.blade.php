
<div class="pg-topbar">
    <div class="pg-container">
        <div class="pg-topbar-contact">
            @if(frontendData::get_backend('phone'))
                <span><i class="fa fa-phone"></i>{{ frontendData::get_backend('phone') }}</span>
            @endif
            @if(frontendData::get_backend('email'))
                <a href="mailto:{{ frontendData::get_backend('email') }}"><i class="fa fa-envelope-o"></i>{{ frontendData::get_backend('email') }}</a>
            @endif
        </div>
        <div class="pg-topbar-social">
            @if(frontendData::get_frontend('facebook'))
                <a href="{{ frontendData::get_frontend('facebook') }}" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
            @endif
            @if(frontendData::get_frontend('twitter'))
                <a href="{{ frontendData::get_frontend('twitter') }}" aria-label="Twitter"><i class="fa fa-twitter"></i></a>
            @endif
            @if(frontendData::get_frontend('linkedin'))
                <a href="{{ frontendData::get_frontend('linkedin') }}" aria-label="LinkedIn"><i class="fa fa-linkedin"></i></a>
            @endif
            @if(frontendData::get_frontend('youtube'))
                <a href="{{ frontendData::get_frontend('youtube') }}" aria-label="YouTube"><i class="fa fa-youtube"></i></a>
            @endif
            @if(frontendData::get_frontend('google'))
                <a href="{{ frontendData::get_frontend('google') }}" aria-label="Google+"><i class="fa fa-google-plus"></i></a>
            @endif
        </div>
    </div>
</div>
