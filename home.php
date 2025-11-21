<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	      	<?php
	      		$parse = parse_ini_file('admin/config.ini', FALSE, INI_SCANNER_RAW);
    			$title = isset($parse['election_title']) ? htmlspecialchars($parse['election_title']) : 'MISS PASCO 2025';
	      	?>
	      	<h1 class="page-header text-center title"><b>MISS PASCO 2025</b></h1>
	        <div class="row">
	        	<div class="col-sm-10 col-sm-offset-1">
	        		<?php
				        if(isset($_SESSION['error'])){
				        	?>
				        	<div class="alert alert-danger alert-dismissible">
				        		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					        	<ul>
					        		<?php
					        			foreach($_SESSION['error'] as $error){
					        				echo "
					        					<li>".$error."</li>
					        				";
					        			}
					        		?>
					        	</ul>
					        </div>
				        	<?php
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
 
				    <div class="alert alert-danger alert-dismissible" id="alert" style="display:none;">
		        		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			        	<span class="message"></span>
			        </div>

				    <?php
						$voter_dni = isset($voter['dni']) ? $conn->real_escape_string($voter['dni']) : null;
						if($voter_dni){
							$stmt = $conn->prepare("SELECT * FROM votes WHERE voters_id = ?");
							$stmt->bind_param("s", $voter_dni);
							$stmt->execute();
							$vquery = $stmt->get_result();
							if($vquery->num_rows > 0){?>
									<div class="text-center">
										<h3>Al parecer ya tienes a tu candidata</h3>
										<a href="#view" data-toggle="modal" class="btn btn-flat btn-primary btn-lg">Tu candidata es:</a>
									</div>
									<?php
							}else{?>
								<form method="POST" id="ballotForm" action="submit_ballot.php" novalidate>
									<?php
									include 'includes/slugify.php';
									$sql = "SELECT * FROM positions ORDER BY priority ASC";
									$query = $conn->query($sql);
									while ($row = $query->fetch_assoc()) {
										$position_id = (int)$row['id'];
										$slug = slugify($row['description']);
										$max_vote = (int)$row['max_vote'];
										
										// Obtener candidatos con consulta preparada
										$cstmt = $conn->prepare("SELECT * FROM candidates WHERE position_id = ?");
										$cstmt->bind_param("i", $position_id);
										$cstmt->execute();
										$cquery = $cstmt->get_result();

										$candidate = '';
										while ($crow = $cquery->fetch_assoc()) {
											$candidate_id = (int)$crow['id'];
											$checked = '';
											if (isset($_SESSION['post'][$slug])) {
												$value = $_SESSION['post'][$slug];
												if (is_array($value) && in_array($candidate_id, $value)) {
													$checked = 'checked';
												} elseif ($value == $candidate_id) {
													$checked = 'checked';
												}
											}

											$input_name = $max_vote > 1 ? $slug . "[]" : $slug;
											$input_type = $max_vote > 1 ? 'checkbox' : 'radio';
											$image = !empty($crow['photo']) ? 'images/' . htmlspecialchars($crow['photo']) : 'images/profile.jpg';

											$candidate .= sprintf(
												'<li>
													<input type="%s" class="flat-red %s" name="%s" value="%d" %s>
													<img src="%s" height="300px" width="250px" class="clist" alt="Foto de %s %s">
													<span class="cname clist">%s %s</span>
												</li>',
												$input_type,
												htmlspecialchars($slug),
												htmlspecialchars($input_name),
												$candidate_id,
												$checked,
												htmlspecialchars($image),
												htmlspecialchars($crow['firstname']),
												htmlspecialchars($crow['lastname']),
												htmlspecialchars($crow['firstname']),
												htmlspecialchars($crow['lastname'])
											);
										}
										$instruct = $max_vote > 1 ? 'Puedes seleccionar solo una participante' : 'Select only one candidate';
										echo '
											<div class="row">
												<div class="col-xs-12">
													<div class="box box-solid" id="' . $position_id . '">
														<div class="box-header with-border">
															<h3 class="box-title"><b>' . htmlspecialchars($row['description']) . '</b></h3>
														</div>
														<div class="box-body">
															<p>' . $instruct . '
																<span class="pull-right">
																	<button type="button" class="btn btn-success btn-sm btn-flat reset" data-desc="' . htmlspecialchars($slug) . '">
																		<i class="fa fa-refresh"></i> Reset
																	</button>
																</span>
															</p>
															<div id="candidate_list">
																<ul>
																	' . $candidate . '
																</ul>
															</div>
														</div>
													</div>
												</div>
											</div>
										';
									}
									?>
										<div class="text-center">
										<button type="button" class="btn btn-success btn-flat" id="preview">
											<i class="fa fa-file-text"></i> Preview
										</button> 
										<button type="submit" class="btn btn-primary btn-flat" name="vote">
											<i class="fa fa-check-square-o"></i> Submit
										</button>
									</div>
								</form>
								<?php
							}
							$stmt->close();
						}else {
							echo '<div class="alert alert-warning">No se ha identificado correctamente al votante.</div>';
						}
						?>
	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>
  	<?php include 'includes/ballot_modal.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
	$('.content').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});

	$(document).on('click', '.reset', function(e){
	    e.preventDefault();
	    var desc = $(this).data('desc');
	    $('.'+desc).iCheck('uncheck');
	});

	$(document).on('click', '.platform', function(e){
		e.preventDefault();
		$('#platform').modal('show');
		var platform = $(this).data('platform');
		var fullname = $(this).data('fullname');
		$('.candidate').html(fullname);
		$('#plat_view').html(platform);
	});

	$('#preview').click(function(e){
		e.preventDefault();
		var form = $('#ballotForm').serialize();
		if(form == ''){
			$('.message').html('You must vote atleast one candidate');
			$('#alert').show();
		}
		else{
			$.ajax({
				type: 'POST',
				url: 'preview.php',
				data: form,
				dataType: 'json',
				success: function(response){
					if(response.error){
						var errmsg = '';
						var messages = response.message;
						for (i in messages) {
							errmsg += messages[i]; 
						}
						$('.message').html(errmsg);
						$('#alert').show();
					}
					else{
						$('#preview_modal').modal('show');
						$('#preview_body').html(response.list);
					}
				}
			});
		}
		
	});

});
</script>
</body>
</html>