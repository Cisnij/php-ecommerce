<?php
class User {
    private PDO $pdo;

    public function __construct(PDO $pdo) { //tạo đối tượng dpo để querry trực tiếp
        $this->pdo = $pdo;
    }

    public function findByUsername(string $username): ?array { //trả về array or null
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1"); //lệnh prepare chuẩn bị code
        $stmt->execute([$username]); //truyền đối tượng vào prepare và thực thi
        $user = $stmt->fetch(); // thực thi xong lấy data về
        return $user ? $user : null; //trả user if có user k thì nul
    }

    public function register(array $userData): bool { //thành công thì true và ngc lại
        $hash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (fullname, username, email, password, phone, role, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $userData['fullname'],
            $userData['username'],
            $userData['email'],
            $hash,
            $userData['phone'],
            $userData['role'] ?? 'user'
        ]);
    }

    public function getAll(): array {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);// trả về mảng key value
    }

    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ? $user : null;
    }
    public function update(array $userData): bool {
        $hash = password_hash($userData['password'], PASSWORD_DEFAULT);// chuyển đổi thành mã băm, tham số 1 là pass usser input 2 là hàm thực thi băm
        $sql = "UPDATE users SET 
                fullname = ?, username = ?, email = ?, password = ?, phone = ?, role = ?, updated_at = NOW()
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $userData['fullname'],
            $userData['username'],
            $userData['email'],
            $hash,
            $userData['phone'],
            $userData['role'],
            $userData['id']
        ]);
    }
    public function updateWithoutPassword(array $userData): bool {
        $sql = "UPDATE users SET 
                fullname = ?, username = ?, email = ?, phone = ?, role = ?, updated_at = NOW()
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $userData['fullname'],
            $userData['username'],
            $userData['email'],
            $userData['phone'],
            $userData['role'],
            $userData['id']
        ]);
    }
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function countAll(): int {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM users");
        $result = $stmt->fetch();
        return (int)$result['total'];
    }
}