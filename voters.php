
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
<div class="container" style="background-color: rgb(242, 242, 242); margin-top: 50px;">
    <h3 class="text-white text-center" style="margin-top: 50px;"><strong>MISS PASCO 2025</strong></h3>
    <div class="row" style="margin-top: 50px;">
            <div class="col-md-8">
                <h5 class="text-white text-center" style="margin-bottom: 20px;"><strong>INGRESE SUS DATOS DE VOTANTE</strong></h5>
                <form class="form-horizontal" id="uservoter">
                        <div class="form-group">
                            <label for="firstname" class="col-sm-6 control-label"><center>Dni</center></label>
                            <div class="col-sm-6">
                              <input type="text" class="form-control" id="dni" name="dni" required>
                            </div>
                        </div>
                           <div class="form-group">
                            <div class="col-sm-12">
                                <div  style="display: flex;justify-content:end;">
                                   <a href="index.php" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Volver al Inicio</a>
                                  <button type="submit" class="btn btn-primary btn-flat" name="add"><i class="fa fa-save"></i> Registrar</button>
                                </div>                            
                              </div>
                        </div>
                        <div class="modal-footer">
                       
                        </div>
                        
                  </form>
                  <div id="responseMessage"></div>

            </div>
            
            <div class="col-md-4">
                       <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <p class="text-center" style="margin-bottom: 20px;">
                      <strong>CANTIDAD DE CONCURSANTE</strong>
                    </p>

                    <div class="progress-group">
                      Miss Pasco
                      <span class="float-right"><b>12</b>/12</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-primary" style="width: 80%"></div>
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
