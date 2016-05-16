<?php


class Member
{
  public $data;
  public $dbh;

  public function __construct()
  {
      $this->dbh = $this->connectDatabase();

  }

  public function connectDatabase()
  {
      define('DSN','mysql:host=localhost;dbname=hw_object;charset=utf8');
      define('USER','testuser');
      define('PASSWORD','9999');

      try
      {
        return new PDO(DSN,USER,PASSWORD);
      }
      catch(PDOException $e)
      {
        echo $e->getMessage();
        exit;
      }
  }


  public function set($data)
  {
    $this->data = $data;
  }

  public function insert()
  {
    $dbh = $this->dbh;
    $sql= "insert into members(name,age,email,created_at) values(:name,:age,:email,now())";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':name',$this->data['name']);
    $stmt->bindParam(':age',$this->data['age']);
    $stmt->bindParam(':email',$this->data['email']);
    $result = $stmt->execute();

    return $result;
  }

  public function findByEmail($a)
  {
    $dbh = $this->dbh;
    $sql= "select * from members where email = :email";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':email',$a);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($row))
    {
      return $row[0];
    }
    return false;
 }

 public function delete($e)
 {
    $dbh = $this->dbh;
    $sql= "delete from members where id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id',$e);
    $stmt->execute();
 }


}


$member = new Member();
// メンバーのデータをセットします。
$member->set(array(
  'name' => 'テスト名',
  'age' => 30,
  'email' => 'test@example.com',
));

$result = $member->insert();

// $data = $member->findByEmail('test@example.com');
// var_dump($data['id']);
// $result = $member->delete($data['id']);

// $data = $member->findByEmail('test@example.com');