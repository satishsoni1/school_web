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
            <div class="pg-grid" style="grid-template-columns: 1fr 1.6fr; align-items:start;">
                <div class="pg-contact-info">
                    <div class="pg-contact-item">
                        <span class="pg-contact-icon"><i class="fa fa-map-marker"></i></span>
                        <div><strong>Address</strong><p>{{ frontendData::get_backend('address') }}</p></div>
                    </div>
                    <div class="pg-contact-item">
                        <span class="pg-contact-icon"><i class="fa fa-phone"></i></span>
                        <div><strong>Phone</strong><p>{{ frontendData::get_backend('phone') }}</p></div>
                    </div>
                    <div class="pg-contact-item">
                        <span class="pg-contact-icon"><i class="fa fa-envelope-o"></i></span>
                        <div><strong>Email</strong><p>{{ frontendData::get_backend('email') }}</p></div>
                    </div>
                </div>

                <div class="pg-contact-form-card">
                    <h3 style="margin-bottom:20px;">Send us a message</h3>
                    <form id="contact-form" action="mail.php" method="post">
                        <div class="pg-grid pg-grid--2">
                            <div class="pg-field">
                                <input id="send-email-name" type="text" class="pg-input" name="name" placeholder="Your Name">
                            </div>
                            <div class="pg-field">
                                <input id="send-email-email" type="email" class="pg-input" name="email" placeholder="Your Email">
                            </div>
                        </div>
                        <div class="pg-field">
                            <input id="send-email-subject" type="text" class="pg-input" name="sub" placeholder="Subject">
                        </div>
                        <div class="pg-field">
                            <textarea name="message" id="send-email-message" class="pg-textarea" maxlength="550" placeholder="Message..."></textarea>
                            <div class="pg-field-hint"><span id="counter__length"></span> characters remain</div>
                        </div>
                        <button type="button" name="ok" class="pg-btn pg-btn-primary" id="send-email">Submit <i class="fa fa-paper-plane"></i></button>
                        <p class="form-messege"></p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @if(strlen($page->content) > 0)
        <section class="pg-section--tight pg-section--alt">
            <div class="pg-container">
                <div class="pg-prose">{{ htmlspecialchars_decode($page->content) }}</div>
            </div>
        </section>
    @endif

@endsection

@section('footerAssetPush')
    <script>
        var counterLength = document.querySelector('#send-email-message');
        document.getElementById('counter__length').innerHTML = counterLength.getAttribute('maxlength');
        function val(e) {
            var attrLength = counterLength.getAttribute('maxlength');
            var currentLength = counterLength.value.length;
            var totalLength = attrLength - currentLength;
            document.querySelector('#counter__length').innerHTML = totalLength;
            if (e.keyCode === 8) {
                document.querySelector('#counter__length').innerHTML = totalLength++;
            }
        }
        counterLength.addEventListener("keyup", val, false);


        function check_email(email) {
            var status = false;
            var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
            if (email.search(emailRegEx) == -1) {
                $("#to_error").html('');
                $("#to_error").html("<?=$this->lang->line('mail_valid')?>").css("text-align", "left").css("color", 'red');
            } else {
                status = true;
            }
            return status;
        }

        $(document).on('click', '#send-email', function() {
            var error       = 0;
            var name        = $('#send-email-name').val();
            var email       = $('#send-email-email').val();
            var subject     = $('#send-email-subject').val();
            var message     = $('#send-email-message').val();


            if(name == '') {
                error++;
                $('#send-email-name').css("border-color", 'red');
            } else {
                $('#send-email-name').css("border-color", '');
            }

            if(email == '') {
                error++;
                $('#send-email-email').css("border-color", 'red');
            } else {
                $('#send-email-email').css("border-color", '');
            }

            if(subject == '') {
                error++;
                $('#send-email-subject').css("border-color", 'red');
            } else {
                $('#send-email-subject').css("border-color", '');
            }

            if(message == '') {
                error++;
                $('#send-email-message').css("border-color", 'red');
            } else {
                $('#send-email-message').css("border-color", '');
            }

            if(check_email(email) == false) {
                error++;
                $('#send-email-email').css("border-color", 'red');
            } else {
                $('#send-email-email').css("border-color", '');
            }

            if(error <= 0) {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('frontend/contactMailSend')?>",
                    data: {'name' : name, 'email' : email, 'subject' : subject, 'message' : message},
                    dataType: "html",
                    success: function(data) {
                        if(data = 'success') {
                            location.reload();
                        }
                    }
                });

            }
        });

    </script>


    @if($this->session->flashdata('success'))
        <script type="text/javascript">
            toastr["success"]("<?=$this->session->flashdata('success');?>")
        </script>
    @endif

    @if($this->session->flashdata('error'))
       <script type="text/javascript">
            toastr["error"]("<?=$this->session->flashdata('error');?>")
        </script>
    @endif
@endsection
