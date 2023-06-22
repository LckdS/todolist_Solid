<?php 

class TodoController {
    private $todoRepository;
    
    public function __construct(TodoRepository $todoRepository) {
        $this->todoRepository = $todoRepository;
    }
    
    public function addTodo() {
        if (isset($_POST['title'])) {
            $title = $_POST['title'];
            
            if (empty($title)) {
                header("Location: ../indexx.php?mess=error");
            } else {
                $todo = new Todo($title);
                $res = $this->todoRepository->add($todo);
                
                if ($res) {
                    header("Location: ../indexx.php?mess=success");
                } else {
                    header("Location: ../indexx.php");
                }
                
                exit();
            }
        } else {
            header("Location: ../indexx.php?mess=error");
        }
    }
    
    public function updateCheckedStatus() {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            
            if (empty($id)) {
                echo 'error';
            } else {
                $result = $this->todoRepository->updateCheckedStatus($id);
                echo $result;
                
                exit();
            }
        } else {
            header("Location: ../indexx.php?mess=error");
            exit();
        }
    }
    
    public function deleteTodo() {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            
            if (empty($id)) {
                echo 0;
            } else {
                $result = $this->todoRepository->delete($id);
                echo $result;
                
                exit();
            }
        } else {
            header("Location: ../indexx.php?mess=error");
            exit();
        }
    }
}

?>