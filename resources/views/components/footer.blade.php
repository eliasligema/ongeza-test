@if(true)
<div class="footer-summary clearfix text-center">
    <div class="cover section-padding">
        <div class="container text-center">
            <div class="card-group text-white">
                <div class="card bg-transparent border-0 p-3">
                    <div class="card-body pb-0">
                        <h3 class="number">+1,160</h3>
                        <h5 class="card-title">{{ Helper::trans('footer.visitors_per_day', 'Visitors Per Day') }}</h5>
                    </div>
                </div>
                <div class="card bg-transparent border-0 p-3">
                    <div class="card-body pb-0">
                        <h3 class="number">+50</h3>
                        <h5 class="card-title">{{ Helper::trans('footer.total_categories', 'Total Categories') }}</h5>
                    </div>
                </div>
                <div class="card bg-transparent border-0 p-3">
                    <div class="card-body pb-0">
                        <h3 class="number">+690</h3>
                        <h5 class="card-title">{{ Helper::trans('footer.categories_per_day', 'Categories Per Day') }}</h5>
                    </div>
                </div>
                <div class="card bg-transparent border-0 p-3">
                    <div class="card-body pb-0">
                        <h3 class="number">+250</h3>
                        <h5 class="card-title">{{ Helper::trans('footer.sellers', 'Sellers') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- <hr class="mb-0"> -->

<div class="footer-address-links clearfix bg-white ">
    <div class="container section-padding text-left">
        <div class="row mx-0">
            <div class="col-12 col-lg-4 text-center text-md-left">
                <div class="text-center text-md-left px-3 px-md-0 mb-5 mb-md-0">
                    <h4 class="mb-4">{{ Helper::trans('footer.about_us', 'About Us') }}</h4>
                    <div class=" text-justify">
                    {{ Helper::trans('footer.about_content', 'We work hard to build a platform that is beautifully designed, user friendly as well as ensure every client is satisfied with the service they get.') }}
                        
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4 text-center text-md-left pl-md-5">
                <div class="text-center text-md-left px-3 px-md-5 mb-5 mb-md-0">
                    <h4 class="mb-4">{{ Helper::trans('footer.others', 'Others') }}</h4>
                    <ul class="list-unstyled">
                        <li><a class="text-dark" href="{{ url('page/help') }}">{{ Helper::trans('footer.help', 'Help') }}</a></li>
                        <li><a class="text-dark" href="{{ url('about') }}">{{ Helper::trans('footer.about_us', 'About Us') }}</a></li>
                        <li><a class="text-dark" href="{{ url('contact') }}">{{ Helper::trans('footer.contact_us', 'Contact Us') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-12 col-lg-4 text-center text-md-left">
                <div class="text-center text-md-left px-3 px-md-0 mb-5 mb-md-0">
                    <h4 class="mb-4">{{ Helper::trans('footer.address', 'Address') }}</h4>
                    <div class="no-padding mb-5 mb-md-0">
                        <a class="d-block text-muted btn-link text-justify" target="_blank" href="https://goo.gl/maps/Vv2YECBA67h2gRKPA">8th Floor | Posta House | Plot No. 6&amp;7 Ohio
                        Street/Ghana Avenue, Dar es salaam, Tanzania.</a>
                        <h4 class="mt-4">{{ Helper::trans('footer.contact_us', 'Contact Us') }}</h4>
                        <a class="d-block">Phone: +{{ config('app.phone') }}</a>
                        <a class="d-block">WhatsApp: +{{ config('app.whatsapp') }}</a>
                        <a class="d-block" href="mailto:{{ config('app.email') }}">{{ config('app.email') }}</a>	  
                    </div>
                    <div class="social-links outline text-center text-md-left mt-3">
                        
                        <a class="btn btn-facebook btn-circle" href="https://facebook.com/{{ config('app.social_name') }}"
                            target="_blank">
                            <i class="fab fa-facebook-f fa-1x"></i>
                        </a>
                        <a class="btn btn-twitter btn-circle" href="https://twitter.com/{{ config('app.social_name') }}"
                            target="_blank">
                            <i class="fab fa-twitter fa-1x"></i>
                        </a>
                        <a class="btn btn-instagram btn-circle" href="https://www.instagram.com/{{ config('app.social_name') }}"
                            target="_blank">
                            <i class="fab fa-instagram fa-1x"></i>
                        </a>
                        <a class="btn btn-linkedin btn-circle" href="https://www.linkedin.com/in/{{ config('app.social_name') }}"
                            target="_blank">
                            <i class="fab fa-linkedin fa-1x"></i>
                        </a>
                        <a class="btn btn-whatsapp btn-circle" href="https://wa.me/{{ config('app.phone') }}?text=Hi, I'm interested" target="_blank">
                            <i class="fab fa-whatsapp fa-1x"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="footer-copyright clearfix bg-primary text-white ">
    <div class="container py-5">
        <div class="row align-items-center justify-content-between">
            <div class="col-12 col-md-6 m-0 text-center text-md-left">{{ Helper::trans('footer.copyright', 'Copyright') }} &copy; {{ config('app.name') }} 2018 - {{ date('Y', time()) }}</div>
            <div class="col-12 col-md-6 m-0 text-center text-md-right">{{ Helper::trans('footer.design', 'Design by') }} RK</div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="model_subscription_thanks" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" style="background:rgba(0,0,0,.2);">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="overflow:hidden;">
            <div class="modal-body {{ session('modal_alert') }}">
                <div class="d-flex align-items-center justify-content-between {{ session('modal_alert')? :'text-primary' }} ">
                    @if(session('modal_title'))
                    <h5 class="modal-title">{{ session('modal_title') }}</h5>
                    @endif
                    <button type="button" class="close py-1 my-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="clearfix" style="font-size:16px;">
                @if(session('modal_content'))
                    {!! session('modal_content') !!}
                @endif
                @if(session('modal_link'))
                    <div class="clearfix text-right mt-3">
                        <a href="{{ session('modal_link') }}" class="btn btn-primary">{{ session('modal_link_title')? session('modal_link_title'):'Continue' }}</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="ThrobberContainer"><canvas height="100" width="100" style="width: 100px; height: 100px; display: none;"></canvas></div>

@if(false)
@include('components.socket')
@endif

<script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/scripts.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/handlebars.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/taginput/jquery-tagsinput.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
<!-- <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script> -->
 
<script type="text/javascript" src="{{ asset('js/sliders/jquery.cookie.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/underscore/underscore-min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/underscore/underscore-skip-take.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/sliders/debug.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/sliders/throbber.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/sliders/common.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/sliders/localStorage.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/multimedia/multimedia.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/sliders/sliders.js') }}"></script>

<script>
jQuery(function(){
    @if(session('modal_title') || session('modal_content'))
        jQuery('#model_subscription_thanks').modal('show');
    @endif

    function reset_menu_sticky(pos) {
        if(pos > 100 && !jQuery('body').hasClass('sticky')) {
            jQuery('body').addClass('sticky');
        } else if(pos <= 50 && jQuery('body').hasClass('sticky')) {
            jQuery('body').removeClass('sticky');
        }
    }

    var pos = window.pageYOffset;
    reset_menu_sticky(pos);
    document.body.onscroll = function(e){
        pos = this.pageYOffset;
        reset_menu_sticky(pos);
    }

    atr.components.common.loadLazyComponents();
    atr.multimedia.initMultimediaViewer();
    atr.common.resizeBackground();

    jQuery(function() {
        atr.components.sliders.initSliderPositionsForCurrentPage();
    });
    
    if (atr.common.userIsOnMobile() === true) {
        console.log('mobile');
        jQuery('body').addClass('mobile');
    } else if(atr.common.userIsOnTablet() === true){
        console.log('tablet');
        jQuery('body').addClass('tablet');
    } else {
        console.log('desktop');
    }
})

</script>

@php
Session::forget(['modal_title', 'modal_content', 'modal_link']);
@endphp
</body>

</html>