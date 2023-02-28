<div class="page-body">
    <div class="row">
 
       <div class="col-md-12 col-xl-12">
          <div class="card">
             <div class="card-block">
                <div class="card-block table-border-style">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="m-b-10">Feedbacks</h5>
                        </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table">
                         <thead>
                            <tr>
                               <th>Name</th>
                               <th>Email</th>
                               <th>Mobile</th>
                               <th>Message</th>
                               <th>Service / Page</th>
                               <th>Action</th>
                            </tr>
                         </thead>
                         <tbody>
                            <?php 
                            foreach ($feedbacks as $feedback) {
                              ?>
                              <tr>
                                  <td><?php echo $feedback->name; ?></td>
                                  <td><?php echo $feedback->email; ?></td>
                                  <td><?php echo $feedback->mobile; ?></td>
                                  <td><?php echo $feedback->message; ?></td>
                                  <td><?php echo $feedback->type; ?></td>
                                  <td>
                                      <?php echo Form::open(['route' => array('feedback.destroy', $feedback->feedback_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
                                          <button type="submit" class="btn btn-danger"><span class="pcoded-micon"><i class="ti-trash"></i></span></button>
                                      </form>
                                  </td>

                              </tr>
                            <?php } ?>          
                         </tbody>
                      </table>
                   </div>
                   <?php echo $feedbacks->links(); ?>
                </div>
             </div>
          </div>
       </div>
       
    </div>
 </div>
 <div id="styleSelector">
 </div>