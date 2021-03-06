<!-- Iniciar a sessão -->
<?php session_start(); ?>

<!-- Inclusão da classe users -->
<?php include 'classes/user_class.php'; ?>

<?php

		# OPERAÇÕES COM O FICHEIRO CSV
    $filename = 'data/users.csv';

      $file = fopen($filename, 'r'); //ler o ficheiro .csv
      while (!feof($file))

      {

        $data = fgetcsv($file, 0, ";"); //ir buscar dados ao ficheiro .csv

        if($data == "") {

            break;

        }


		if($data[0] == $_SESSION['user']) {

		  $user = new User($data[2], $data[3], $data[4], $data[5], $data[6], $data[7]);
		  $nome = $user->getName();

		}

	}

?>

<!DOCTYPE html>
<html lang="en">
<head>

	<!-- MetaTags -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<!-- Incluir folha de estilos -->
	<link type="text/css" rel="stylesheet" href="css/styles.css">

	<!-- Bootstrap CDN -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<!-- Link da google fonts -->
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

	<!-- Font Awesome icons -->
	<script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>

	<!-- Título da página -->
	<title>BusiChat | Área de Gestão</title>
</head>
<body>
	<div id="area_gestao">

		<!-- Área de Gestão -->
		<h1 class="text-center">Bem-vindo à sua Área de Gestão, <?php echo $nome ?></h1>

		<!-- Menu lateral -->
		<div id="sideMenu" class="sidenav">
			<a href="javascript:void(0)" class="closeBtnGestao" onclick="closeNav()">&times;</a>
			<a href="mensagens/nova_msg.php"><i class="fas fa-comment fa-1x"></i> Escrever nova mensagem</a>
			<a href="mensagens/gerir_contactos.php"><i class="fas fa-users fa-1x"></i> Gerir contactos</a>
			<a href="mensagens/historico_msg_recebidas.php"><i class="fas fa-envelope-open fa-1x"></i> Histórico de mensagens recebidas</a>
			<a href="mensagens/historico_msg_enviadas.php"><i class="far fa-envelope fa-1x"></i> Histórico de mensagens enviadas</a>
			<a href='edicao_conta.php'><i class='fas fa-cogs fa-1x'></i> Editar dados da conta</a>

			<?php

				# Obter informação acerca do cargo do utilizador
				$user_cargo = $user->getCargo();

				/* Disponibilizar a opção de registar utilizadores caso a sessão atual
				pertença a um diretor */
				if($user_cargo == 'diretor') {

					echo "<a href='registar.php'><i class='fas fa-user-plus fa-1x'></i> Registar novos utilizadores</a>";

				} else {

					/* Caso a sessão atual não seja a de um diretor, a opção de registar
					novos utilizadores não aparecerá */
					echo "";

				}
			?>

			<a href="logout.php"><i class="fas fa-sign-out-alt fa-1x"></i> Encerrar Sessão</a>
		</div>

		<span onclick="openNav()" class="gerir_conta">&#9776; Gerir conta</span>

		<!-- Shortcut para enviar nova mensagem -->
		<div class="nova_msg_card">
			<div class="imginbox"><h3 class="text-center">Shortcut de mensagem rápida</h3><img src="img/nova_msg.jpg">Envie uma nova mensagem!</div>
			<div class="detalhes_nova_msg">
				<div class="conteudo_nova_msg">

					<?php

					if(!file_exists("data/mensagens.csv")) {

							$file = fopen("data/mensagens.csv", "w");
							fclose($file);

							}


					if($_SERVER["REQUEST_METHOD"] == "POST") {

							$file = fopen("data/mensagens.csv", "r");

							while (!feof($file)) {

								$data = fgetcsv($file, 0, ";");

								if($data[0]==""){

									break;

								}

								$message = new Message($data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);

							}

							fclose($file);

							$from = $_SESSION['username'];

							$message = new Message($_POST['to'], $from, $_POST['subject'],
								 $_POST['text'], $_POST['date_hour']);

							$file = fopen("data/mensagens.csv", "a");

							$message = (array)$message;

							fputcsv($file, $message, ";");

							fclose($file);

									echo "<strong><p class='text-center alert alert-primary'>Mensagem enviada com sucesso!</p></strong>";

					}

			?>



					<form action="" method="post">
						<h3>Envie uma nova mensagem rápida!</h3>

						<!-- Assunto da mensagem -->
						<div class="form-inline shortcut_msg">
							<h5>Assunto da mensagem rápida</h5>
							<input type="text" name="subject" placeholder="Insira uma breve descrição da mensagem aqui!" class="form-control input_assunto_msg">
						</div>

						<!-- Destinatários da mensagem -->
						<div class="form-inline shortcut_msg">
							<h5>Destinatários da mensagem</h5>
							<select class="custom-select col-sm-6" name="to">

								<?php

									$filename = 'data/users.csv';

										$file = fopen($filename, 'r'); //ler o ficheiro
										while (!feof($file))

										{

										  $data = fgetcsv($file, 0, ";"); //ir buscar dados ao csv

										if($data[0] == "") {

										break;



									}

										  $user = new User($data[2], $data[3], $data[4],$data[5],$data[6],$data[7]);

										  if(!$data[0] == $_SESSION['user']) {

										  $nome = $user->getName();

										  echo "<option value='$nome'>$nome</option><br>";

										}

									}

								?>
							</select>
						</div>

							<input type="submit" name="submeter_msg_rapida" value="Enviar mensagem!" class="btn btn-primary">

					</form>
				</div>
			</div>
		</div>
</div>

<!-- JavaScript -->
<script type="text/javascript" src="js/main.js"></script>

</body>
</html>