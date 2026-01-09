<?php
/**
 
 * Page de connexion sécurisée
 * 
 * Auteur: Système de Gestion Patients
 * Date: 2025
 * Version: 1.0
 */


require_once 'config/database.php';
require_once 'includes/auth.php';

checkLogin();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Patients - Hôpital CIM Bouhanifia</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: #ecf0f1;
            padding: 20px 0;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #34495e;
            margin-bottom: 20px;
        }

        .sidebar-header h2 {
            font-size: 18px;
            color: #3498db;
        }

        .sidebar-menu a, .sidebar-menu button {
            display: block;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
            border: none;
            background: none;
            cursor: pointer;
            width: 100%;
            text-align: left;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover, .sidebar-menu button:hover {
            background: #34495e;
            padding-left: 30px;
            color: #3498db;
        }

        .sidebar-menu a.active {
            background: #3498db;
            color: white;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 20px;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 24px;
        }

        .user-info {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .user-role {
            background: #ecf0f1;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            color: #2c3e50;
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        /* CONTAINER */
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn-primary {
            background: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-edit {
            background: #f39c12;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            margin-right: 5px;
        }

        .btn-edit:hover {
            background: #d68910;
        }

        .btn-view {
            background: #27ae60;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            margin-right: 5px;
        }

        .btn-view:hover {
            background: #229954;
        }

        /* TABLE */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table thead {
            background: #ecf0f1;
        }

        .table th {
            padding: 12px;
            text-align: left;
            color: #2c3e50;
            font-weight: 600;
            border-bottom: 2px solid #bdc3c7;
        }

        .table td {
            padding: 12px;
            border-bottom: 1px solid #ecf0f1;
            color: #34495e;
        }

        .table tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            overflow-y: auto;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ecf0f1;
            padding-bottom: 15px;
        }

        .modal-header h2 {
            color: #2c3e50;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover {
            color: #000;
        }

        /* ALERT */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: none;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }

        /* DASHBOARD */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .card-title {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 10px;
        }

        .card-value {
            font-size: 32px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
            .header {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>🏥 CIM EPS</h2>
        </div>
        <div class="sidebar-menu">
            <a href="" data-page="dashboard" class="nav-link active">📊 Tableau de Bord</a>
            <a href="api\patients.php" data-page="patients" class="nav-link">👥 Patients</a>
            <a href="api\rendezvous.php" data-page="rendezvous" class="nav-link">📅 Rendez-vous</a>
            <a href="api\medecins.php" data-page="medecins" class="nav-link">👨‍⚕️ Médecins</a>
            <a href="api\examens.php" data-page="examens" class="nav-link">🔬 Examens</a>
            <a href="#" data-page="users" class="nav-link">🔐 Utilisateurs</a>
            <hr style="margin: 20px 0; border: none; border-top: 1px solid #34495e;">
            <button onclick="logout()" class="logout-btn" style="width: calc(100% - 40px); margin: 0 20px;">Déconnexion</button>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- HEADER -->
        <div class="header">
            <h1>Gestion des Données Patients</h1>
            <div class="user-info">
                <span class="user-role" id="userRole">Admin</span>
                <button class="logout-btn" onclick="logout()">Déconnexion</button>
            </div>
        </div>

        <!-- DASHBOARD PAGE -->
        <div id="dashboard" class="page" style="display: block;">
            <div class="container">
                <h2>Tableau de Bord</h2>
                <div class="dashboard-grid">
                    <div class="card">
                        <div class="card-title">Patients Inscrits</div>
                        <div class="card-value" id="totalPatients">0</div>
                    </div>
                    <div class="card">
                        <div class="card-title">Rendez-vous Aujourd'hui</div>
                        <div class="card-value" id="rdvToday">0</div>
                    </div>
                    <div class="card">
                        <div class="card-title">Médecins</div>
                        <div class="card-value" id="totalMedecins">0</div>
                    </div>
                    <div class="card">
                        <div class="card-title">Examens</div>
                        <div class="card-value" id="totalExamens">0</div>
                    </div>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 20px;">
                    <h3 style="color: #2c3e50; margin-bottom: 10px;">Derniers Patients Inscrits</h3>
                    <table class="table" id="recentPatientsTable">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Date d'Inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="recentPatients"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- PATIENTS PAGE -->
        <div id="patients" class="page" style="display: none;">
            <div class="container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>Gestion des Patients</h2>
                    <button class="btn-primary" onclick="openPatientModal()" style="width: auto; padding: 10px 20px;">+ Ajouter Patient</button>
                </div>

                <input type="text" id="searchPatient" placeholder="Rechercher un patient (nom, prénom, email)..." style="width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #bdc3c7; border-radius: 5px;">

                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Date Naissance</th>
                            <th>Téléphone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="patientsList"></tbody>
                </table>
            </div>
        </div>

        <!-- RENDEZ-VOUS PAGE -->
        <div id="rendezvous" class="page" style="display: none;">
            <div class="container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>Gestion des Rendez-vous</h2>
                    <button class="btn-primary" onclick="openRdvModal()" style="width: auto; padding: 10px 20px;">+ Nouveau Rendez-vous</button>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>ID RDV</th>
                            <th>Patient</th>
                            <th>Médecin</th>
                            <th>Date</th>
                            <th>Heure</th>
                            <th>Type Examen</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="rdvList"></tbody>
                </table>
            </div>
        </div>

        <!-- MEDECINS PAGE -->
        <div id="medecins" class="page" style="display: none;">
            <div class="container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>Gestion des Médecins</h2>
                    <button class="btn-primary" onclick="openMedecinModal()" style="width: auto; padding: 10px 20px;">+ Ajouter Médecin</button>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Spécialité</th>
                            <th>Service</th>
                            <th>Téléphone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="medecinsList"></tbody>
                </table>
            </div>
        </div>

        <!-- EXAMENS PAGE -->
        <div id="examens" class="page" style="display: none;">
            <div class="container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>Gestion des Examens</h2>
                    <button class="btn-primary" onclick="openExamenModal()" style="width: auto; padding: 10px 20px;">+ Nouvel Examen</button>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient</th>
                            <th>Type Examen</th>
                            <th>Date</th>
                            <th>Médecin</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="examensList"></tbody>
                </table>
            </div>
        </div>

        <!-- USERS PAGE -->
        <div id="users" class="page" style="display: none;">
            <div class="container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>Gestion des Utilisateurs</h2>
                    <button class="btn-primary" onclick="openUserModal()" style="width: auto; padding: 10px 20px;">+ Ajouter Utilisateur</button>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersList"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- PATIENT MODAL -->
    <div id="patientModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="patientModalTitle">Ajouter Patient</h2>
                <span class="close" onclick="closePatientModal()">&times;</span>
            </div>
            <form id="patientForm" onsubmit="savePatient(event)">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nom *</label>
                        <input type="text" id="patientNom" required>
                    </div>
                    <div class="form-group">
                        <label>Prénom *</label>
                        <input type="text" id="patientPrenom" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Date de Naissance *</label>
                        <input type="date" id="patientDateNaissance" required>
                    </div>
                    <div class="form-group">
                        <label>Sexe *</label>
                        <select id="patientSexe" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Adresse</label>
                    <input type="text" id="patientAdresse">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="tel" id="patientTelephone">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="patientEmail">
                    </div>
                </div>

                <div class="form-group">
                    <label>Notes Médicales</label>
                    <textarea id="patientNotes" rows="3"></textarea>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn-primary">Enregistrer</button>
                    <button type="button" class="btn-secondary" onclick="closePatientModal()">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <!-- RENDEZ-VOUS MODAL -->
    <div id="rdvModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Ajouter Rendez-vous</h2>
                <span class="close" onclick="closeRdvModal()">&times;</span>
            </div>
            <form id="rdvForm" onsubmit="saveRdv(event)">
                <div class="form-group">
                    <label>Patient *</label>
                    <select id="rdvPatient" required></select>
                </div>

                <div class="form-group">
                    <label>Médecin *</label>
                    <select id="rdvMedecin" required></select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Date *</label>
                        <input type="date" id="rdvDate" required>
                    </div>
                    <div class="form-group">
                        <label>Heure *</label>
                        <input type="time" id="rdvHeure" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Type d'Examen *</label>
                    <select id="rdvTypeExamen" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="Radiographie">Radiographie</option>
                        <option value="Échographie">Échographie</option>
                        <option value="Scanner">Scanner</option>
                        <option value="IRM">IRM</option>
                    </select>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn-primary">Enregistrer</button>
                    <button type="button" class="btn-secondary" onclick="closeRdvModal()">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MEDECIN MODAL -->
    <div id="medecinModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Ajouter Médecin</h2>
                <span class="close" onclick="closeMedecinModal()">&times;</span>
            </div>
            <form id="medecinForm" onsubmit="saveMedecin(event)">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nom *</label>
                        <input type="text" id="medecinNom" required>
                    </div>
                    <div class="form-group">
                        <label>Prénom *</label>
                        <input type="text" id="medecinPrenom" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Spécialité *</label>
                    <input type="text" id="medecinSpecialite" required>
                </div>

                <div class="form-group">
                    <label>Service *</label>
                    <input type="text" id="medecinService" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="tel" id="medecinTelephone">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="medecinEmail">
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn-primary">Enregistrer</button>
                    <button type="button" class="btn-secondary" onclick="closeMedecinModal()">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EXAMEN MODAL -->
    <div id="examenModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Ajouter Examen</h2>
                <span class="close" onclick="closeExamenModal()">&times;</span>
            </div>
            <form id="examenForm" onsubmit="saveExamen(event)">
                <div class="form-group">
                    <label>Patient *</label>
                    <select id="examenPatient" required></select>
                </div>

                <div class="form-group">
                    <label>Type d'Examen *</label>
                    <select id="examenType" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="Radiographie">Radiographie</option>
                        <option value="Échographie">Échographie</option>
                        <option value="Scanner">Scanner</option>
                        <option value="IRM">IRM</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Date de l'Examen *</label>
                        <input type="date" id="examenDate" required>
                    </div>
                    <div class="form-group">
                        <label>Médecin *</label>
                        <select id="examenMedecin" required></select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Compte Rendu</label>
                    <textarea id="examenCompteRendu" rows="3"></textarea>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn-primary">Enregistrer</button>
                    <button type="button" class="btn-secondary" onclick="closeExamenModal()">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <!-- USER MODAL -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Ajouter Utilisateur</h2>
                <span class="close" onclick="closeUserModal()">&times;</span>
            </div>
            <form id="userForm" onsubmit="saveUser(event)">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nom *</label>
                        <input type="text" id="userName" required>
                    </div>
                    <div class="form-group">
                        <label>Prénom *</label>
                        <input type="text" id="userPrenom" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" id="userEmail" required>
                </div>

                <div class="form-group">
                    <label>Mot de Passe *</label>
                    <input type="password" id="userPassword" required>
                </div>

                <div class="form-group">
                    <label>Rôle *</label>
                    <select id="userRole" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="admin">Administrateur</option>
                        <option value="medecin">Médecin</option>
                        <option value="manipulateur">Manipulateur</option>
                        <option value="patient">Patient</option>
                    </select>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn-primary">Enregistrer</button>
                    <button type="button" class="btn-secondary" onclick="closeUserModal()">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // DATABASE (LOCAL STORAGE)
        let patients = JSON.parse(localStorage.getItem('patients')) || [];
        let medecins = JSON.parse(localStorage.getItem('medecins')) || [];
        let rendezvous = JSON.parse(localStorage.getItem('rendezvous')) || [];
        let examens = JSON.parse(localStorage.getItem('examens')) || [];
        let users = JSON.parse(localStorage.getItem('users')) || [];

        // INIT DATA
        function initData() {
            if (patients.length === 0) {
                patients = [
                    { id: 1, nom: 'Benali', prenom: 'Ahmed', dateNaissance: '1985-05-15', sexe: 'M', telephone: '0612345678', email: 'ahmed@example.com', adresse: 'Mascara', notes: 'Aucune allergie connue' },
                    { id: 2, nom: 'Remli', prenom: 'Fatima', dateNaissance: '1990-08-22', sexe: 'F', telephone: '0698765432', email: 'fatima@example.com', adresse: 'Bouhanifia', notes: 'Hypertension' }
                ];
                localStorage.setItem('patients', JSON.stringify(patients));
            }

            if (medecins.length === 0) {
                medecins = [
                    { id: 1, nom: 'Mahmoudi', prenom: 'Laouni', specialite: 'Radiologie', service: 'CIM', telephone: '0556789012', email: 'mahmoudi@hopital.com' },
                    { id: 2, nom: 'Djouti', prenom: 'Soumia', specialite: 'Cardiologie', service: 'Cardio', telephone: '0567890123', email: 'djouti@hopital.com' }
                ];
                localStorage.setItem('medecins', JSON.stringify(medecins));
            }

            loadDashboard();
            loadPatients();
            loadMedecins();
            loadRdv();
            loadExamens();
            loadUsers();
        }

        // PAGE NAVIGATION
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                const page = this.dataset.page;
                document.querySelectorAll('.page').forEach(p => p.style.display = 'none');
                document.getElementById(page).style.display = 'block';
            });
        });

        // DASHBOARD
        function loadDashboard() {
            document.getElementById('totalPatients').textContent = patients.length;
            document.getElementById('totalMedecins').textContent = medecins.length;
            document.getElementById('totalExamens').textContent = examens.length;

            const today = new Date().toISOString().split('T')[0];
            const rdvToday = rendezvous.filter(r => r.date === today).length;
            document.getElementById('rdvToday').textContent = rdvToday;

            const recentPatients = patients.slice(-5).reverse();
            let html = '';
            recentPatients.forEach(p => {
                html += `
                    <tr>
                        <td>${p.nom}</td>
                        <td>${p.prenom}</td>
                        <td>${p.dateNaissance}</td>
                        <td><button class="btn-view" onclick="viewPatient(${p.id})">Voir</button></td>
                    </tr>
                `;
            });
            document.getElementById('recentPatients').innerHTML = html;
        }

        // PATIENTS MANAGEMENT
        function loadPatients() {
            let html = '';
            patients.forEach(p => {
                html += `
                    <tr>
                        <td>${p.id}</td>
                        <td>${p.nom}</td>
                        <td>${p.prenom}</td>
                        <td>${p.dateNaissance}</td>
                        <td>${p.telephone || '-'}</td>
                        <td>
                            <button class="btn-view" onclick="viewPatient(${p.id})">Voir</button>
                            <button class="btn-edit" onclick="editPatient(${p.id})">Modifier</button>
                            <button class="btn-danger" onclick="deletePatient(${p.id})">Supprimer</button>
                        </td>
                    </tr>
                `;
            });
            document.getElementById('patientsList').innerHTML = html;
        }

        function openPatientModal() {
            document.getElementById('patientForm').reset();
            document.getElementById('patientModalTitle').textContent = 'Ajouter Patient';
            document.getElementById('patientModal').style.display = 'block';
        }

        function closePatientModal() {
            document.getElementById('patientModal').style.display = 'none';
        }

        function savePatient(e) {
            e.preventDefault();
            const nom = document.getElementById('patientNom').value;
            const prenom = document.getElementById('patientPrenom').value;
            const dateNaissance = document.getElementById('patientDateNaissance').value;
            const sexe = document.getElementById('patientSexe').value;
            const adresse = document.getElementById('patientAdresse').value;
            const telephone = document.getElementById('patientTelephone').value;
            const email = document.getElementById('patientEmail').value;
            const notes = document.getElementById('patientNotes').value;

            const patient = {
                id: Math.max(...patients.map(p => p.id), 0) + 1,
                nom, prenom, dateNaissance, sexe, adresse, telephone, email, notes
            };

            patients.push(patient);
            localStorage.setItem('patients', JSON.stringify(patients));
            loadPatients();
            loadDashboard();
            closePatientModal();
            alert('Patient ajouté avec succès!');
        }

        function editPatient(id) {
            const patient = patients.find(p => p.id === id);
            if (patient) {
                document.getElementById('patientNom').value = patient.nom;
                document.getElementById('patientPrenom').value = patient.prenom;
                document.getElementById('patientDateNaissance').value = patient.dateNaissance;
                document.getElementById('patientSexe').value = patient.sexe;
                document.getElementById('patientAdresse').value = patient.adresse || '';
                document.getElementById('patientTelephone').value = patient.telephone || '';
                document.getElementById('patientEmail').value = patient.email || '';
                document.getElementById('patientNotes').value = patient.notes || '';
                document.getElementById('patientModalTitle').textContent = 'Modifier Patient';
                document.getElementById('patientModal').style.display = 'block';
            }
        }

        function viewPatient(id) {
            const patient = patients.find(p => p.id === id);
            if (patient) {
                alert(`Fiche Patient:\n\nNom: ${patient.nom}\nPrénom: ${patient.prenom}\nDate Naissance: ${patient.dateNaissance}\nSexe: ${patient.sexe}\nAdresse: ${patient.adresse}\nTéléphone: ${patient.telephone}\nEmail: ${patient.email}\nNotes: ${patient.notes}`);
            }
        }

        function deletePatient(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce patient?')) {
                patients = patients.filter(p => p.id !== id);
                localStorage.setItem('patients', JSON.stringify(patients));
                loadPatients();
                loadDashboard();
            }
        }

        // RENDEZ-VOUS MANAGEMENT
        function loadRdv() {
            let html = '';
            rendezvous.forEach(r => {
                const patient = patients.find(p => p.id === r.patientId);
                const medecin = medecins.find(m => m.id === r.medecinId);
                const statusClass = r.statut === 'prévu' ? 'status-pending' : r.statut === 'annulé' ? 'status-cancelled' : 'status-active';
                
                html += `
                    <tr>
                        <td>${r.id}</td>
                        <td>${patient?.nom} ${patient?.prenom}</td>
                        <td>${medecin?.nom} ${medecin?.prenom}</td>
                        <td>${r.date}</td>
                        <td>${r.heure}</td>
                        <td>${r.typeExamen}</td>
                        <td><span class="status-badge ${statusClass}">${r.statut}</span></td>
                        <td>
                            <button class="btn-edit" onclick="editRdv(${r.id})">Modifier</button>
                            <button class="btn-danger" onclick="deleteRdv(${r.id})">Annuler</button>
                        </td>
                    </tr>
                `;
            });
            document.getElementById('rdvList').innerHTML = html;
        }

        function openRdvModal() {
            populateSelectPatients('rdvPatient');
            populateSelectMedecins('rdvMedecin');
            document.getElementById('rdvForm').reset();
            document.getElementById('rdvModal').style.display = 'block';
        }

        function closeRdvModal() {
            document.getElementById('rdvModal').style.display = 'none';
        }

        function saveRdv(e) {
            e.preventDefault();
            const patientId = parseInt(document.getElementById('rdvPatient').value);
            const medecinId = parseInt(document.getElementById('rdvMedecin').value);
            const date = document.getElementById('rdvDate').value;
            const heure = document.getElementById('rdvHeure').value;
            const typeExamen = document.getElementById('rdvTypeExamen').value;

            const rdv = {
                id: Math.max(...rendezvous.map(r => r.id), 0) + 1,
                patientId, medecinId, date, heure, typeExamen, statut: 'prévu'
            };

            rendezvous.push(rdv);
            localStorage.setItem('rendezvous', JSON.stringify(rendezvous));
            loadRdv();
            loadDashboard();
            closeRdvModal();
            alert('Rendez-vous ajouté avec succès!');
        }

        function editRdv(id) {
            const rdv = rendezvous.find(r => r.id === id);
            if (rdv) {
                populateSelectPatients('rdvPatient');
                populateSelectMedecins('rdvMedecin');
                document.getElementById('rdvPatient').value = rdv.patientId;
                document.getElementById('rdvMedecin').value = rdv.medecinId;
                document.getElementById('rdvDate').value = rdv.date;
                document.getElementById('rdvHeure').value = rdv.heure;
                document.getElementById('rdvTypeExamen').value = rdv.typeExamen;
                document.getElementById('rdvModal').style.display = 'block';
            }
        }

        function deleteRdv(id) {
            if (confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous?')) {
                rendezvous = rendezvous.filter(r => r.id !== id);
                localStorage.setItem('rendezvous', JSON.stringify(rendezvous));
                loadRdv();
                loadDashboard();
            }
        }

        // MEDECINS MANAGEMENT
        function loadMedecins() {
            let html = '';
            medecins.forEach(m => {
                html += `
                    <tr>
                        <td>${m.id}</td>
                        <td>${m.nom}</td>
                        <td>${m.prenom}</td>
                        <td>${m.specialite}</td>
                        <td>${m.service}</td>
                        <td>${m.telephone || '-'}</td>
                        <td>
                            <button class="btn-edit" onclick="editMedecin(${m.id})">Modifier</button>
                            <button class="btn-danger" onclick="deleteMedecin(${m.id})">Supprimer</button>
                        </td>
                    </tr>
                `;
            });
            document.getElementById('medecinsList').innerHTML = html;
        }

        function openMedecinModal() {
            document.getElementById('medecinForm').reset();
            document.getElementById('medecinModal').style.display = 'block';
        }

        function closeMedecinModal() {
            document.getElementById('medecinModal').style.display = 'none';
        }

        function saveMedecin(e) {
            e.preventDefault();
            const nom = document.getElementById('medecinNom').value;
            const prenom = document.getElementById('medecinPrenom').value;
            const specialite = document.getElementById('medecinSpecialite').value;
            const service = document.getElementById('medecinService').value;
            const telephone = document.getElementById('medecinTelephone').value;
            const email = document.getElementById('medecinEmail').value;

            const medecin = {
                id: Math.max(...medecins.map(m => m.id), 0) + 1,
                nom, prenom, specialite, service, telephone, email
            };

            medecins.push(medecin);
            localStorage.setItem('medecins', JSON.stringify(medecins));
            loadMedecins();
            loadDashboard();
            closeMedecinModal();
            alert('Médecin ajouté avec succès!');
        }

        function editMedecin(id) {
            const medecin = medecins.find(m => m.id === id);
            if (medecin) {
                document.getElementById('medecinNom').value = medecin.nom;
                document.getElementById('medecinPrenom').value = medecin.prenom;
                document.getElementById('medecinSpecialite').value = medecin.specialite;
                document.getElementById('medecinService').value = medecin.service;
                document.getElementById('medecinTelephone').value = medecin.telephone || '';
                document.getElementById('medecinEmail').value = medecin.email || '';
                document.getElementById('medecinModal').style.display = 'block';
            }
        }

        function deleteMedecin(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce médecin?')) {
                medecins = medecins.filter(m => m.id !== id);
                localStorage.setItem('medecins', JSON.stringify(medecins));
                loadMedecins();
                loadDashboard();
            }
        }

        // EXAMENS MANAGEMENT
        function loadExamens() {
            let html = '';
            examens.forEach(ex => {
                const patient = patients.find(p => p.id === ex.patientId);
                const medecin = medecins.find(m => m.id === ex.medecinId);
                
                html += `
                    <tr>
                        <td>${ex.id}</td>
                        <td>${patient?.nom} ${patient?.prenom}</td>
                        <td>${ex.typeExamen}</td>
                        <td>${ex.date}</td>
                        <td>${medecin?.nom} ${medecin?.prenom}</td>
                        <td>
                            <button class="btn-edit" onclick="editExamen(${ex.id})">Modifier</button>
                            <button class="btn-danger" onclick="deleteExamen(${ex.id})">Supprimer</button>
                        </td>
                    </tr>
                `;
            });
            document.getElementById('examensList').innerHTML = html;
        }

        function openExamenModal() {
            populateSelectPatients('examenPatient');
            populateSelectMedecins('examenMedecin');
            document.getElementById('examenForm').reset();
            document.getElementById('examenModal').style.display = 'block';
        }

        function closeExamenModal() {
            document.getElementById('examenModal').style.display = 'none';
        }

        function saveExamen(e) {
            e.preventDefault();
            const patientId = parseInt(document.getElementById('examenPatient').value);
            const typeExamen = document.getElementById('examenType').value;
            const date = document.getElementById('examenDate').value;
            const medecinId = parseInt(document.getElementById('examenMedecin').value);
            const compteRendu = document.getElementById('examenCompteRendu').value;

            const examen = {
                id: Math.max(...examens.map(ex => ex.id), 0) + 1,
                patientId, typeExamen, date, medecinId, compteRendu
            };

            examens.push(examen);
            localStorage.setItem('examens', JSON.stringify(examens));
            loadExamens();
            loadDashboard();
            closeExamenModal();
            alert('Examen ajouté avec succès!');
        }

        function editExamen(id) {
            const examen = examens.find(ex => ex.id === id);
            if (examen) {
                populateSelectPatients('examenPatient');
                populateSelectMedecins('examenMedecin');
                document.getElementById('examenPatient').value = examen.patientId;
                document.getElementById('examenType').value = examen.typeExamen;
                document.getElementById('examenDate').value = examen.date;
                document.getElementById('examenMedecin').value = examen.medecinId;
                document.getElementById('examenCompteRendu').value = examen.compteRendu || '';
                document.getElementById('examenModal').style.display = 'block';
            }
        }

        function deleteExamen(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet examen?')) {
                examens = examens.filter(ex => ex.id !== id);
                localStorage.setItem('examens', JSON.stringify(examens));
                loadExamens();
                loadDashboard();
            }
        }

        // USERS MANAGEMENT
        function loadUsers() {
            let html = '';
            users.forEach(u => {
                html += `
                    <tr>
                        <td>${u.id}</td>
                        <td>${u.nom}</td>
                        <td>${u.email}</td>
                        <td><span class="status-badge status-pending">${u.role}</span></td>
                        <td><span class="status-badge status-active">Actif</span></td>
                        <td>
                            <button class="btn-edit" onclick="editUser(${u.id})">Modifier</button>
                            <button class="btn-danger" onclick="deleteUser(${u.id})">Supprimer</button>
                        </td>
                    </tr>
                `;
            });
            document.getElementById('usersList').innerHTML = html;
        }

        function openUserModal() {
            document.getElementById('userForm').reset();
            document.getElementById('userModal').style.display = 'block';
        }

        function closeUserModal() {
            document.getElementById('userModal').style.display = 'none';
        }

        function saveUser(e) {
            e.preventDefault();
            const nom = document.getElementById('userName').value;
            const prenom = document.getElementById('userPrenom').value;
            const email = document.getElementById('userEmail').value;
            const password = document.getElementById('userPassword').value;
            const role = document.getElementById('userRole').value;

            const user = {
                id: Math.max(...users.map(u => u.id), 0) + 1,
                nom, prenom, email, password, role
            };

            users.push(user);
            localStorage.setItem('users', JSON.stringify(users));
            loadUsers();
            closeUserModal();
            alert('Utilisateur ajouté avec succès!');
        }

        function editUser(id) {
            const user = users.find(u => u.id === id);
            if (user) {
                document.getElementById('userName').value = user.nom;
                document.getElementById('userPrenom').value = user.prenom;
                document.getElementById('userEmail').value = user.email;
                document.getElementById('userPassword').value = user.password;
                document.getElementById('userRole').value = user.role;
                document.getElementById('userModal').style.display = 'block';
            }
        }

        function deleteUser(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')) {
                users = users.filter(u => u.id !== id);
                localStorage.setItem('users', JSON.stringify(users));
                loadUsers();
            }
        }

        // HELPER FUNCTIONS
        function populateSelectPatients(selectId) {
            let html = '<option value="">-- Sélectionner un patient --</option>';
            patients.forEach(p => {
                html += `<option value="${p.id}">${p.nom} ${p.prenom}</option>`;
            });
            document.getElementById(selectId).innerHTML = html;
        }

        function populateSelectMedecins(selectId) {
            let html = '<option value="">-- Sélectionner un médecin --</option>';
            medecins.forEach(m => {
                html += `<option value="${m.id}">${m.nom} ${m.prenom} (${m.specialite})</option>`;
            });
            document.getElementById(selectId).innerHTML = html;
        }

        function logout() {
               if (confirm("Êtes-vous sûr de vouloir vous déconnecter ?")) {
                localStorage.clear(); // ou bien removeItem(...) pour cibler seulement certaines clés
                 window.location.href = "Gestion Patients Hôpital.php";
            }
        }

        // CLOSE MODAL WHEN CLICKING OUTSIDE
        window.onclick = function(event) {
            const patientModal = document.getElementById('patientModal');
            const rdvModal = document.getElementById('rdvModal');
            const medecinModal = document.getElementById('medecinModal');
            const examenModal = document.getElementById('examenModal');
            const userModal = document.getElementById('userModal');

            if (event.target === patientModal) patientModal.style.display = 'none';
            if (event.target === rdvModal) rdvModal.style.display = 'none';
            if (event.target === medecinModal) medecinModal.style.display = 'none';
            if (event.target === examenModal) examenModal.style.display = 'none';
            if (event.target === userModal) userModal.style.display = 'none';
        }

        // SEARCH FUNCTIONALITY
        document.getElementById('searchPatient').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const filtered = patients.filter(p => 
                p.nom.toLowerCase().includes(searchTerm) || 
                p.prenom.toLowerCase().includes(searchTerm) ||
                p.email.toLowerCase().includes(searchTerm)
            );

            let html = '';
            filtered.forEach(p => {
                html += `
                    <tr>
                        <td>${p.id}</td>
                        <td>${p.nom}</td>
                        <td>${p.prenom}</td>
                        <td>${p.dateNaissance}</td>
                        <td>${p.telephone || '-'}</td>
                        <td>
                            <button class="btn-view" onclick="viewPatient(${p.id})">Voir</button>
                            <button class="btn-edit" onclick="editPatient(${p.id})">Modifier</button>
                            <button class="btn-danger" onclick="deletePatient(${p.id})">Supprimer</button>
                        </td>
                    </tr>
                `;
            });
            document.getElementById('patientsList').innerHTML = html;
        });

        // INITIALIZE
        initData();
    </script>
</body>
</html>
