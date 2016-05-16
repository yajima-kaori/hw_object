<?php

require_once('config.php');
require_once('functions.php');


class TableBase
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

 public function delete($e)
 {
    $dbh = connectDatabase();
    $sql= "delete from members where id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id',$e);
    $stmt->execute();
 }


}


class Member extends TableBase
{
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

}

class ShopItem extends TableBase
{

  public $code;
  public $name;
  public $price;

  public function set($data)
  {
    $this->code = $data['code'];
    $this->name = $data['name'];
    $this->price = $data['price'];
    var_dump($data);
  }

  public function insert()
  {
    $dbh = connectDatabase();
    $sql = "select * from shop_items order by id desc limit 1";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $row1 = $stmt->fetch();
    // var_dump($row1["id"]);

    $sql= "insert into shop_items(code,name,price,created_at) values(:code,:name,:price,now())";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':code',$this->code);
    $stmt->bindParam(':name',$this->name);
    $stmt->bindParam(':price',$this->price);
    $stmt->execute();

    $sql = "select * from shop_items order by id desc limit 1";
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

   public function delete($e)
 {
    $dbh = connectDatabase();
    $sql= "delete from shop_items where id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id',$e);
    $stmt->execute();
 }

  public function findByCode($code)
  {
    $dbh = connectDatabase();
    $sql= "select * from shop_items where code = :code";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':code',$code);
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

}

$shopItem = new ShopItem();

// $shopItem->set(array(
//   'code' => '123456',
//   'name' => '洗顔',
//   'price' => 200,
// ));

// $shopItem->insert();

$result = $shopItem->findByCode('123456');
var_dump($result);

$member = new Member();
$data = $member->findByEmail('test@example.com');
var_dump($data);
