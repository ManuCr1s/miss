
<?php 
include 'includes/header.php'; 
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
              if(response.status == '0'){
                    Swal.fire({
                      title: 'Error!',
                      text: response.message,
                      icon: 'error',
                      confirmButtonText: 'Cool'
                    })

              } else if(response.status == '1') {
                    if(response.apiresponse.status == '200'){
                       Swal.fire({
                        title: "Confirmacion:",
                           html: `
                                  <h5>¿Esta seguro que desea realizar su registro con este DNI?</h5>
                                  <h3>${response.apiresponse.data.numero}</h3>
                                  <input  style="display:none;"  id="swal-nombre" class="swal2-input" placeholder="Nombres" value="${response.apiresponse.data.nombres}">
                                  <input  style="display:none;"  id="swal-paterno" class="swal2-input" placeholder="Nombres" value="${response.apiresponse.data.apellido_paterno}">
                                  <input  style="display:none;"  id="swal-apellidos" class="swal2-input" placeholder="Apellidos" value="${response.apiresponse.data.apellido_paterno} ${response.apiresponse.data.apellido_materno}">
                                    <input style="display:none;"  id="swal-dni" value="${response.apiresponse.data.numero}">
                                `,
                        focusConfirm: false,
                        showCancelButton: true,
                        allowOutsideClick: () => false, 
                        allowEscapeKey: false,
                        preConfirm: async (login) => {
                              const nombre = document.getElementById("swal-nombre").value;
                              const apellidos = document.getElementById("swal-apellidos").value;
                              const paterno = document.getElementById("swal-paterno").value;
                              const dni = document.getElementById("swal-dni").value;
                        /*       if(!nombre || !apellidos){
                                  Swal.showValidationMessage("Los campos no pueden estar vacíos");
                                  return false; // evita que cierre el Swal
                              } */

                              return { nombre, apellidos, dni,paterno };
                      },
                      allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                          if (result.isConfirmed) {
                           $.ajax({
                                  url: 'admin/voters_user.php',
                                  type: 'POST',
                                  data: {
                                      nombre: result.value.nombre,
                                      apellidos: result.value.apellidos,
                                      dni:result.value.dni,
                                      paterno:result.value.paterno,
                                  },
                                  dataType: 'json',
                                  success: function(saveResp){
                                      if(saveResp.status == 'success'){
                                          Swal.fire({
                                              icon: 'success',
                                              title: 'Datos guardados!',
                                              text: saveResp.message,
                                              allowOutsideClick: false
                                          }).then(() => {
                                              location.reload();
                                          });
                                        } else {
                                          Swal.fire({
                                              icon: 'error',
                                              title: 'Error al guardar!',
                                              text: saveResp.message
                                          });
                                      }
                                  },
                                  error: function(xhr, status, error){
                                      Swal.fire({
                                          icon: 'error',
                                          title: 'Error en la solicitud!',
                                          text: error,
                                          allowOutsideClick: false,
                                      });
                                  }
                              });
                          }
                      });
                    }else{
                           Swal.fire({
                            title: 'Error!',
                            text: 'Ingrese dni correcto',
                            icon: 'error',
                            confirmButtonText: 'Cool'
                          })
                    }
           
              }else{
                    Swal.fire({
                      title: 'Error!',
                      text: response.message,
                      icon: 'error',
                      confirmButtonText: 'Cool'
                    })
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
