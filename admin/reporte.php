<?php include 'includes/session.php'; ?>
<?php include 'includes/slugify.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <?php
                $sql = "SELECT * FROM positions";
                $query = $conn->query($sql);

                echo "<h3>".$query->num_rows."</h3>";
              ?>

              <p>No. de Votos por Candidata</p>
            </div>
            <div class="icon">
              <i class="fa fa-tasks"></i>
            </div>
            <a href="positions.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <?php
                $sql = "SELECT * FROM candidates";
                $query = $conn->query($sql);

                echo "<h3>".$query->num_rows."</h3>";
              ?>
          
              <p>Numero de Candidatas</p>
            </div>
            <div class="icon">
              <i class="fa fa-black-tie"></i>
            </div>
            <a href="candidates.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <?php
                $sql = "SELECT * FROM voters";
                $query = $conn->query($sql);

                echo "<h3>".$query->num_rows."</h3>";
              ?>
             
              <p>Registrados</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="voters.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <?php
                $sql = "SELECT * FROM votes GROUP BY voters_id";
                $query = $conn->query($sql);

                echo "<h3>".$query->num_rows."</h3>";
              ?>

              <p>Total de Votos</p>
            </div>
            <div class="icon">
              <i class="fa fa-edit"></i>
            </div>
            <a href="votes.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
              <div class="table-responsive">
                <table class="table" id="candidate">
                    <thead>
                        <tr>  
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th># Votos</th>
                            <th>Puntos</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                             <th></th><th></th><th>Total:</th><th></th>
                        </tr>
                    </tfoot>
                </table>
        </div>
      </section>
      <!-- right col -->
    </div>

  	<?php include 'includes/footer.php'; ?>

</div>
<!-- ./wrapper -->
<?php include 'includes/scripts.php'; ?>
  <script src="../plugins/datatables/datatables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>

<!-- pdfmake (requerido para PDF) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>

<!-- Botones HTML5 -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script>
  $(function(){
    $('#candidate').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'pdfHtml5',
                text: 'Exportar PDF',
                filename: 'informe_Candidatas',
                title: 'INFORME DE CANDIDATAS',
                orientation: 'portrait',    // vertical
                pageSize: 'A4',
                  customize: function (doc) {
                      // CAMBIAR MÁRGENES DEL PDF
                      doc.pageMargins = [25, 50, 25, 30];  
                      // [izquierda, arriba, derecha, abajo]

                      // CAMBIAR ESTILO DEL TÍTULO
                      doc.styles.title = {
                          fontSize: 18,
                          bold: true,
                          alignment: 'center',
                          margin: [0, 0, 0, 20]  // espacio bajo el título
                      };

                      // AGREGAR SUBTÍTULO
                      doc.content.splice(1, 0, {
                          text: 'Listado generado automáticamente',
                          fontSize: 12,
                          italics: true,
                          alignment: 'center',
                          margin: [0, 0, 0, 20]
                      });

                       var table = doc.content[doc.content.length - 1];

                       table.layout = {
                            paddingTop: function () { return 10; },
                            paddingBottom: function () { return 10; },
                            paddingLeft: function () { return 60; },
                            paddingRigth: function () { return 60; },
                             hLineWidth: function (i, node) {
                                  return 0; // sin líneas horizontales
                              },
                              vLineWidth: function (i, node) {
                                  return 0; // sin líneas verticales
                              },
                              hLineColor: function (i, node) {
                                  return 'white'; // color blanco (opcional)
                              },
                              vLineColor: function (i, node) {
                                  return 'white'; // color blanco (opcional)
                              }
                        };
                  }
            }
        ],
         ajax: {
                url: 'votes_show.php',
                dataSrc: 'data'
            },
          columns: [  { data: 'nombre' },
        { data: 'apellido' },
        { data: 'cantidad' },
        { data: 'puntos' }],
         pageLength: -1,  
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();

            // Función para convertir a número
            var intVal = function (i) {
                return typeof i === 'string'
                    ? i.replace(/[\$,]/g, '') * 1
                    : typeof i === 'number'
                    ? i
                    : 0;
            };

            // Sumamos la columna 2 (cantidad)
            var total = api
                .column(2)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Mostrar total en el footer
            $(api.column(2).footer()).html(total);
        }
    });
});
</script>
</body>
</html>
