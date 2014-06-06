<?php
require('dbconnection.php');
$mongo = DBConnection::instantiate();
$collection = $mongo->getCollection('users');
$users = array(
  array(
    'username' => 'ESFDreamCamp',
    'password' => md5('dashboard'),
    )
  );
foreach($users as $user)
{
  try{
    $collection->insert($user);
  } catch (MongoCursorException $e) {
    die($e->getMessage());
  }
}
echo 'Users created successfully';
