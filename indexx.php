<?php
require 'db_conn.php';

$dbConnection = new DBConnection($sname, $db_name, $uname, $password);
$todoRepository = new TodoRepository($dbConnection);
$todoController = new TodoController($todoRepository);

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        $todoController->addTodo();
    } elseif ($action === 'update') {
        $todoController->updateCheckedStatus();
    } elseif ($action === 'delete') {
        $todoController->deleteTodo();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>To Do List</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div class="main-section">
		<div class="add-section">
			<form action="app/app.php" method="POST" autocomplete="off">
				<?php if(isset($_GET['mess']) && $_GET['mess'] == 'error'){ ?>
					<input type="text" name="title" style="border-color: #ff6666;" placeholder="Su tarea es requerida">
					<button type="submit">Add &nbsp;<span>&#43;</span></button>
				<?php }else{ ?>
					<input type="text" name="title" placeholder="¿Que tienes planeado para hoy?">
					<button type="submit">Agregar &nbsp;<span>&#43;</span></button>
				<?php } ?>
			</form>
		</div>
		<?php
		$todos = $conn->query("SELECT * FROM todos ORDER BY id DESC"); ?>
		<div class="show-todo-section">
			<?php if ($todos->rowCount() <= 0 ) {?>
				<div class="todo-item">
					<div class="empty">
						<img src="img/p.jpg" width="100%">
					</div>
				</div>
			<?php } ?>

			<?php while($todo = $todos->fetch(PDO::FETCH_ASSOC)){?>
				<div class="todo-item">
					<span id="<?php echo $todo['id'];?>"
						class="remove-to-do">x</span>
						<?php if($todo['checked']){ ?>
							<input type="checkbox"
								data-todo-id="<?php echo $todo['id'];?>"
								class="check-box"
								checked  />
							<h2 class="checked"><?php echo $todo['title']?></h2><br>
						<?php }else{ ?>
							<input type="checkbox"
								data-todo-id="<?php echo $todo['id'];?>"
								class="check-box"/>
							<h2><?php echo $todo['title']?></h2><br>
						<?php } ?>
					<small> created: <?php echo $todo['date_time']?></small>
				</div>
			<?php } ?>
		</div>
	</div>
	<script src="js/jquery-3.2.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$('.remove-to-do').click(function(){
				const id = $(this).attr('id');

				$.post("app/remove.php", 
					{
						id: id
					},
					(data)  => {
						if(data){
							$(this).parent().hide(600);
						}
					}
				);
			});
			$(".check-box").click(function(e){
				const id = $(this).attr('data-todo-id');

				$.post('app/check.php', 
					{
						id: id
					},
					(data) => {
						if(data != 'error'){
							const h2 = $(this).next();
							if(data === '1'){
								h2.removeClass('checked');
							}else {
								h2.addClass('checked');
							}
						}
					}
				);
			});
		});
	</script>
</body>
</html>
