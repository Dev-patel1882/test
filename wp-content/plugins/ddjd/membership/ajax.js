$(document).ready(function(){


	// this function are used to add new member data
	$(document).on("submit","#add_member",function(e){
		e.preventDefault();
       var email = $("#email").val();
       var number = $("#mem_num").val();
       
       if(email == ""){
           $("#email_error").html("email are required");  
       }else if(number == ""){
           $("#number_error").html("email are required");  
       }else{
           $.ajax({
			url: '../wp-admin/admin-ajax.php',
		    type: "post",
			data: {
				   addmember:'addmember',
			       email:email,
			       number:number
			    } ,
			success: function (response) {
				
	               var json_msg = JSON.parse(response);
	               var msg = json_msg.msg;

	               if(msg == "success"){
                      $('#addmember').modal('hide');
                      $( "#example" ).load( location.href + " #example" );
	               	  alert("recoards are success added");
	               	  

	               }else if(msg == "email are already exists"){
                      $("#email_error").html(msg);  
	               }
	               
				   
			},error: function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}
		   });
       }
       
       
	});


    // this function are used to edit data
	$(document).on("click","#edit",function(){
		var id = $(this).attr("data-id");
		var email = $(this).attr("data-email");
		var number = $(this).attr("data-number");
		


        $("#edit_id").val(id);
		$("#edit_email").val(email);
		$("#edit_mem_num").val(number);
	});



	// this function are used to  update member

	$(document).on("submit","#update-member",function(e){
		e.preventDefault();
		var id = $("#edit_id").val();
		var email = $("#edit_email").val();
		var number = $("#edit_mem_num").val();
       
         $.ajax({
			url: '../wp-admin/admin-ajax.php',
		    type: "post",
			data: {
				   action:'update_member',
				   update_id:id,
			       update_email:email,
			       update_number:number
			    } ,
			success: function (response) {
                 $('#editmember').modal('hide');
				 var json_msg = JSON.parse(response);
	             var msg = json_msg.msg;
				 if(msg == "membership are successfully updaed"){
                      $( "#example" ).load( location.href + " #example" );
                    alert(msg);

				}
				
	               
				   
			},error: function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}
		   });

	});


	// this function are used to delete recoards

	$(document).on("click","#delete",function(){
		var del_id = $(this).attr("data-id");



		$.ajax({
            type: "POST",
            url: '../wp-admin/admin-ajax.php',
            dataType: 'text',
            data: { action: 'delete_member' , id: del_id},
            success: function(response){
               var json_msg = JSON.parse(response);
	           var msg = json_msg.msg;
               if(msg == "recoard are successfully deleted"){
                 	alert(msg);
                 	$( "#example" ).load( location.href + " #example" );
               }
            }
          });
	});



	$(document).on("click","#save_value",function(){
		var DeleteIDs = $(':checkbox:checked').map(function(){
            return $(this).val();
        });

        $.ajax({
				url: "../wp-admin/admin-ajax.php",
				type: "post",
				data: {data:DeleteIDs.get().join()} ,
				success: function (response) {
	                var json_msg = JSON.parse(response);
                  var msg = json_msg.msg;
                  if(msg == "Recoards are successfully deleted"){
                     alert(msg);
                     $( "#example" ).load( location.href + " #example" );
                       
                  }
	                
				   
				},
				error: function(jqXHR, textStatus, errorThrown) {
				   console.log(textStatus, errorThrown);
				}
		   });
	});

	$(document).ready(function () {
              $('#example').DataTable();
    });


    // download test csv

    
    jQuery("#download-btn").click(function() {
        var csvUrl = "path/to/csv/file.csv";
        var link = document.createElement("a");
        link.setAttribute("href", csvUrl);
        link.setAttribute("download", "data.csv");
        link.style.display = "none";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
})