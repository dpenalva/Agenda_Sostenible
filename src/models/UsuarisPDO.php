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
            // Verificar si el email o nombre de usuario ya existe
            $query = "SELECT COUNT(*) FROM usuaris WHERE email = :email OR nom_usuari = :username";
            $stmt = $this->sql->prepare($query);
            $stmt->execute([
                ':email' => $data['email'],
                ':username' => $data['username']
            ]);
            
            if ((int)$stmt->fetchColumn() > 0) {
                throw new \Exception("El email o nombre de usuario ya está registrado");
            }

            // Insertar nuevo usuario
            $query = "INSERT INTO usuaris (nom, cognoms, nom_usuari, email, password, rol) 
                     VALUES (:nom, :cognoms, :nom_usuari, :email, :password, 'user')";
            
            $stmt = $this->sql->prepare($query);
            $result = $stmt->execute([
                ':nom' => $data['nombre'],
                ':cognoms' => $data['apellido'],
                ':nom_usuari' => $data['username'],
                ':email' => $data['email'],
                ':password' => password_hash($data['password'], PASSWORD_DEFAULT)
            ]);

            if (!$result) {
                throw new \Exception("Error al registrar el usuario");
            }
            
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
            $query = "SELECT id, nom, cognoms, email, nom_usuari, biografia, rol 
                     FROM usuaris 
                     WHERE id = :id";
            
            $stmt = $this->sql->prepare($query);
            $stmt->execute([':id' => $id]);
            
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$result) {
                throw new \Exception("Usuario no encontrado");
            }
            
            return $result;
        } catch (\PDOException $e) {
            error_log("Error getting user: " . $e->getMessage());
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
                         biografia = :biografia,
                         rol = :rol
                     WHERE id = :id";
            
            $stmt = $this->sql->prepare($query);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->bindValue(':nom', $data['nom']);
            $stmt->bindValue(':cognoms', $data['cognoms']);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':nom_usuari', $data['nom_usuari']);
            $stmt->bindValue(':biografia', $data['biografia'] ?? '');
            $stmt->bindValue(':rol', $data['rol']);
            
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            throw new \Exception("Error al actualizar el usuario");
        }
    }

    public function getUserById($id) {
        try {
            $query = "SELECT id, nom, cognoms, nom_usuari, email, biografia, rol 
                     FROM usuaris 
                     WHERE id = :id 
                     LIMIT 1";

            $stmt = $this->sql->prepare($query);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$result) {
                throw new \Exception("Usuario no encontrado");
            }
            
            // Limpiar los datos antes de devolverlos
            return array_map(function($value) {
                return is_string($value) ? trim(strip_tags($value)) : $value;
            }, $result);

        } catch (\PDOException $e) {
            throw new \Exception("Error al obtener el usuario");
        }
    }

    public function createUser($data) {
        try {
            // Verificar si el email existe
            $query = "SELECT COUNT(*) FROM usuaris WHERE email = :email";
            $stmt = $this->sql->prepare($query);
            $stmt->execute([':email' => $data['email']]);
            
            if ((int)$stmt->fetchColumn() > 0) {
                throw new \Exception("El email ya está registrado");
            }

            // Si el email no existe, proceder con la inserción
            $query = "INSERT INTO usuaris (nom, cognoms, email, nom_usuari, password, rol) 
                     VALUES (:nom, :cognoms, :email, :nom_usuari, :password, :rol)";
            
            $stmt = $this->sql->prepare($query);
            $result = $stmt->execute([
                ':nom' => $data['nom'],
                ':cognoms' => $data['cognoms'] ?? '',
                ':email' => $data['email'],
                ':nom_usuari' => $data['nom_usuari'],
                ':password' => $data['password'],
                ':rol' => $data['rol'] ?? 'user'
            ]);

            if (!$result) {
                throw new \Exception("Error al crear el usuario");
            }
            
            return $this->sql->lastInsertId();
            
        } catch (\PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
            throw new \Exception("Error al crear el usuario");
        }
    }
} 