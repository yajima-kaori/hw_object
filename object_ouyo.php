<?php

class TableBase
{
  public $dbh;
  public $data;
  public $culom1;
  public $culom2;
  public $culom3;

  public function __construct()
  {
    $this->dbh = $this->connectDatabase();
  }

  public function connectDatabase()
  {
      $dsn = 'mysql:host=localhost;dbname=hw_object;charset=utf8';
      $user ='testuser';
      $password = '9999';

      try
      {
        return new PDO($dsn,$user,$password);
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

    foreach ($this->data as $key => $value)
    {
      $key_array[]=$key;
    }

    $dbh = $this->dbh;

    $sql= "insert into " . $this->tableName ."(" . $this->culom1 . "," . $this->culom2 . "," . $this->culom3 . ",created_at) values(:culom1,:culom2,:culom3,now())";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':culom1',$this->data["$key_array[0]"]);
    $stmt->bindParam(':culom2',$this->data["$key_array[1]"]);
    $stmt->bindParam(':culom3',$this->data["$key_array[2]"]);
    $result = $stmt->execute();

    return $result;
  }

 public function delete($id)
 {
    $dbh = $this->dbh;
    $sql= "delete from " . $this->tableName . " where id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id',$id);
    $stmt->execute();
 }


}


class Member extends TableBase
{
  public $tableName = "members";
  public $culom1 = "name";
  public $culom2 = "age";
  public $culom3 = "email";

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

}

class ShopItem extends TableBase
  {
    public $tableName = "shop_items";
    public $culom1 = "code";
    public $culom2 = "name";
    public $culom3 = "price";

  public function findByCode($code)
  {
    $dbh = $this->dbh;
    $sql= "select * from shop_items where code = :code";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':code',$code);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!empty($row))
        {
          return $row[0];
        }
        return false;
  }

}

$shopItem = new ShopItem();

$shopItem->set(array(
  'code' => '123456',
  'name' => '洗顔',
  'price' => 200,
));

$result = $shopItem->insert();

// $result = $shopItem->findByCode('123456');
// var_dump($result);


// $shopItem->delete(23);

// $member = new Member();
// $member->set(array(
//   'name' => 'テスト名',
//   'age' => 30,
//   'email' => 'test@example.com',
// ));
// $result = $member->insert();
// $member->delete(118);

// $data = $member->findByEmail('test@example.com');
// var_dump($data);
