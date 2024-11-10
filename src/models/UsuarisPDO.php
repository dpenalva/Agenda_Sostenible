<?php
namespace Models;

class UsuarisPDO extends DB {
    public function login($email, $password) {
        try {
            $query = "SELECT id, nom, cognoms, nom_usuari, email, password, rol FROM usuaris WHERE email = :email";
            $stmt = $this->sql->prepare($query);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$user) {
                error_log("Usuario no encontrado: " . $email);
                throw new \Exception("Usuario no encontrado");
            }

            // Verificar la estructura completa del usuario
            error_log("Datos del usuario encontrado: " . print_r($user, true));

            if (!password_verify($password, $user['password'])) {
                error_log("Verificación de contraseña fallida para: " . $email);
                throw new \Exception("Contraseña incorrecta");
            }

            // Log después de verificación exitosa
            error_log("Login exitoso - Usuario: " . $user['nom'] . " - Rol: " . $user['rol']);

            unset($user['password']);
            return $user;

        } catch (\PDOException $e) {
            error_log("Error PDO en login: " . $e->getMessage());
            throw new \Exception("Error al iniciar sesión");
        }
    }

    public function register($data) {
        try {
            $query = "INSERT INTO usuaris (nom, cognoms, nom_usuari, email, password, rol) 
                      VALUES (:nom, :cognoms, :nom_usuari, :email, :password, :rol)";
            $stmt = $this->sql->prepare($query);

            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $rol = $data['rol'] ?? 'user'; // Por defecto será 'user'

            $stmt->execute([
                ':nom' => $data['nom'],
                ':cognoms' => $data['cognoms'],
                ':nom_usuari' => $data['nom_usuari'],
                ':email' => $data['email'],
                ':password' => $hashedPassword,
                ':rol' => $rol
            ]);

            return $this->sql->lastInsertId();
        } catch (\PDOException $e) {
            error_log("Error registering user: " . $e->getMessage());
            throw new \Exception("Error al registrar el usuario");
        }
    }

    public function getByUsername($username) {
        try {
            $query = "SELECT * FROM usuaris WHERE nom_usuari = :username";
            $stmt = $this->sql->prepare($query);
            $stmt->execute([':username' => $username]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error en getByUsername: " . $e->getMessage());
            throw new \Exception("Error al buscar el usuario");
        }
    }

    public function getByEmail($email) {
        try {
            $query = "SELECT id, nom, cognoms, nom_usuari, email, password, rol FROM usuaris WHERE email = :email";
            $stmt = $this->sql->prepare($query);
            $stmt->execute([':email' => $email]);
            
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error getting user by email: " . $e->getMessage());
            throw new \Exception("Error al obtener usuario por email");
        }
    }

    /**
     * Obtiene un usuario por su ID
     */
    public function get($id) {
        try {
            $query = "SELECT 
                        id,
                        nom,
                        cognoms,
                        nom_usuari,
                        email,
                        imatge_perfil,
                        biografia,
                        banner
                    FROM usuaris 
                    WHERE id = :id";
            
            $stmt = $this->sql->prepare($query);
            $stmt->execute([':id' => $id]);
            
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$user) {
                throw new \Exception("Usuario no encontrado");
            }
            
            return $user;
        } catch (\PDOException $e) {
            error_log("Error en get: " . $e->getMessage());
            throw new \Exception("Error al obtener el usuario");
        }
    }

    public function updateProfileImage($userId, $imageUrl) {
        try {
            $query = "UPDATE usuaris SET imatge_perfil = :imageUrl WHERE id = :userId";
            $stmt = $this->sql->prepare($query);
            $stmt->execute([
                ':imageUrl' => $imageUrl,
                ':userId' => $userId
            ]);

            if ($stmt->rowCount() === 0) {
                throw new \Exception("No se pudo actualizar la imagen de perfil");
            }

            return true;
        } catch (\PDOException $e) {
            error_log("Error en updateProfileImage: " . $e->getMessage());
            throw new \Exception("Error al actualizar la imagen de perfil");
        }
    }

    public function updateBanner($userId, $imageUrl) {
        try {
            $query = "UPDATE usuaris SET banner = :imageUrl WHERE id = :userId";
            $stmt = $this->sql->prepare($query);
            $stmt->execute([
                ':imageUrl' => $imageUrl,
                ':userId' => $userId
            ]);
            
            if ($stmt->rowCount() === 0) {
                throw new \Exception("No se pudo actualizar el banner");
            }
            
            return true;
        } catch (\PDOException $e) {
            error_log("Error en updateBanner: " . $e->getMessage());
            throw new \Exception("Error al actualizar el banner");
        }
    }

    public function updateProfile($userId, $data) {
        try {
            $query = "UPDATE usuaris SET 
                      nom = :name,
                      biografia = :bio
                      WHERE id = :userId";
            
            $stmt = $this->sql->prepare($query);
            $stmt->execute([
                ':name' => $data['name'],
                ':bio' => $data['bio'] ?? null,
                ':userId' => $userId
            ]);
            
            if ($stmt->rowCount() === 0) {
                throw new \Exception("No se pudo actualizar el perfil");
            }
            
            return true;
        } catch (\PDOException $e) {
            error_log("Error en updateProfile: " . $e->getMessage());
            throw new \Exception("Error al actualizar el perfil");
        }
    }

    public function isAdmin($userId) {
        try {
            $query = "SELECT rol FROM usuaris WHERE id = :id";
            $stmt = $this->sql->prepare($query);
            $stmt->execute([':id' => $userId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $result && $result['rol'] === 'admin';
        } catch (\PDOException $e) {
            error_log("Error checking admin status: " . $e->getMessage());
            throw new \Exception("Error al verificar el rol del usuario");
        }
    }

    public function getTotalUsers() {
        try {
            $query = "SELECT COUNT(*) as total FROM usuaris";
            $stmt = $this->sql->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $result['total'];
        } catch (\PDOException $e) {
            error_log("Error getting total users: " . $e->getMessage());
            throw new \Exception("Error al obtener el total de usuarios");
        }
    }

    public function getRecentUsers($limit = 5) {
        try {
            $query = "SELECT id, nom, cognoms, email 
                      FROM usuaris 
                      ORDER BY id DESC 
                      LIMIT :limit";
            
            $stmt = $this->sql->prepare($query);
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error getting recent users: " . $e->getMessage());
            throw new \Exception("Error al obtener usuarios recientes");
        }
    }

    public function deleteUser($id) {
        try {
            $query = "DELETE FROM usuaris WHERE id = :id";
            $stmt = $this->sql->prepare($query);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            throw new \Exception("Error al eliminar el usuario");
        }
    }

    public function updateUser($id, $data) {
        try {
            $query = "UPDATE usuaris 
                     SET nom = :nom, 
                         cognoms = :cognoms, 
                         email = :email,
                         nom_usuari = :nom_usuari,
                         biografia = :biografia
                     WHERE id = :id";
            
            $stmt = $this->sql->prepare($query);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->bindValue(':nom', $data['nom']);
            $stmt->bindValue(':cognoms', $data['cognoms']);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':nom_usuari', $data['nom_usuari']);
            $stmt->bindValue(':biografia', $data['biografia'] ?? '');
            
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            throw new \Exception("Error al actualizar el usuario");
        }
    }

    public function getUserById($id) {
        try {
            $query = "SELECT id, nom, cognoms, nom_usuari, email, biografia FROM usuaris WHERE id = :id";
            $stmt = $this->sql->prepare($query);
            $stmt->execute([':id' => $id]);
            
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            // Añadir log para debug
            error_log("Query result: " . print_r($result, true));
            
            if (!$result) {
                throw new \Exception("Usuario no encontrado");
            }
            
            return $result;
        } catch (\PDOException $e) {
            error_log("Error en getUserById: " . $e->getMessage());
            throw new \Exception("Error al obtener el usuario");
        }
    }
} 