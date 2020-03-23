function CustomConfirmation() {
    if( !(this instanceof CustomConfirmation) ) {
        return new CustomConfirmation();
    }

    this.confirm_box = "_custom_confirm_box_container_";
    this.confirm_style = "_custom_confirm_box_style_";
    this.confirmed = false;
    this.status = 0;
    this.init();
    console.log('CustomConfirmation initialized')
}

CustomConfirmation.prototype.STATUS_DONE = 1;
CustomConfirmation.prototype.STATUS_WAITING = 2;
CustomConfirmation.prototype.STATUS_NONE = 0;

CustomConfirmation.prototype.init = function() {
    var $this = this;
    window['playAudio'] = function () { 
        $this.audio_beep_sound().play(); 
    } 
    window['pauseAudio'] = function () { 
        $this.audio_beep_sound().pause(); 
    } 
    document.querySelectorAll('[data-confirmation]').forEach(function(a) {
        a.onclick = async function(e){
            e.preventDefault();
            window.playAudio();
            var status = await $this.confirm(this.getAttribute('data-confirmation'));
            // var status = window.confirm(this.getAttribute('data-confirmation'));

            $this.close();
            if(status) {
                window.location.replace(this.getAttribute('href'));
            }
        }
    })
}

CustomConfirmation.prototype.confirm = async function(message) {
    var $this = this;
    var interval = 100;
    $this.status = $this.STATUS_WAITING;
    $this.template(message);

    return new Promise(function(resolve, reject) {
        var timer = setInterval(function() {
            if( $this.status === $this.STATUS_DONE ) {
                clearInterval(timer);
                $this.status = $this.STATUS_NONE;
                return resolve($this.confirmed);
            }
        }, interval);
    })
}

CustomConfirmation.prototype.close = function() {
    var confirm_box_id = this.confirm_box;
    var element = document.getElementById(confirm_box_id);
    if(element) {
        element.style.display = "none";
    }
}

CustomConfirmation.prototype.style = function(){
    var confirm_box_id = this.confirm_style;
    var element = document.getElementById(confirm_box_id);

    if(!element) {
        var html = `
.custom-confirm-box {
    width: 300px;
    height: auto;
    position: fixed;
    left: 50%;
    top: 0;
    margin-left: -150px;
    margin-top: 100px;
    background: #ffffff;
    z-index: 99999;
    box-shadow: 0 0 10px 1px rgb(197, 197, 197);
}

.custom-confirm-box .col,
.custom-confirm-box .row {
    /* margin: 0 !important; */
    /* padding: 0 !important; */
}

.custom-confirm-box-background {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 99998;
    background: rgba(0, 0, 0, 0.08);
}

.btn {
    text-transform: none;
}`;

        element = document.createElement('STYLE');
        element.setAttribute('id', confirm_box_id);
        element.innerHTML = html;
        document.head.appendChild(element);
    }
    
}

CustomConfirmation.prototype.template = function(message) {
    var confirm_box_id = this.confirm_box;
    var element = document.getElementById(confirm_box_id); 

    if(!element) {
        this.style();
        element = document.createElement('DIV');
        element.setAttribute('id', confirm_box_id);

        var html = `<div class="custom-confirm-box">
            <div class="clearfix p-4 custom-confirm-message">
                ${message}
            </div>
            <div class="row px-3 pb-3">
                
                <div class="col pl-2">
                    <button type="button" class="btn btn-secondary box-shadow btn-block" data-action="no">No</button>
                </div>
                <div class="col pr-2">
                    <button type="button" class="btn btn-primary box-shadow btn-block " data-action="yes">Yes</button>
                </div>
            </div>
        </div>
        <div class="custom-confirm-box-background"></div>`;
        element.innerHTML = html;
        document.body.appendChild(element);
        element.style.display = "none";

        element.querySelector('[data-action=yes]').onclick = this.onSubmit(true);
        element.querySelector('[data-action=no]').onclick = this.onSubmit(false);
    }
    
    if(element) {
        element.style.display = "block";
        element.querySelector('.custom-confirm-message').innerHTML = message;
    }
}

CustomConfirmation.prototype.onSubmit = function(value){
    var $this = this;
    return function() {
        $this.confirmed = value;
        $this.status = $this.STATUS_DONE;
    }
}

CustomConfirmation.prototype.audio_beep_sound = function (){
    var beep_audio_id = "_audio_beep_sound_";
    var audio_file = `${base_url}/media/audio/beeps.mp3`;
    var audio = document.getElementById(beep_audio_id); 
    var audio_source = null;
    if(!audio) {
        audio_source = document.createElement('SOURCE');
        audio_source.src = audio_file;
        audio_source.type = "audio/mpeg";

        audio = document.createElement('AUDIO');
        audio.id = beep_audio_id;
        audio.style.display = "none";
        audio.appendChild(audio_source);
        
        document.body.appendChild(audio);
    }

    return audio;
}

jQuery.fn.ckeditor = function(){
    console.log(this);
    if(this.length) {
        this.each(function(index, elem){ 
            var name = this.getAttribute('name');
            if(name && typeof CKEDITOR != 'undefined') {
                CKEDITOR.replace(name, {
                    toolbarGroups: [{
                        "name": "basicstyles",
                        "groups": ["basicstyles", "styles", "list", "blocks"]
                      }
                    ]
                });
            }
        })
    }
}


jQuery(document).ready(function(){
    console.log(window.base_url);
    CustomConfirmation();

    bsCustomFileInput.init();
    
    if(jQuery.fn.tooltip){
        jQuery('[data-toggle="tooltip"]').tooltip();
    }

    if(jQuery && jQuery.fn.dcAccordion){
        // console.log('dcAccordion');
        jQuery('[accordion]').dcAccordion({
            speed:'fast',
            classActive : 'active',
            classArrow  : 'fa fa-angle-down',
            classExpand : 'active',
            autoExpand  : false
        });
    }

    // console.log('select2')
    // console.log(jQuery.fn.select2)
    if(jQuery && jQuery.fn.select2) {
        // console.log('select2')
        jQuery('.select2').select2({
            selectOnClose: true,
            placeholder: 'Select an option'
        });

        jQuery('.category-input').select2({
            selectOnClose: true,
            placeholder: 'Categories...',
            containerCssClass: 'form-control form-control-lg'
        });
    }

    if(jQuery && jQuery.fn.niceScroll){
        var scroll_container = function(){
            jQuery('[scroll-container]').niceScroll({
                cursorcolor: "#aaaaaa",
                cursorborder: "2px solid #aaaaaa",
                cursorborderradius: "2px",
                cursorwidth: "3px",
                autohidemode: true,
                spacebarenabled: false
            });
        }

        scroll_container();
        window.onresize = function(event){
            jQuery('[scroll-container]').getNiceScroll().remove();
            scroll_container();
        }
    }
    
    if(jQuery.fn.cycle){
        jQuery('.pics').cycle({
            fx:     'fade',
            speed:  'fast',
            delay:  -4000,
            cleartypeNoBg: true,
            next:   '.control-next',
            prev:   '.control-prev'
    
        });
    }
	
	if(jQuery.fn.ckeditor) {
		jQuery('.ckeditor').ckeditor();
	}

    if(jQuery.fn.datepicker){
        // console.log('jQuery.fn.datepicker');
        jQuery('.datepicker').datepicker({
            format:'yyyy-mm-dd',
            autoclose: true
        });
    }

    jQuery("input.profile-img").change(function(e){
        var img = e.target.files[0];
        var input_name = jQuery(this).attr('name');
        var widthDim = jQuery(this).data('width');
        var heightDim = jQuery(this).data('height');
        var squareDim = jQuery(this).data('square') || true;

        console.log(input_name)

        if(!widthDim && !heightDim){
            widthDim = squareDim;
        }

        if(!img.type.match('image.*')){
            alert("Whoops! That is not an image.");
            return;
        }

        iEdit.open(img, widthDim, heightDim, function(res){
            var image = new Image();
            image.onload = function (imageEvent) {

                // Resize the image
                var canvas = document.createElement('canvas'),
                    max_size = (window.profile_upload)? 140: 640,
                    width = image.width,
                    height = image.height;

                if (width > height) {
                    if (width > max_size) {
                        height *= max_size / width;
                        width = max_size;
                    }
                } else {
                    if (height > max_size) {
                        width *= max_size / height;
                        height = max_size;
                    }
                }
                
                canvas.width = width;
                canvas.height = height;
                canvas.getContext('2d').drawImage(image, 0, 0, width, height);
                resizedImage = canvas.toDataURL('image/jpeg');

                console.log(jQuery("[name="+input_name+"_preview]"));
                
                jQuery("[name="+input_name+"_preview]").attr("src", resizedImage);
                jQuery("[name="+input_name+"_data]").val(resizedImage);
                jQuery("[name="+input_name+"_filename]").val(img.name);
            }

            image.src = res;
        });
    });
});

