<?php
namespace models;
class Reservations extends \app\Model {
    public string $table = "reservations";

    public function countThisWeek($userId) {
        $sql = "SELECT COUNT(*) as nb FROM `{$this->table}` WHERE user_id = ? AND YEARWEEK(date, 1) = YEARWEEK(NOW(), 1)";
        $stmt = $this->_connexion->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? (int)$row['nb'] : 0;
    }

    public function add($userId, $date) {
        $sql = "INSERT INTO reservations (user_id, date) VALUES (?, ?)";
        $stmt = $this->_connexion->prepare($sql);
        $stmt->bind_param("is", $userId, $date);
        if (!$stmt->execute()) {
            \app\Debug::debugDie([$stmt->errno, $stmt->error]);
        }
        return $this->_connexion->insert_id;
    }

    public function addProduit($reservationId, $produitId, $quantite) {
        $sql = "INSERT INTO reservation_produits (reservation_id, produit_id, quantite) VALUES (?, ?, ?)";
        $stmt = $this->_connexion->prepare($sql);
        $stmt->bind_param("iii", $reservationId, $produitId, $quantite);
        if (!$stmt->execute()) {
            \app\Debug::debugDie([$stmt->errno, $stmt->error]);
        }
    }

    public function getEnAttente() {
        $sql = "SELECT r.*, 
                       u.nom, u.prenom,
                       GROUP_CONCAT(CONCAT(p.name, ' (x', rp.quantite, ')') SEPARATOR '<br>') as produits
                FROM reservations r
                JOIN users u ON u.id = r.user_id
                JOIN reservation_produits rp ON rp.reservation_id = r.id
                JOIN produits p ON p.id = rp.produit_id
                WHERE r.statut = 'en_attente'
                GROUP BY r.id
                ORDER BY r.date DESC";
        $result = $this->_connexion->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getValidees() {
        $sql = "SELECT r.*, 
                       u.nom, u.prenom,
                       GROUP_CONCAT(CONCAT(p.name, ' (x', rp.quantite, ')') SEPARATOR '<br>') as produits
                FROM reservations r
                JOIN users u ON u.id = r.user_id
                JOIN reservation_produits rp ON rp.reservation_id = r.id
                JOIN produits p ON p.id = rp.produit_id
                WHERE r.statut = 'validee'
                GROUP BY r.id
                ORDER BY r.date DESC";
        $result = $this->_connexion->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function valider($id) {
        $sql = "UPDATE reservations SET statut = 'validee' WHERE id = ?";
        $stmt = $this->_connexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
?>