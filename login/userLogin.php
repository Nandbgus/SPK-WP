<?php
session_start();
include '../tools/connection.php';

if (isset($_SESSION["login_user"])) {
	header("location: ../home/home.php");
	exit();
}

if (isset($_POST['login_user'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	$stmt = $conn->prepare("SELECT * FROM ta_user WHERE user_nama = ?");
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows === 1) {
		$row = $result->fetch_assoc();
		if ($password === $row["user_password"]) {
			session_regenerate_id(true);
			$_SESSION["login_user"] = true;
			$_SESSION["user_id"] = $row["user_id"];
			$_SESSION["user_nama"] = $row["user_nama"];
			header("location: ../home/home.php");
			exit();
		}
	}
	$error = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User Login</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		body {
			background: linear-gradient(135deg, #89f7fe, #66a6ff);
			height: 100vh;
			display: flex;
			justify-content: center;
			align-items: center;
			font-family: 'Poppins', sans-serif;
		}
		.glass-card {
			background: rgba(255, 255, 255, 0.2);
			backdrop-filter: blur(12px);
			border-radius: 16px;
			box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
			padding: 2rem;
			width: 100%;
			max-width: 420px;
			color: #fff;
		}
		.glass-card h3 {
			text-align: center;
			font-weight: 600;
			margin-bottom: 1.5rem;
		}
		.form-control {
			border: none;
			border-radius: 10px;
			padding: 12px;
		}
		.btn-custom {
			background: #007bff;
			border: none;
			color: white;
			border-radius: 10px;
			width: 100%;
			padding: 10px;
			transition: all 0.3s ease;
		}
		.btn-custom:hover {
			background: #0056b3;
			transform: translateY(-2px);
		}
		.alert-error {
			background: rgba(255, 0, 0, 0.2);
			color: #ffe6e6;
			border-radius: 8px;
			padding: 8px;
			text-align: center;
			margin-bottom: 1rem;
		}
	</style>
</head>
<body>
	<div class="glass-card">
		<h3>Login Pengguna</h3>

		<?php if (isset($error)) : ?>
			<div class="alert-error">Username atau Password salah!</div>
		<?php endif; ?>

		<form action="" method="post">
			<div class="mb-3">
				<label for="username" class="form-label">Username</label>
				<input type="text" class="form-control" name="username" required autofocus autocomplete="off">
			</div>
			<div class="mb-3">
				<label for="password" class="form-label">Password</label>
				<input type="password" class="form-control" name="password" required>
			</div>
			<button type="submit" name="login_user" class="btn-custom mt-2">Masuk</button>
		</form>
	</div>
</body>
</html>
