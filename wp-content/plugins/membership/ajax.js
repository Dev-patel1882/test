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
					  $('input[type="text"],textarea').val('');
                      $('#addmember').modal('hide');
					  $("#create-alert").removeClass('d-none');
                      $( "#example" ).load( location.href + " #example", () => {
						$("#create-alert").addClass('d-none');;
					  });
	               }else if(msg == "email are already exists"){
                      $("#email_error").html(msg);
	               }
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
				 $('#editmember').modal('hide');
				 $("#update-alert").removeClass('d-none');
				 $( "#example" ).load( location.href + " #example", () => {
					 $("#update-alert").addClass('d-none');
				 });
			}
	   });

	});


	// this function are used to delete recoards

	$(document).on("click","#delete",function(){
		var del_id = $(this).attr("data-id");
		deleteMembership({id: del_id});
	});

	function deleteMembership(data) {
		$("#delete_membership_model").modal('show');
		var deleteBtn = document.getElementById("approve-membership-delete");
		deleteBtn.addEventListener("click", function () {
			$.ajax({
				type: "POST",
				url: '../wp-admin/admin-ajax.php',
				data: data,
				success: function(response){
					var json_msg = JSON.parse(response);
					var msg = json_msg.msg;
					$("#delete_membership_model").modal('hide');
					$("#delete-alert").removeClass('d-none');
					$( "#example" ).load( location.href + " #example", () => {
						$("#delete-alert").addClass('d-none');
					});
				}
			});
		});
	}

	$(document).on("click","#multi-delete-btn",function(){
		var DeleteIDs = $(':checkbox:checked').map(function(){
            return $(this).val();
        }).get();
		if (DeleteIDs.length > 0) {
			deleteMembership({data: DeleteIDs.join()})
		}
	});

	$(document).ready(function () {
		  $('#membership-table').DataTable({
			  "lengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
			  "pageLength": 10,
		  });
    });

});
