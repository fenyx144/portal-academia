<?php require_once "vistas/parte_superior.php"?>

<!--INICIO del cont principal-->

<div class="container">
    <div class="main-section">
		<div class="dashbord dashbord-green">
			<div class="icon-section">
				<i class="fa fa-book" aria-hidden="true"></i><br>
				<small>Cursos</small>
				<p><br></p>
			</div>
			<div class="detail-section">
				<a class="nav-link" href="cursos.php">
					<span>Ir a la sección</span>
				</a>
			</div>
		</div>
		<div class="dashbord dashbord-orange">
			<div class="icon-section">
                <i class="fa fa-graduation-cap"></i><br>
				<small>Profesores</small>
				<p><br></p>
			</div>
			<div class="detail-section">
				<a class="nav-link" href="profesores.php">
					<span>Ir a la sección</span>
				</a>
			</div>
		</div>
		
		<div class="dashbord dashbord-red">
			<div class="icon-section">
				<i class="fa fa-user" aria-hidden="true"></i><br>
				<small>Estudiantes</small>
				<p><br></p>
			</div>
			<div class="detail-section">
				<a class="nav-link" href="estudiantes.php">
					<span>Ir a la sección</span>
				</a>
			</div>
		</div>
		<div class="dashbord dashbord-skyblue">
			<div class="icon-section">
				<i class="fa fa-users" aria-hidden="true"></i><br>
				<small>Grupos</small>
				<p><br></p>
			</div>
			<div class="detail-section">
				<a class="nav-link"  href="grupos.php">
					<span>Ir a la sección</span>
				</a>
			</div>
		</div>
		
	</div>   
</div>

<!--FIN del cont principal-->
<?php require_once "vistas/parte_inferior.php" ?>
<script type="text/javascript" src="scripts/mainProfesores.js"></script>
<script type="text/javascript" src="scripts/cursosProfesor.js"></script>
</body>

</html>