jQuery(document).ready(function(){

	function windowOffset(elem){
		var iTop = elem.offset().top;
		var iLeft = elem.offset().left;
		var res = {
			top: iTop - jQuery(window).scrollTop(),
			left: iLeft - jQuery(window).scrollLeft()
		}
		return res;
	} 


	//Inserting required elements.
	var iEditHTML = '<div class="iEdit-img-edit"><canvas class="iEdit-img-edit-can"></canvas><canvas class="iEdit-img-edit-process-can"></canvas><div class="iEdit-img-edit-select"><div class="iEdit-img-edit-select-resize"></div></div><div class="iEdit-img-edit-act iEdit-img-edit-save"> Done </div><div class="iEdit-img-edit-act iEdit-img-edit-cancel"> Cancel </div></div>';
	jQuery("body").append(iEditHTML);
	
	
	//Main Image Editor Object
	window.iEdit = {

		//Caching Selectors
		can: jQuery('.iEdit-img-edit-can')[0],
		processCan: jQuery('.iEdit-img-edit-process-can')[0],
		selectionBox: jQuery('.iEdit-img-edit-select'),
		container: jQuery('.iEdit-img-edit'),
		saveBtn: jQuery(".iEdit-img-edit-save"),
		cancelBtn: jQuery('.iEdit-img-edit-cancel'),

		//Internal Properties
		drag: false,
		resize: false,
		square: true,
		status: false,
		grcx: null,
		grcy: null,
		callback: null,
		imageType: null,
		imageQuality: 1,

		//Open the Image Editor with appropriate settings
		open: function(imgObj, widthDim, heightDim, callback, imageType, imageQuality){

			this.drag = false,
            this.resize = false;
            
            if(widthDim && heightDim){
                this.square = false;
                this.widthDim = widthDim;
                this.heightDim = heightDim;
            } else {
                this.square = widthDim || false;
            }
			
			this.imageQuality = imageQuality || 1;
			if(imageType == "jpeg" || imageType == "png" || imageType == "gif" || imageType == "bmp"){ //JPG and any other would default to JPEG//
				this.imageType = imageType || "jpeg";
			} else {
				this.imageType = "jpeg";	
			}

			//niu = Not In Use
			this.grcx = "niu",
			this.grcy = "niu",
			
			//Specifyinf user callback
			this.callback = callback,
			this.status = true;

			var ctx = this.can.getContext("2d");

			//Shwoing the conatiner on screen
			iEdit.container.css("display","block").stop().animate({"opacity":"1"});

			var img = new Image();
			var that =  this;

			//Draw the image on the visible canvas depending on the aspect ratio of the image.
			jQuery(img).on("load", function(){

                if(img.width > img.height){
					that.can.width = img.width;
					that.can.height = img.height;

					that.can.style.width = (jQuery(window).width()/2*1)+"px"; 
					that.can.style.height = (img.height*((jQuery(window).width()/2*1)/img.width))+"px";
					
					ctx.fillStyle = '#fff'; 
					ctx.fillRect(0, 0, that.can.width, that.can.height);

					ctx.drawImage(img, 0, 0, that.can.width, that.can.height);

					iEdit.selectionBox.height(jQuery(that.can).height()-20);
					iEdit.selectionBox.width(jQuery(that.can).height()-20);

					iEdit.selectionBox.css({'left': ((jQuery(window).width()/2) - jQuery(that.can).height()/2) + 10  + 'px' ,'top': jQuery(window).height()/2 - jQuery(that.can).height()/2 - 15 + 'px' });

				} else if(img.width < img.height){

					that.can.width = img.width;
					that.can.height = img.height;

					that.can.style.width = (img.width*((jQuery(window).height()/3*2)/img.height)) + "px";
					that.can.style.height = (jQuery(window).height()/3*2) + "px"; 

					ctx.fillStyle = '#fff'; 
					ctx.fillRect(0, 0, that.can.width, that.can.height);

					ctx.drawImage(img, 0, 0, that.can.width, that.can.height);

					iEdit.selectionBox.height(jQuery(that.can).width()-20);
					iEdit.selectionBox.width(jQuery(that.can).width()-20);

					iEdit.selectionBox.css({'left': ((jQuery(window).width()/2) - jQuery(that.can).width()/2) + 10  + 'px' ,'top': jQuery(window).height()/2 - jQuery(that.can).width()/2 - 15 + 'px' });
				} else {
					that.can.width = img.width;
					that.can.height = img.height;

					that.can.style.width = (jQuery(window).height()/4.8*3.3) + "px";
					that.can.style.height = (jQuery(window).height()/4.8*3.3) + "px";					

					ctx.fillStyle = '#fff'; 
					ctx.fillRect(0, 0, that.can.width, that.can.height);

					ctx.drawImage(img, 0, 0, that.can.width, that.can.height);

					iEdit.selectionBox.height(jQuery(that.can).width()-20);
					iEdit.selectionBox.width(jQuery(that.can).width()-20);
				
					iEdit.selectionBox.css({'left': ((jQuery(window).width()/2) - jQuery(that.can).width()/2) + 10  + 'px' ,'top': jQuery(window).height()/2 - jQuery(that.can).width()/2 - 15 + 'px' });
				}
			});
			
			img.src = URL.createObjectURL(imgObj);
		},

		//Close the image editor and reset the settings.
		close: function(){
			this.drag = false;
			this.resize = false;
			this.square = true;
			this.status = false;
			this.grcx = undefined;
			this.grcy = undefined;
			this.callback = undefined;

			this.can.height = 0;
			this.can.width = 0;

			this.processCan.height = 0;
			this.processCan.width = 0;

			var pCtx = this.processCan.getContext("2d");			
			var ctx = this.can.getContext("2d");

			ctx.clearRect(0, 0, 0, 0);
			pCtx.clearRect(0, 0, 0, 0);
		
			iEdit.selectionBox.css({
				"height":'0px',
				"width":'0px',				
			});		

			iEdit.container.stop().animate({
				"opacity":"0"
			}, 300);

			setTimeout(function(){
				iEdit.container.css({"display":"none"});
			}, 300);

		}
	}

	//Set flags to stop trachong mouse movement.
	jQuery(document).on("mouseup",function(){
		iEdit.drag = false;
		iEdit.resize = false;	

		iEdit.grcx = 'niu';
		iEdit.grcy = 'niu';
	});


	//Set flags to start trachong mouse movement.
	iEdit.selectionBox.on("mousedown",function(e){
		var that = jQuery(this);

		var rcx = e.clientX - windowOffset(that).left;
		var rcy = e.clientY - windowOffset(that).top;

		iEdit.grcx = 'niu';
		iEdit.grcy = 'niu';

		if( (iEdit.selectionBox.width() - rcx <= 28) && (iEdit.selectionBox.height() - rcy <= 28)){
			iEdit.drag = false;
			iEdit.resize = true;
		} else {
			iEdit.drag = true;
			iEdit.resize = false;
		}
	});


	//Track mouse movements when the flags are set.
	jQuery(document).on('mousemove', function(e){

		var rcx = e.clientX - windowOffset(iEdit.selectionBox).left;
		var rcy = e.clientY - windowOffset(iEdit.selectionBox).top;

		if(iEdit.drag === true && iEdit.status){

			if(iEdit.grcx === 'niu'){
				iEdit.grcx = rcx;
			}

			if(iEdit.grcy === 'niu'){
				iEdit.grcy = rcy;
			}

			var xMove = e.clientX - iEdit.grcx;
			var yMove = e.clientY - iEdit.grcy;


			if( (xMove + iEdit.selectionBox.width() >= jQuery(iEdit.can).width() + windowOffset(jQuery(iEdit.can)).left) || xMove <= windowOffset(jQuery(iEdit.can)).left){
				if(xMove <= windowOffset(jQuery(iEdit.can)).left){
					iEdit.selectionBox.css({"left":windowOffset(jQuery(iEdit.can)).left+"px"});
				}else{
					iEdit.selectionBox.css({"left":windowOffset(jQuery(iEdit.can)).left + jQuery(iEdit.can).width() - iEdit.selectionBox.width() + "px"});						
				}
			}else{
				iEdit.selectionBox.css({"left":xMove+"px"});
			}


			if((yMove + iEdit.selectionBox.height() >= jQuery(iEdit.can).height() + windowOffset(jQuery(iEdit.can)).top) || (yMove <= windowOffset(jQuery(iEdit.can)).top) ){
				if(yMove <= windowOffset(jQuery(iEdit.can)).top){
					iEdit.selectionBox.css({"top":windowOffset(jQuery(iEdit.can)).top+"px"});
				}else{
					iEdit.selectionBox.css({"top":windowOffset(jQuery(iEdit.can)).top + jQuery(iEdit.can).height() - iEdit.selectionBox.height() + "px"});
				}
			}else{
				iEdit.selectionBox.css({"top":yMove+"px"});
			}

		}else if(iEdit.resize === true && iEdit.status){

			var nWidth = rcx;
			var nHeight = rcy;

			if(iEdit.square){
				if(nWidth >= nHeight){//Width is the dominating dimension; 
					nHeight = nWidth;
					if(nWidth < 100){
						nWidth = 100;
						nHeight = 100;						
					}
				}else{//Height is the dominating dimension; 
					nWidth = nHeight;
					if(nHeight < 100){
						nWidth = 100;
						nHeight = 100;
					}
				}				

				if((nWidth + windowOffset(iEdit.selectionBox).left) >= jQuery(iEdit.can).width() + windowOffset(jQuery(iEdit.can)).left){
					nWidth = (windowOffset(jQuery(iEdit.can)).left + jQuery(iEdit.can).width()) - (windowOffset(iEdit.selectionBox).left);
					if(windowOffset(iEdit.selectionBox).top + nWidth > jQuery(iEdit.can).height() + windowOffset(jQuery(iEdit.can)).top){
						nWidth = (windowOffset(jQuery(iEdit.can)).top + jQuery(iEdit.can).height()) - (windowOffset(iEdit.selectionBox).top);
					}
					nHeight = nWidth;
				}else if((nHeight + windowOffset(iEdit.selectionBox).top) >= jQuery(iEdit.can).height() + windowOffset(jQuery(iEdit.can)).top){
					nHeight = (windowOffset(jQuery(iEdit.can)).top + jQuery(iEdit.can).height()) - (windowOffset(iEdit.selectionBox).top);
					if(windowOffset(iEdit.selectionBox).left + nHeight > jQuery(iEdit.can).width() + windowOffset(jQuery(iEdit.can)).left){
						nHeight = (windowOffset(jQuery(iEdit.can)).left + jQuery(iEdit.can).width()) - (windowOffset(iEdit.selectionBox).left);
					}
					nWidth = nHeight;
				}


			} else if(iEdit.widthDim && iEdit.heightDim){
				// if(nWidth >= nHeight){//Width is the dominating dimension; 
				// 	nHeight = nWidth;
				// 	if(nWidth < 100){
				// 		nWidth = 100;
				// 		nHeight = 100;						
				// 	}
				// }else{//Height is the dominating dimension; 
				// 	nWidth = nHeight;
				// 	if(nHeight < 100){
				// 		nWidth = 100;
				// 		nHeight = 100;
				// 	}
                // }	
                
                nWidth = (iEdit.widthDim/iEdit.heightDim)*nHeight;

				if((nWidth + windowOffset(iEdit.selectionBox).left) >= jQuery(iEdit.can).width() + windowOffset(jQuery(iEdit.can)).left){
					nWidth = (windowOffset(jQuery(iEdit.can)).left + jQuery(iEdit.can).width()) - (windowOffset(iEdit.selectionBox).left);
					if(windowOffset(iEdit.selectionBox).top + nWidth > jQuery(iEdit.can).height() + windowOffset(jQuery(iEdit.can)).top){
						nWidth = (windowOffset(jQuery(iEdit.can)).top + jQuery(iEdit.can).height()) - (windowOffset(iEdit.selectionBox).top);
					}
					nHeight = nWidth/(iEdit.widthDim/iEdit.heightDim);
				}else if((nHeight + windowOffset(iEdit.selectionBox).top) >= jQuery(iEdit.can).height() + windowOffset(jQuery(iEdit.can)).top){
					nHeight = (windowOffset(jQuery(iEdit.can)).top + jQuery(iEdit.can).height()) - (windowOffset(iEdit.selectionBox).top);
					if(windowOffset(iEdit.selectionBox).left + nHeight > jQuery(iEdit.can).width() + windowOffset(jQuery(iEdit.can)).left){
						nHeight = (windowOffset(jQuery(iEdit.can)).left + jQuery(iEdit.can).width()) - (windowOffset(iEdit.selectionBox).left);
					}
					nWidth = (iEdit.widthDim/iEdit.heightDim)*nHeight;
				}
			} else {

				if(nWidth <= 100){
					nWidth = 100;
				}
				if(nHeight <= 100){
					nHeight = 100;
				}			
				if(e.clientX >= jQuery(iEdit.can).width() + windowOffset(jQuery(iEdit.can)).left){    //REASON: nWidth + windowOffset(iEdit.selectionBox).left = e.clientX;
					nWidth = (windowOffset(jQuery(iEdit.can)).left + jQuery(iEdit.can).width()) - (windowOffset(iEdit.selectionBox).left);
				}
				if(e.clientY >= jQuery(iEdit.can).height() + windowOffset(jQuery(iEdit.can)).top){	//REASON: Same logic as nWidth
					nHeight = (windowOffset(jQuery(iEdit.can)).top + jQuery(iEdit.can).height()) - (windowOffset(iEdit.selectionBox).top);
				}

			}


			iEdit.selectionBox.css({
				"width":nWidth+"px",
				"height":nHeight+"px",				
			});
	
		}

	});

	//Process the selected region and return it as an image to the user defined callback.
	iEdit.saveBtn.on("click", function(){

		if(iEdit.callback == undefined){
			iEdit.close();
			return;
		}

		var ratio = iEdit.can.width/jQuery(iEdit.can).width();

		var h = iEdit.selectionBox.height() * ratio;
		var w = iEdit.selectionBox.width() * ratio;		
		var x = (windowOffset(iEdit.selectionBox).left - windowOffset(jQuery(iEdit.can)).left) * ratio;
		var y = (windowOffset(iEdit.selectionBox).top - windowOffset(jQuery(iEdit.can)).top) * ratio;		

		iEdit.processCan.height = h;
		iEdit.processCan.width = w;		
		
		var pCtx = iEdit.processCan.getContext("2d");

		pCtx.drawImage(iEdit.can, x, y, w, h, 0, 0, w, h);


		iEdit.callback(iEdit.processCan.toDataURL("image/"+iEdit.imageType, iEdit.imageQuality));
		iEdit.close();

	});

	//Close the canvas without processing the image on cancel.
	iEdit.cancelBtn.on("click", function(){
		iEdit.close();
	});

	//Setup canvas when window is resized. 
	jQuery(window).on("resize", function(){
		if(iEdit.status){
			iEdit.selectionBox.css({'left': ((jQuery(window).width()/2) - jQuery(iEdit.can).height()/2) + 10  + 'px' ,'top': jQuery(window).height()/2 - jQuery(iEdit.can).height()/2 + 10 + 'px' });
		}
	});	
});