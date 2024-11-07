<?php
namespace Models;

class UsuarisPDO extends DB {
    public function login($email, $password) {
        try {
            $query = "SELECT * FROM usuaris WHERE email = :email";
            $stmt = $this->sql->prepare($query);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$user) {
                throw new \Exception("Usuario no encontrado");
            }

            if (!password_verify($password, $user['password'])) {
                throw new \Exception("Contraseña incorrecta");
            }

            unset($user['password']);
            return $user;
        } catch (\PDOException $e) {
            error_log("Error en login: " . $e->getMessage());
            throw new \Exception("Error al iniciar sesión");
        }
    }

    public function register($data) {
        try {
            $query = "INSERT INTO usuaris (nom, cognoms, nom_usuari, email, password) VALUES (:nom, :cognoms, :nom_usuari, :email, :password)";
            $stmt = $this->sql->prepare($query);

            // Hash the password before storing it
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

            $stmt->execute([
                ':nom' => $data['nom'],
                ':cognoms' => $data['cognoms'],
                ':nom_usuari' => $data['nom_usuari'],
                ':email' => $data['email'],
                ':password' => $hashedPassword
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
            $query = "SELECT * FROM usuaris WHERE email = :email";
            $stmt = $this->sql->prepare($query);
            $stmt->execute([':email' => $email]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error en getByEmail: " . $e->getMessage());
            throw new \Exception("Error al buscar el usuario");
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
} 