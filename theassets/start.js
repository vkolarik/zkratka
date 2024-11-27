// Created by STRd6
// MIT License
// jquery.paste_image_reader.js
/*var camera = ((new Audio()).canPlayType("audio/ogg; codecs=vorbis")==="") ? 'https://zkra.tk/theassets/shoot.wav' : 'https://zkra.tk/theassets/shoot.ogg';
var snd = new Audio(camera); // buffers automatically when created
function snapshoot() {
  snd.play();
}
*/
(function($) {
  var defaults;
  $.event.fix = (function(originalFix) {
    return function(event) {
      event = originalFix.apply(this, arguments);
      if (event.type.indexOf('copy') === 0 || event.type.indexOf('paste') === 0) {
        event.clipboardData = event.originalEvent.clipboardData;
      }
      return event;
    };
  })($.event.fix);
  defaults = {
    callback: $.noop,
    matchType: /image.*/
  };
  return $.fn.pasteImageReader = function(options) {
    if (typeof options === "function") {
      options = {
        callback: options
      };
    }
    options = $.extend({}, defaults, options);
    return this.each(function() {
      var $this, element;
      element = this;
      $this = $(this);
      return $this.bind('paste', function(event) {
        var clipboardData, found;
        found = false;
        clipboardData = event.clipboardData;
        return Array.prototype.forEach.call(clipboardData.types, function(type, i) {
          var file, reader;
          if (found) {
            return;
          }
          if (type.match(options.matchType) || clipboardData.items[i].type.match(options.matchType)) {
            file = clipboardData.items[i].getAsFile();
            reader = new FileReader();
            reader.onload = function(evt) {
              return options.callback.call(element, {
                dataURL: evt.target.result,
                event: evt,
                file: file,
                name: file.name
              });
            };
            reader.readAsDataURL(file);
            return found = true;
          }else{
			//$("#imgfile").val(file);
			var prop = document.getElementById('main_link').style.display;
			if(type.match(/text.*/)){
				if(prop != 'block'){
					alert('Ve schránce nebyl nelezen obrázek');
				}
			}else{
				alert('Ve schránce nebyl nelezen obrázek');
			}
		  }
        });
      });
    });
  };
})(jQuery);



$("html").pasteImageReader(function(results) {
  var dataURL, filename;
  filename = results.filename, dataURL = results.dataURL;

  $('#img_form').val(dataURL);

  if(results.file.size > 10000000){
	  alert('Obrázek přesahuje povolenou velikost');
	  return;
  }
  $('#size_form').val(results.file.size);
  $('#type_form').val(results.file.type);
  
  document.getElementById("formular").submit();
});

var $data, $size, $type, $test, $width, $height;
$(function() {
  $data = $('.data');
  $size = $('.size');
  $type = $('.type');
  $test = $('#test');
  $width = $('#width');
  $height = $('#height');
  $('.target').on('click', function() {
    var $this = $(this);
    var bi = $.this.css('background-image');
    if (bi!='none') {
        $data.text(bi.substr(4,bi.length-6));
    }
                    
                    
    $('.active').removeClass('active');
    $this.addClass('active');
    
    $this.toggleClass('contain');
    
    $width.val($this.data('width'));
    $height.val($this.data('height'));
    if ($this.hasClass('contain')) {
      $this.css({'width':$this.data('width'), 'height':$this.data('height'), 'z-index':'10'})
    } else {
      $this.css({'width':'', 'height':'', 'z-index':''})
    }
    
  })
})

function create_link(){
	if($('#url').val() != ""){
		var RegExp = /^(http|https):\/\/(([a-zA-Z0-9$\-_.+!*'(),;:&=]|%[0-9a-fA-F]{2})+@)?(((25[0-5]|2[0-4][0-9]|[0-1][0-9][0-9]|[1-9][0-9]|[0-9])(\.(25[0-5]|2[0-4][0-9]|[0-1][0-9][0-9]|[1-9][0-9]|[0-9])){3})|localhost|([a-zA-Z0-9\-\u00C0-\u017F]+\.)+([a-zA-Z]{2,}))(:[0-9]+)?(\/(([a-zA-Z0-9$\-_.+!*'(),;:@&=]|%[0-9a-fA-F]{2})*(\/([a-zA-Z0-9$\-_.+!*'(),;:@&=]|%[0-9a-fA-F]{2})*)*)?(\?([a-zA-Z0-9$\-_.+!*'(),;:@&=\/?]|%[0-9a-fA-F]{2})*)?(\#([a-zA-Z0-9$\-_.+!*'(),;:@&=\/?]|%[0-9a-fA-F]{2})*)?)?$/;
		if(RegExp.test($('#url').val())){
			//
			var response = '';
			$.ajax({ type: "GET",   
				url: "link.php",
				async: false,
				data: { 
					url: $( "#url" ).val()
				},
				success : function(text)
			{
             response = text;
			}
			});

			$("#link_response").html(response);
		}else{
			alert('Invalid url format');
		}
	}else{
		alert('Nejprve musis vyplnit pole');
	}
}
/*
function modal(msg){
	$("#popupmsg").html(msg);
	$("#popup").modal();
}
*/