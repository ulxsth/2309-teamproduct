<?php
require_once dirname(__FILE__, 2) . "/repository/UserRepository.php";

function findUserById($id) {
  $repository = new UserRepository();
  return $repository->findById($id);
}
?>
