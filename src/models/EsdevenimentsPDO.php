<?php
namespace Models;

class EsdevenimentsPDO {
    private $sql;

    public function __construct($config) {
        $this->sql = new \PDO(
            "mysql:host=" . $config['host'] . ";dbname=" . $config['name'],
            $config['user'],
            $config['pass'],
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]
        );
    }

    /**
     * Obtiene todos los eventos ordenados por fecha
     */
    public function getAll() {
        try {
            $stmt = $this->sql->prepare("
                SELECT 
                    id,
                    titol,
                    descripcio,
                    longitud,
                    latitud,
                    data_esdeveniment,
                    hora_esdeveniment,
                    visibilitat_esdeveniment,
                    tipus_esdeveniment_id,
                    id_usuari
                FROM esdeveniments
                ORDER BY data_esdeveniment DESC, hora_esdeveniment DESC
            ");
            
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("Error al obtener eventos: " . $e->getMessage());
            throw new \Exception("Error al obtener los eventos");
        }
    }

    /**
     * Obtiene todos los eventos de un usuario específico
     */
    public function getByUser($userId) {
        try {
            $query = "SELECT 
                        e.id,
                        e.titol,
                        e.descripcio,
                        e.data_inici,
                        e.hora_inici,
                        e.ubicacio,
                        e.aforament,
                        e.tipus_esdeveniment_id,
                        e.imatge_url,
                        e.created_at,
                        e.updated_at,
                        u.nom_usuari as creador_nom,
                        u.imatge_perfil as creador_avatar
                    FROM esdeveniments e
                    INNER JOIN usuaris u ON e.usuari_id = u.id
                    WHERE e.usuari_id = :userId
                    ORDER BY e.data_inici DESC, e.hora_inici DESC";

            $stmt = $this->sql->prepare($query);
            $stmt->execute([':userId' => $userId]);
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error en getByUser: " . $e->getMessage());
            throw new \Exception("Error al obtener los eventos del usuario");
        }
    }

    /**
     * Obtiene un evento específico
     */
    public function get($id) {
        try {
            $stmt = $this->sql->prepare("
                SELECT 
                    id,
                    titol,
                    descripcio,
                    data_esdeveniment,
                    hora_esdeveniment,
                    longitud,
                    latitud,
                    visibilitat_esdeveniment
                FROM esdeveniments 
                WHERE id = :id
            ");
            
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$result) {
                throw new \Exception("No se encontró el evento con ID: $id");
            }
            
            return $result;
            
        } catch (\PDOException $e) {
            throw new \Exception("Error al obtener el evento: " . $e->getMessage());
        }
    }

    /**
     * Crea un nuevo evento
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO esdeveniments (
                titol, 
                descripcio, 
                data_esdeveniment, 
                hora_esdeveniment, 
                longitud, 
                latitud, 
                visibilitat_esdeveniment
            ) VALUES (
                :titol,
                :descripcio,
                :data_esdeveniment,
                :hora_esdeveniment,
                :longitud,
                :latitud,
                :visibilitat_esdeveniment
            )";

            $stmt = $this->sql->prepare($sql);
            
            $stmt->execute([
                ':titol' => $data['titol'],
                ':descripcio' => $data['descripcio'] ?? '',
                ':data_esdeveniment' => $data['data_esdeveniment'],
                ':hora_esdeveniment' => $data['hora_esdeveniment'],
                ':longitud' => $data['longitud'] ?? null,
                ':latitud' => $data['latitud'] ?? null,
                ':visibilitat_esdeveniment' => $data['visibilitat_esdeveniment'] ?? '0'
            ]);
            
            return $this->sql->lastInsertId();
        } catch (\PDOException $e) {
            throw new \Exception("Error al crear el evento: " . $e->getMessage());
        }
    }

    public function getTotalEvents() {
        try {
            $query = "SELECT COUNT(*) as total FROM esdeveniments";
            $stmt = $this->sql->query($query);
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (\PDOException $e) {
            error_log("Error en getTotalEvents: " . $e->getMessage());
            return 0;
        }
    }

    public function getRecentEvents($limit = 5) {
        try {
            $query = "SELECT id, titol, descripcio, data_esdeveniment, hora_esdeveniment 
                      FROM esdeveniments 
                      ORDER BY data_esdeveniment DESC, hora_esdeveniment DESC 
                      LIMIT :limit";
            
            $stmt = $this->sql->prepare($query);
            $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("Error en getRecentEvents: " . $e->getMessage());
            return [];
        }
    }

    public function toggleLike($userId, $eventId) {
        try {
            // Verificar si ya existe el favorito
            $stmt = $this->sql->prepare("
                SELECT id FROM favorit 
                WHERE usuari_id = :userId AND esdeveniment_id = :eventId
            ");
            $stmt->execute([
                ':userId' => $userId,
                ':eventId' => $eventId
            ]);
            
            $existingFavorite = $stmt->fetch();
            
            if ($existingFavorite) {
                // Si existe, eliminar el favorito
                $stmt = $this->sql->prepare("
                    DELETE FROM favorit 
                    WHERE usuari_id = :userId AND esdeveniment_id = :eventId
                ");
                $stmt->execute([
                    ':userId' => $userId,
                    ':eventId' => $eventId
                ]);
                $isLiked = false;
            } else {
                // Si no existe, crear el favorito
                $stmt = $this->sql->prepare("
                    INSERT INTO favorit (usuari_id, esdeveniment_id) 
                    VALUES (:userId, :eventId)
                ");
                $stmt->execute([
                    ':userId' => $userId,
                    ':eventId' => $eventId
                ]);
                $isLiked = true;
            }
            
            // Obtener el número total de favoritos
            $stmt = $this->sql->prepare("
                SELECT COUNT(*) as count 
                FROM favorit 
                WHERE esdeveniment_id = :eventId
            ");
            $stmt->execute([':eventId' => $eventId]);
            $result = $stmt->fetch();
            
            return [
                'isLiked' => $isLiked,
                'likesCount' => $result['count']
            ];
        } catch (\PDOException $e) {
            error_log("Error en toggleLike: " . $e->getMessage());
            throw new \Exception("Error al procesar el favorito");
        }
    }

    public function getLikedEvents($userId) {
        try {
            $stmt = $this->sql->prepare("
                SELECT e.*, 
                       (SELECT COUNT(*) FROM favorit WHERE esdeveniment_id = e.id) as likes_count,
                       TRUE as is_liked
                FROM esdeveniments e
                INNER JOIN favorit f ON e.id = f.esdeveniment_id
                WHERE f.usuari_id = :userId
                ORDER BY e.data_esdeveniment DESC
            ");
            
            $stmt->execute([':userId' => $userId]);
            return $stmt->fetchAll();
            
        } catch (\Exception $e) {
            error_log("Error en getLikedEvents: " . $e->getMessage());
            throw new \Exception("Error al obtener eventos favoritos");
        }
    }

    public function getLikedEventsWithDetails($userId) {
        try {
            // Consulta simplificada que solo obtiene los eventos favoritos básicos
            $query = "SELECT e.* 
                     FROM esdeveniments e
                     INNER JOIN favorit f ON e.id = f.esdeveniment_id
                     WHERE f.usuari_id = :userId
                     ORDER BY e.data_esdeveniment DESC, e.hora_esdeveniment DESC";
            
            $stmt = $this->sql->prepare($query);
            $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
            $stmt->execute();
            
            $events = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Añadir información adicional a cada evento
            foreach ($events as &$event) {
                $event['is_liked'] = true; // Siempre true ya que son eventos favoritos
                $event['user_rating'] = 0;  // Valor por defecto
                $event['average_rating'] = 0; // Valor por defecto
            }
            
            return $events;
        } catch (\PDOException $e) {
            error_log("Error en getLikedEventsWithDetails: " . $e->getMessage());
            throw new \Exception("Error al obtener los eventos favoritos: " . $e->getMessage());
        }
    }

    // Método auxiliar para verificar si existe la tabla
    private function tableExists($tableName) {
        try {
            $result = $this->sql->query("SHOW TABLES LIKE '{$tableName}'");
            return $result->rowCount() > 0;
        } catch (\PDOException $e) {
            return false;
        }
    }

    // Método para obtener el rating de un usuario
    public function getUserRating($userId, $eventId) {
        // Por ahora retornamos 0 como valor por defecto
        return 0;
    }

    // Método para obtener el rating promedio
    public function getAverageRating($eventId) {
        // Por ahora retornamos 0 como valor por defecto
        return 0;
    }

    public function update($id, $data) {
        try {
            $sql = "UPDATE esdeveniments SET 
                    titol = :titol,
                    descripcio = :descripcio,
                    data_esdeveniment = :data_esdeveniment,
                    hora_esdeveniment = :hora_esdeveniment,
                    longitud = :longitud,
                    latitud = :latitud,
                    visibilitat_esdeveniment = :visibilitat_esdeveniment
                    WHERE id = :id";

            $stmt = $this->sql->prepare($sql);
            
            $stmt->execute([
                ':id' => $id,
                ':titol' => $data['titol'],
                ':descripcio' => $data['descripcio'],
                ':data_esdeveniment' => $data['data_esdeveniment'],
                ':hora_esdeveniment' => $data['hora_esdeveniment'],
                ':longitud' => $data['longitud'],
                ':latitud' => $data['latitud'],
                ':visibilitat_esdeveniment' => $data['visibilitat_esdeveniment']
            ]);
            
            return true;
        } catch (\PDOException $e) {
            throw new \Exception("Error al actualizar el evento: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->sql->prepare("DELETE FROM esdeveniments WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return true;
        } catch (\PDOException $e) {
            throw new \Exception("Error al eliminar el evento: " . $e->getMessage());
        }
    }
} 