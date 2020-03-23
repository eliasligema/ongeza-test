  /*
   * Some helper functions to work with our UI and keep our code cleaner
   */

// Adds an entry to our debug area
function ui_add_log(message, color)
{
    var d = new Date();

    var dateString = (('0' + d.getHours())).slice(-2) + ':' +
        (('0' + d.getMinutes())).slice(-2) + ':' +
        (('0' + d.getSeconds())).slice(-2);

    color = (typeof color === 'undefined' ? 'muted' : color);

    var source   = document.getElementById("debug-template").innerHTML;
    var template = Handlebars.compile(source);
    var context = {date:dateString, message:message, color:color};
    var html    = template(context);
    
    jQuery('#debug').find('div.empty').fadeOut(); // remove the 'no messages yet'
    jQuery('#debug').prepend(html);
}

// Creates a new file and add it to our list
function ui_multi_add_file(container, id, file)
{
    var uploaded_list = jQuery(container).find('[files-list]');
    var is_single = jQuery(container).attr('single-file');
    is_single = (typeof is_single != 'undefined')? true: false;

    if(!uploaded_list.length) {
        uploaded_list = jQuery('[files-list]');
    }    

    var source = document.getElementById("files-template").innerHTML;
    var template = Handlebars.compile(source);
    var context = { filename:file.name, id:id };
    var html = template(context);
    html = jQuery(html);

    // html.get(0).onclick = async function(e){
    //     e.preventDefault();
    //     window.playAudio();
    //     var status = await confirmation.confirm(this.getAttribute('data-confirmation'));

    //     confirmation.close();
    //     if(status) {
    //         window.location.replace(this.getAttribute('href'));
    //     }
    // }

    var reader = new FileReader();
    reader.template = html;
    reader.onload = function(e) {
        // console.log(e.target.result)
        this.template.find('img.image-preview').attr('src', e.target.result);
    }
    reader.readAsDataURL(file);
    uploaded_list.find('div.empty').fadeOut(); // remove the 'no files yet'

    if(is_single) {
        uploaded_list.html(html);
    } else {
        uploaded_list.prepend(html);
    }
    
}

// Changes the status messages on our list
function ui_multi_update_file_status(id, status, message)
{
    jQuery('#uploaderFile' + id).find('span').html(message).prop('class', 'status text-' + status);
}

// Updates a file progress, depending on the parameters it may animate it or change the color.
function ui_multi_update_file_progress(id, percent, color, active)
{
    color = (typeof color === 'undefined' ? false : color);
    active = (typeof active === 'undefined' ? true : active);

    var bar = jQuery('#uploaderFile' + id).find('div.progress-bar');

    bar.width(percent + '%').attr('aria-valuenow', percent);
    bar.toggleClass('progress-bar-striped progress-bar-animated', active);

    if (percent === 0){
        bar.html('');
    } else {
        bar.html(percent + '%');
    }

    if (color !== false){
        bar.removeClass('bg-success bg-info bg-warning bg-danger');
        bar.addClass('bg-' + color);
    }
}


jQuery(function(){
    /*
     * For the sake keeping the code clean and the examples simple this file
     * contains only the plugin configuration & callbacks.
     * 
     * UI functions ui_* can be located in: demo-ui.js
     */
    var confirmation = CustomConfirmation();
    jQuery.fn.dmUploaderCustom = function(options){
        this.each(function(i, elem){
            // console.log(elem)
            options.url = jQuery(elem).data('upload-url');
            
            jQuery(elem).find('[files-list] [data-confirmation]').forEach(function(a) {
                a.onclick = async function(e){
                    e.preventDefault();
                    window.playAudio();
                    var status = await confirmation.confirm(this.getAttribute('data-confirmation'));
        
                    confirmation.close();
                    if(status) {
                        window.location.replace(this.getAttribute('href'));
                    }
                }
            })

            jQuery(elem).dmUploader(options);
        })
    }

    jQuery('[data-upload-url]').dmUploaderCustom({ 
        maxFileSize: 3000000, // 3 Megs 
        onDragEnter: function(){
            // Happens when dragging something over the DnD area
            this.addClass('active');
        },
        onDragLeave: function(){
            // Happens when dragging something OUT of the DnD area
            this.removeClass('active');
        },
        onInit: function(){
            // Plugin is ready to use
            ui_add_log('Penguin initialized :)', 'info');
        },
        onComplete: function(){
            // All files in the queue are processed (success or error)
            ui_add_log('All pending tranfers finished');
        },
        onNewFile: function(id, file){
            // console.log(this.get(0))
            // When a new file is added using the file selector or the DnD area
            ui_add_log('New file added #' + id);
            ui_multi_add_file(this.get(0), id, file);
        },
        onBeforeUpload: function(id){
            // about tho start uploading a file
            ui_add_log('Starting the upload of #' + id);
            ui_multi_update_file_status(id, 'uploading', 'Uploading...');
            ui_multi_update_file_progress(id, 0, '', true);
        },
        onUploadCanceled: function(id) {
            // Happens when a file is directly canceled by the user.
            ui_multi_update_file_status(id, 'warning', 'Canceled by User');
            ui_multi_update_file_progress(id, 0, 'warning', false);
        },
        onUploadProgress: function(id, percent){
            // Updating file progress
            ui_multi_update_file_progress(id, percent);
        },
        onUploadSuccess: function(id, data){
            // A file was successfully uploaded
            ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));
            ui_add_log('Upload of file #' + id + ' COMPLETED', 'success');
            ui_multi_update_file_status(id, 'success', 'Upload Complete');
            ui_multi_update_file_progress(id, 100, 'success', false);
        
            console.log('ID: ' + id)
            console.log('DATA: ' + data)
        },
        onUploadError: function(id, xhr, status, message){
            ui_multi_update_file_status(id, 'danger', message);
            ui_multi_update_file_progress(id, 0, 'danger', false);  
        },
        onFallbackMode: function(){
            // When the browser doesn't support this plugin :(
            ui_add_log('Plugin cant be used here, running Fallback callback', 'danger');
        },
        onFileSizeError: function(file){
            ui_add_log('File \'' + file.name + '\' cannot be added: size excess limit', 'danger');
        }
    });


    
  });