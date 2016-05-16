<?php

require_once('config.php');
require_once('functions.php');


class Member
{
  public $name;
  public $age;
  public $email;

  public function set($data)
  {
    $this->name = $data['name'];
    $this->age = $data['age'];
    $this->email = $data['email'];
  }

  public function insert()
  {

    $dbh = connectDatabase();

    $sql = "select * from members order by id desc limit 1";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $row1 = $stmt->fetch();
    // var_dump($row1["id"]);

    $sql= "insert into members(name,age,email,created_at) values(:name,:age,:email,now())";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':name',$this->name);
    $stmt->bindParam(':age',$this->age);
    $stmt->bindParam(':email',$this->email);
    $stmt->execute();

    $sql = "select * from members order by id desc limit 1";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $row2 = $stmt->fetch();
    // var_dump($row2["id"]);

    //insert前後で､idが増えているかで判定を行ったが別の方法ありそう
    if(!$row1["id"] == $row2["id"])
    {
      echo 'true';
    }
    else
    {
      echo 'false';
    }

  }

  public function findByEmail($a)
  {
    $dbh = connectDatabase();
    $sql= "select * from members where email = :email";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':email',$a);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ここから､コピペのため解説ほしい
    $obj = new stdClass;
    foreach ($row as $key => $val) {
      $obj->$key = $val;
      return $val;
    }
    // ここまで

    if(!$row)
    {
      return 'false';
    }
 }

 public function delete($e)
 {
    $dbh = connectDatabase();
    $sql= "sdelete from members where id = :id";
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
var_dump($data['id']);
$result = $member->delete($data['id']);

$data = $member->findByEmail('test@example.com');