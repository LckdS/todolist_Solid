<?php 

class TodoRepository {
    private $dbConnection;
    
    public function __construct(DBConnection $dbConnection) {
        $this->dbConnection = $dbConnection;
    }
    
    public function add(Todo $todo) {
        $conn = $this->dbConnection->getConnection();
        
        $stmt = $conn->prepare("INSERT INTO todos(title) VALUES(?)");
        $res = $stmt->execute([$todo->getTitle()]);
        
        return $res;
    }
    
    public function updateCheckedStatus($id) {
        $conn = $this->dbConnection->getConnection();
        
        $todos = $conn->prepare("SELECT id, checked FROM todos WHERE id=?");
        $todos->execute([$id]);
        
        $todo = $todos->fetch();
        $uId = $todo['id'];
        $checked = $todo['checked'];
        
        $uChecked = $checked ? 0 : 1;
        
        $res = $conn->query("UPDATE todos SET checked=$uChecked WHERE id=$uId");
        
        return $res ? $checked : "error";
    }
    
    public function delete($id) {
        $conn = $this->dbConnection->getConnection();
        
        $stmt = $conn->prepare("DELETE FROM todos WHERE id=?");
        $res = $stmt->execute([$id]);
        
        return $res ? 1 : 0;
    }
}

?>