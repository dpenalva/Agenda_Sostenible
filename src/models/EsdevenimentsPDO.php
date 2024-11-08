<?php
namespace Models;

class EsdevenimentsPDO extends DB {
    
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
            $query = "SELECT 
                        e.*,
                        u.nom_usuari as creador_nom,
                        u.imatge_perfil as creador_avatar
                    FROM esdeveniments e
                    INNER JOIN usuaris u ON e.usuari_id = u.id
                    WHERE e.id = :id";

            $stmt = $this->sql->prepare($query);
            $stmt->execute([':id' => $id]);
            
            $event = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$event) {
                throw new \Exception("Evento no encontrado");
            }
            
            return $event;
        } catch (\PDOException $e) {
            error_log("Error en get: " . $e->getMessage());
            throw new \Exception("Error al obtener el evento");
        }
    }

    /**
     * Crea un nuevo evento
     */
    public function create($data) {
        try {
            $query = "INSERT INTO esdeveniments (
                titol,
                descripcio,
                data_inici,
                hora_inici,
                ubicacio,
                aforament,
                tipus_esdeveniment_id,
                imatge_url,
                usuari_id,
                created_at
            ) VALUES (
                :titol,
                :descripcio,
                :data_inici,
                :hora_inici,
                :ubicacio,
                :aforament,
                :tipus_esdeveniment_id,
                :imatge_url,
                :usuari_id,
                NOW()
            )";

            $stmt = $this->sql->prepare($query);
            $result = $stmt->execute([
                ':titol' => $data['titol'],
                ':descripcio' => $data['descripcio'],
                ':data_inici' => $data['data_inici'],
                ':hora_inici' => $data['hora_inici'],
                ':ubicacio' => $data['ubicacio'],
                ':aforament' => $data['aforament'],
                ':tipus_esdeveniment_id' => $data['tipus_esdeveniment_id'],
                ':imatge_url' => $data['imatge_url'] ?? null,
                ':usuari_id' => $data['usuari_id']
            ]);

            if ($result) {
                return $this->sql->lastInsertId();
            }

            throw new \Exception("Error al crear el evento");
        } catch (\PDOException $e) {
            error_log("Error en create: " . $e->getMessage());
            throw new \Exception("Error al crear el evento");
        }
    }

    public function getTotalEvents() {
        try {
            $query = "SELECT COUNT(*) as total FROM esdeveniments";
            $stmt = $this->sql->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $result['total'];
        } catch (\PDOException $e) {
            error_log("Error getting total events: " . $e->getMessage());
            throw new \Exception("Error al obtener el total de eventos");
        }
    }

    public function getRecentEvents($limit = 5) {
        try {
            $query = "SELECT e.*, u.nom_usuari 
                      FROM esdeveniments e 
                      LEFT JOIN usuaris u ON e.usuari_id = u.id 
                      ORDER BY e.created_at DESC 
                      LIMIT :limit";
            
            $stmt = $this->sql->prepare($query);
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error getting recent events: " . $e->getMessage());
            throw new \Exception("Error al obtener eventos recientes");
        }
    }
} 