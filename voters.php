
<?php 
include 'includes/header.php'; 
if(isset($_SESSION['error'])){
    echo "
        <div class='alert alert-danger alert-dismissible fade show' style='margin: 15px;'>
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
            ".$_SESSION['error']."
        </div>
    ";
    unset($_SESSION['error']);
}

if(isset($_SESSION['success'])){
    echo "
        <div class='alert alert-success alert-dismissible fade show' style='margin: 15px;'>
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
            ".$_SESSION['success']."
        </div>
    ";
    unset($_SESSION['success']);
}
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
</div>
<div class="container">
    <div class="row" style="margin-top: 50px;">
            <div class="col-md-8">
                <h5 class="text-white text-center" style="margin-bottom: 50px;"><strong>INGRESE SUS DATOS DE VOTANTE</strong></h5>
                <form class="form-horizontal" id="uservoter">
                        <div class="form-group">
                            <label for="firstname" class="col-sm-6 control-label"><center>Dni</center></label>
                            <div class="col-sm-6">
                              <input type="text" class="form-control" id="dni" name="dni" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="col-sm-6 control-label"><center>Nombres</center></label>
                            <div class="col-sm-6">
                              <input type="text" class="form-control" id="firstname" name="firstname" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lastname" class="col-sm-6 control-label"><center>Apellidos</center></label>

                            <div class="col-sm-6">
                              <input type="text" class="form-control" id="lastname" name="lastname" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-6 control-label"><center>Contraseña</center></label>

                            <div class="col-sm-6">
                              <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="index.php" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Volver al Inicio</a>
                            <button type="submit" class="btn btn-primary btn-flat" name="add"><i class="fa fa-save"></i> Guardar</button>
                        </div>
                        
                  </form>
                  <div id="responseMessage"></div>

            </div>
            
            <div class="col-md-4">
                       <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <p class="text-center" style="margin-bottom: 50px;">
                      <strong>ASI VAN LAS ELECCIONES</strong>
                    </p>

                    <div class="progress-group">
                      Add Products to Cart
                      <span class="float-right"><b>160</b>/200</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-primary" style="width: 80%"></div>
                      </div>
                    </div>
                    <!-- /.progress-group -->

                    <div class="progress-group">
                      Complete Purchase
                      <span class="float-right"><b>310</b>/400</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-danger" style="width: 75%"></div>
                      </div>
                    </div>

                    <!-- /.progress-group -->
                    <div class="progress-group">
                      <span class="progress-text">Visit Premium Page</span>
                      <span class="float-right"><b>480</b>/800</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-success" style="width: 60%"></div>
                      </div>
                    </div>

                    <!-- /.progress-group -->
                    <div class="progress-group">
                      Send Inquiries
                      <span class="float-right"><b>250</b>/500</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-warning" style="width: 50%"></div>
                      </div>
                    </div>
                    <!-- /.progress-group -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
            </div>
    </div>

</div>
<?php include 'includes/scripts.php'; ?>
</body>
<script>
$(function(){
  $(document).on('submit', '#uservoter', function(e){
    e.preventDefault();
    var formData = new FormData(this); 
    $.ajax({
          url: 'admin/voters_add.php', // archivo que procesa
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          dataType: 'json', // esperamos JSON de vuelta
          success: function(response){
              // muestra el mensaje en la página
              if(response.status == 'success'){
                  $('#responseMessage').html('<div class="alert alert-success">'+response.message+'</div>');
                  $('#voterForm')[0].reset(); // limpia el formulario
              } else {
                  $('#responseMessage').html('<div class="alert alert-danger">'+response.message+'</div>');
              }
          },
          error: function(xhr, status, error){
              $('#responseMessage').html('<div class="alert alert-danger">Error en la solicitud: '+error+'</div>');
          }
      });
    });
});
</script>
</html>
