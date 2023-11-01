<?php

require __DIR__ . '/../dto/UserDTO.php';
require __DIR__ . '/../util/PdoManager.php';

class UserRepository {
    private $pdo;

    public function __construct() {
        $this->pdo = PdoManager::getPdo();
    }

    /**
     * ユーザーを新しく登録する
     * @param string $userId ユーザーID
     * @param string $name ユーザー名
     * @param string $email メールアドレス
     * @param string $password パスワードの平文
     * @return void
     */
    public function insert($userId,$name,$email,$password) {
        // SQLの準備
        $sql = 'INSERT INTO users (id,name,email,password_hash) VALUES (:id, :name, :email, :password_hash)';

        // パスワードのハッシュ化
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQLの実行
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password_hash', $hashed_password, PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
     * IDをもとにユーザー検索を行う
     * @param string $id
     * @return UserDTO|null
     */
    public function findById($id) {
        // SQLの準備
        $sql = 'SELECT * FROM users WHERE id = :id';

        // SQLの実行
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $user = $this->toDto($result);
        return $user;
    }

    /**
     * emailをもとにユーザー検索を行う
     * @param string $email
     * @return UserDTO|null
     */
    public function findByEmail($email) {
        // SQLの準備
        $sql = 'SELECT * FROM users WHERE mail = :email';

        // SQLの実行
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $user = $this->toDto($result);
        return $user;
    }


    /**
     * Dtoに登録されたIDに一致するユーザー情報を更新する
     * @param UserDto $dto
     * @return void
     */
    public function updateById($dto) {
        // SQLの準備
        $sql = 'UPDATE users SET name = :name, email = :email, password_hash = :password_hash WHERE id = :id';

        // SQLの実行
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name', $dto->getName(), PDO::PARAM_STR);
        $stmt->bindParam(':email', $dto->getEmail(), PDO::PARAM_STR);
        $stmt->bindParam(':password_hash', $dto->getPasswordHash(), PDO::PARAM_STR);
        $stmt->bindParam(':id', $dto->getId(), PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
     * Dtoに登録されたメールアドレスに一致するユーザー情報を更新する
     * @param UserDto $dto
     * @return void
     */
    public function updateByEmail($dto) {
        // SQLの準備
        $sql = 'UPDATE users SET name = :name, email = :email, password_hash = :password_hash WHERE email = :email';

        // SQLの実行
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name', $dto->getName(), PDO::PARAM_STR);
        $stmt->bindParam(':email', $dto->getEmail(), PDO::PARAM_STR);
        $stmt->bindParam(':password_hash', $dto->getPasswordHash(), PDO::PARAM_STR);
        $stmt->bindParam(':email', $dto->getEmail(), PDO::PARAM_STR);
        $stmt->execute();
    }


    /**
     * DBアクセスの結果をDtoに変換する
     * @param object $result
     * @return UserDTO
     */
    private function toDto($result) {
        $row = $result->fetch_assoc();
        $user = new UserDTO($row['id'], $row['email'], $row['password_hash'], $row['name'], $row["registered_at"]);
        return $user;
    }
}



?>
