<?php
namespace models;

class Benevoles extends \app\Model {
    public string $table = "benevoles";

    public function getNonValides() {
        $sql = "SELECT * FROM benevoles WHERE valide = 0";
        $stmt = $this->_connexion->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getValides() {
        $sql = "SELECT * FROM benevoles WHERE valide = 1";
        $stmt = $this->_connexion->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAll(): array {
        $sql = "SELECT * FROM benevoles";
        $stmt = $this->_connexion->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM benevoles WHERE id = ?";
        $stmt = $this->_connexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function add($data) {
        $sql = "INSERT INTO benevoles (nom, prenom, email, telephone) VALUES (?, ?, ?, ?)";
        $stmt = $this->_connexion->prepare($sql);
        $stmt->bind_param("ssss", $data['nom'], $data['prenom'], $data['email'], $data['telephone']);
        return $stmt->execute();
    }

    public function valider($id) {
        $sql = "UPDATE benevoles SET valide = 1, date_validation = NOW() WHERE id = ?";
        $stmt = $this->_connexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM benevoles WHERE id = ?";
        $stmt = $this->_connexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO benevoles (nom, prenom, email, telephone) VALUES (?, ?, ?, ?)";
        $stmt = $this->_connexion->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("ssss", $data['nom'], $data['prenom'], $data['email'], $data['telephone']);
        return $stmt->execute();
    }
}