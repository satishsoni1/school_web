
<footer class="pg-footer">
    <div class="pg-footer-accent"></div>
    <div class="pg-container">
        <div class="pg-footer-grid">
            <div>
                <div class="pg-footer-brand">
                    <span class="pg-brand-mark">
                        @if(frontendData::get_backend('photo'))
                            <img src="{{ base_url('uploads/images/'.frontendData::get_backend('photo')) }}" alt="{{ frontendData::get_backend('sname') }}">
                        @else
                            <i class="fa fa-graduation-cap" style="color:#fff"></i>
                        @endif
                    </span>
                    <?php $hometype = (isset($homepage->pagesID) ? 'page' : (isset($homepage->postsID) ? 'post' : '')); ?>
                    <span class="pg-footer-brand-name">
                        @if(customCompute($homepage))
                            <a href="{{ base_url('frontend/'.$hometype.'/'.$homepage->url) }}">{{ $backend->sname }}</a>
                        @else
                            <a>{{ $backend->sname }}</a>
                        @endif
                    </span>
                </div>
                <p>{{ frontendData::get_frontend('description') }}</p>
                <div class="pg-footer-social">
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

            @if(isset($menu['frontendSocialQueryMenus']))
                @if(customCompute($menu['frontendSocialQueryMenus']))
                    <div>
                        <h5>Quick Links</h5>
                        <ul class="pg-footer-links">
                        <?php $countFrontendSocialQueryMenus = customCompute($menu['frontendSocialQueryMenus']); ?>
                        @foreach ($menu['frontendSocialQueryMenus'] as $frontendSocialQueryMenu)
                            <?php
                                $url = '#';
                                if($frontendSocialQueryMenu->menu_typeID == 1) {
                                    if(isset($fpages[$frontendSocialQueryMenu->menu_pagesID])) {
                                        $url = base_url('frontend/page/'.$fpages[$frontendSocialQueryMenu->menu_pagesID]->url);
                                    }
                                } elseif ($frontendSocialQueryMenu->menu_typeID == 2) {
                                    if(isset($fposts[$frontendSocialQueryMenu->menu_pagesID])) {
                                        $url = base_url('frontend/post/'.$fposts[$frontendSocialQueryMenu->menu_pagesID]->url);
                                    }
                                } elseif($frontendSocialQueryMenu->menu_typeID == 3) {
                                    $url = $frontendSocialQueryMenu->menu_link;
                                }
                            ?>
                            @if($url !== '#')
                                <li><a href="{{ $url }}"><i class="fa fa-angle-right"></i> <span>{{ $frontendSocialQueryMenu->menu_label }}</span></a></li>
                            @endif
                        @endforeach
                        </ul>
                    </div>
                @endif
            @endif

            <div>
                <h5>Get in Touch</h5>
                <ul class="pg-footer-contact">
                    @if(frontendData::get_backend('address'))
                        <li><i class="fa fa-map-marker"></i> <span>{{ frontendData::get_backend('address') }}</span></li>
                    @endif
                    @if(frontendData::get_backend('phone'))
                        <li><i class="fa fa-phone"></i> <a href="tel:{{ frontendData::get_backend('phone') }}">{{ frontendData::get_backend('phone') }}</a></li>
                    @endif
                    @if(frontendData::get_backend('email'))
                        <li><i class="fa fa-envelope-o"></i> <a href="mailto:{{ frontendData::get_backend('email') }}">{{ frontendData::get_backend('email') }}</a></li>
                    @endif
                    <li><i class="fa fa-clock-o"></i> <span>Mon &ndash; Sat: 7:30 AM &ndash; 2:00 PM</span></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="pg-footer-bottom">
        <div class="pg-container pg-footer-bottom-inner">
            <span>{{ frontendData::get_backend('footer') }} &middot; Website by <a href="{{ base_url('signin/index') }}">PPGMIS</a></span>
            <a class="pg-footer-totop" href="#top" id="pgFooterToTop">Back to top <i class="fa fa-angle-up"></i></a>
        </div>
    </div>
</footer>

<button type="button" class="pg-scroll-top" id="pgScrollTop" aria-label="Scroll to top"><i class="fa fa-angle-up"></i></button>
