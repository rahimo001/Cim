<?php
/**
 * logout.php
 * Déconnexion de l'utilisateur
 * 
 * Auteur: Système de Gestion Patients
 * Date: 2025
 * Version: 1.0
 */

session_start();
session_destroy();

// Rediriger vers la page de connexion
header('Location: login.php');
exit;

?>
