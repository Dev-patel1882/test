

<?php





function delete_member(){
  
  if(isset($_POST['id'])){
     $id = $_POST['id'];
     global $wpdb;

     $table_name = $wpdb->prefix . "membership";
     $wpdb->query( 
              "DELETE FROM $table_name
               WHERE id = $id;
              "       
          );

     $msg = array("msg"=>"recoard are successfully deleted");
     echo json_encode($msg);
     exit;
  }

}
delete_member();

// this code in get post request data and update membership table
if(isset($_POST['update_email'])){
	 	$id = $_POST['update_id'];
	 	$email = $_POST['update_email'];
	 	$number = $_POST['update_number'];
     

	    global $wpdb;
	 	$table_name = $wpdb->prefix . "membership";
	 	$wpdb->query($wpdb->prepare("UPDATE $table_name SET email='$email',number=$number WHERE id=$id"));
       $msg = array("msg"=>"membership are successfully updated");
       echo json_encode($msg); 
       exit();
}


// this function are used to get post request data and add new member

	if(isset($_POST['addmember'])){
       $email = $_POST['email'];
       $number = $_POST['number'];
     
       global $wpdb;
       $table_name = $wpdb->prefix . "membership";

       $wpdb->query( "SELECT * FROM $table_name WHERE email = '$email' " );
       $num_rows = $wpdb->num_rows;
       
     
       if($num_rows == 1){
         $count_rec = array('msg'=>'email are already exists');
         echo json_encode($count_rec);
         exit();

       }else{
          $wpdb->insert(
                $table_name,
                array(
                    
                    'email' => $email,
                    'number' => $number,
                )
          );
         
         $a = array('msg'=>'success');
         echo json_encode($a);
         
         exit();
       }

        

	}
	




// this function are used to multiple recodards delete the membership table data
if(isset($_POST['data'])){

$table_name = $wpdb->prefix . "membership";

           $ids_str = $_POST['data'];
          
          $array = explode(' ', $ids_str);
           
          
           $ids = implode(",", $array);
          
          
           $wpdb->query( 
	            "DELETE FROM $table_name
	             WHERE id IN($ids)
	            "       
	        );

          $msg = array("msg"=>"Recoards are successfully deleted");
          echo json_encode($msg);
          exit;
           
        }



        
function parse_csv( $file ) {
    $csv_data = array();
    if ( ( $handle = fopen( $file, "r" ) ) !== FALSE ) {
        while ( ( $data = fgetcsv( $handle, 1000, "," ) ) !== FALSE ) {
            $csv_data[] = $data;
        }
        fclose( $handle );
    }
    return $csv_data;
}




// this code are used to add csv file and update csv file
if(isset($_POST['submit'])){
       global $wpdb;
       $table_name = $wpdb->prefix . "membership";
       
       $file = $_FILES['csv_file']['tmp_name'];
        
       $csv_data = parse_csv( $file );



       foreach ( $csv_data as $data ) {
       $existing_row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE email = %s AND number = %s", $row[0], $row[1] ) );

             
          if ( $existing_row ) {
              // The row already exists, so skip it
              continue;
          } else {
              // The row does not exist, so insert it into the database
              $wpdb->insert(
                $table_name,
                array(
                    
                    'email' => $data[0],
                    'number' => $data[1],
                )
            );
          }
     
         }   
            

  }

      
     
       
       
   

function member_list() {
     

    ?>
    
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
     

    <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
     <script type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/membership/ajax.js"></script>
      <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/membership/ajax.js" rel="stylesheet" />


      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <div class="wrap">
        


        <div class="row mt-4 mb-4">
        	<div class="col-sm-3 mt-4">
        		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadcsvModal">import csv file</button>
        		<input type="button" id="save_value" name="save_value" value="Delete" class="btn btn-danger" />

        	</div>
          <div class="col-sm-3 mt-4">
          
            <a class="btn btn-success" href="../wp-content/plugins/membership/membership.csv" download>
              Download sample CSV
            </a>

          </div>
        	<div class="col-sm-3 mt-4">
        		<button type="button" class="btn btn-info text-light" data-toggle="modal" data-target="#addmember" >Add new member</button>
        		
        	</div>
        	
        </div>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "membership";

        $rows = $wpdb->get_results("SELECT id,email,number from $table_name");
        ?>
        <table class='wp-list-table widefat fixed striped posts table table-hover' id="example">
            <thead>
		    <tr>
		      <th scope="col">#</th>
		      <th>Multiple Delete</th>
		     
		      <th scope="col">Email</th>
		      <th scope="col">Membership Number</th>
		      
		      <th scope="col" style="width:250px;">Action</th>
		       <!-- <th scope="col">Delete</th> -->
		    </tr>
		  </thead>
		  <tbody>
		  <?php  $sno = 0; ?>
		   <?php foreach ($rows as $row) {  $sno++;?>
		    
		    <tr id="empids<?php echo $row->id; ?>">
		      <th scope="row"><?php echo $sno ?></th>
		       <td>
		        <div class="form-check">
				  <input name="selector[]" class="form-check-input" type="checkbox" value="<?php echo $row->id; ?>" id="ID-<?php echo $row->id; ?>">
				  
				</div>
		      </td>
		      <td><?php echo $row->email; ?></td>
		      <td><?php echo $row->number; ?></td>
		      
           <td style="width: 200px;">
             <button id="edit" class="btn btn-success" data-toggle="modal" data-target="#editmember" data-id="<?php echo $row->id ?>" data-email="<?php echo $row->email ?>" data-number="<?php echo $row->number ?>"><i class="fas fa-edit"></i>&nbsp;Update</button>
             <button id="delete" class="btn btn-danger" data-id="<?php echo $row->id ?>"><i class="fa-solid fa-trash"></i>&nbsp;Delete</button>
           </td>
		      
		     
		    </tr>
                 <?php } ?>
            
        
           
           
        </table>
    </div>



 <!-- upload csv file bootstrap modal -->

   <div class="modal fade" id="uploadcsvModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Your Membership </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" >
      <div class="modal-body">
        
          <div class="form-group">
          
            <input type="file" class="form-control" name="csv_file">
          </div>
          
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="btn btn-primary">Upload</button>
      </div>
      </form>
    </div>
  </div>
</div>


<!-- end -->


<!-- start add membership modal -->

<div class="modal fade" id="addmember" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="add_member">
      <div class="modal-body">
        
          <div class="form-group">
            <label for="email" class="col-form-label">Email:</label>
            <input type="text" class="form-control" id="email">
             <small id="email_error" class="form-text  text-danger"></small>
          </div>

          <div class="form-group">
            <label for="mem_num" class="col-form-label">Membership Number:</label>
            <input type="Number" class="form-control" id="mem_num">
            <small id="number_error" class="form-text  text-danger"></small>
          </div>
          
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- end -->


<!-- update modeal -->
<div class="modal fade" id="editmember" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="update-member">
      <div class="modal-body">
         <input type="text" id="edit_id" hidden>
          <div class="form-group">
            <label for="email" class="col-form-label">Email:</label>
            <input type="text" class="form-control" id="edit_email">
          </div>

          <div class="form-group">
            <label for="mem_num" class="col-form-label">Membership Number:</label>
            <input type="Number" class="form-control" id="edit_mem_num">
          </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update member</button>
      </div>
      </form>
    </div>
  </div>
</div>


    <?php
}
