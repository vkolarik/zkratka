    var clipboard = new Clipboard('.btn');
    clipboard.on('success', function(e) {
        console.log(e);
    });
    clipboard.on('error', function(e) {
        console.log(e);
    });
	$( ".btn" ).trigger( "click" );
	
$( document ).ready(function() {
	$( ".timeoutselect" ).change(function () {
		//$( "#state" ).text( 'changing' );
		$.ajax({
			url: "timeout.php",
			type: "get", //send it through get method
			data: { 
				address: $("#sel_address").val(), 
				value: $( ".timeoutselect" ).val()
		},
		success: function(response) {
			//$( "#state" ).text( 'success' );
		},
		error: function(xhr) {
			//$( "#state" ).text( 'error' );
		}
		});
	});

	$( "#imgfile" ).change(function () {

		var upload_size = document.getElementById('imgfile').files[0].size;
		if(upload_size > 10000000){
			alert('Soubor přesahuje povolenou velikost');
			$("#imgfile").val('');
			return;
		}
		var val = $("#imgfile").val();
		if (!val.match(/(?:gif|jpg|png|bmp|jpeg)$/)) { /*!val.match(/(?:gif|jpg|png|bmp|jpeg)$/)*/
			alert("Soubor není správného typu (gif|jpg|png|bmp|jpeg) nebo je poškozený");
			$("#imgfile").val('');
			return;
		}else{
			$("#upload_size").val(upload_size);
			document.getElementById("upload_form").submit();
		}
	});
});
document.getElementById("link_submit").addEventListener("click", function(event){
    event.preventDefault()
	create_link();
});
document.getElementById("link_form").addEventListener("submit", function(event){
    event.preventDefault()
	create_link();
});
//3 types
document.getElementById("img_btn").addEventListener("click", function(event){
    event.preventDefault()
	$("#main_img").css( "display", "block" );
	$("#img_btn").removeClass("btn-default");
	$("#img_btn").addClass("btn-primary");
	$("#main_upload").css( "display", "none" );
	$("#upload_btn").removeClass("btn-primary");
	$("#upload_btn").addClass("btn-default");
	$("#main_link").css( "display", "none" );
	$("#link_btn").removeClass("btn-primary");
	$("#link_btn").addClass("btn-default");;
});
document.getElementById("upload_btn").addEventListener("click", function(event){
    event.preventDefault()
	$("#main_img").css( "display", "none" );
	$("#img_btn").removeClass("btn-primary");
	$("#img_btn").addClass("btn-default");
	$("#main_upload").css( "display", "block" );
	$("#upload_btn").removeClass("btn-default");
	$("#upload_btn").addClass("btn-primary");
	$("#main_link").css( "display", "none" );
	$("#link_btn").removeClass("btn-primary");
	$("#link_btn").addClass("btn-default");;
});
document.getElementById("link_btn").addEventListener("click", function(event){
    event.preventDefault()
	$("#main_img").css( "display", "none" );
	$("#img_btn").removeClass("btn-primary");
	$("#img_btn").addClass("btn-default");
	$("#main_upload").css( "display", "none" );
	$("#upload_btn").removeClass("btn-primary");
	$("#upload_btn").addClass("btn-default");
	$("#main_link").css( "display", "block" );
	$("#link_btn").removeClass("btn-default");
	$("#link_btn").addClass("btn-primary");
});
