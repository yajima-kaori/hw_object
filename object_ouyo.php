<?php

class TableBase
{
  public $dbh;
  public $data;
  public $tableName;

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

 public function delete($id)
 {
    $dbh = $this->dbh;
    $this->tableName = "members";
    $sql= "delete from" . $this->tableName . "where id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id',$id);
    $stmt->execute();
 }


}


class Member extends TableBase
{
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

  public function insert()
  {
    $dbh = $this->dbh;

    $sql= "insert into shop_items(code,name,price,created_at) values(:code,:name,:price,now())";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':code',$this->data['code']);
    $stmt->bindParam(':name',$this->data['name']);
    $stmt->bindParam(':price',$this->data['price']);
    $stmt->execute();

  }

   public function delete($id)
 {
    $this->tableName = "shop_items";
    parent::delete();
 }

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

$shopItem->insert();

$result = $shopItem->findByCode('123456');
var_dump($result);


$shopItem->delete(23);

$member = new Member();
$member->set(array(
  'name' => 'テスト名',
  'age' => 30,
  'email' => 'test@example.com',
));
$result = $member->insert();
$member->delete(118);

$data = $member->findByEmail('test@example.com');
var_dump($data);
