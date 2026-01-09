<?php
/**
 * index.php
 * Tableau de bord principal avec statistiques
 * 
 * Auteur: Système de Gestion Patients
 * Date: 2025
 * Version: 1.0
 */

require_once 'config/database.php';
require_once 'includes/auth.php';

checkLogin();

// Récupérer les statistiques
$stats = [];

// Nombre total de patients
$stmt = $pdo->query('SELECT COUNT(*) as count FROM patients');
$stats['patients'] = $stmt->fetch()['count'];

// Nombre total de médecins
$stmt = $pdo->query('SELECT COUNT(*) as count FROM medecins');
$stats['medecins'] = $stmt->fetch()['count'];

// Nombre de rendez-vous d'aujourd'hui
$stmt = $pdo->query('SELECT COUNT(*) as count FROM rendezvous WHERE date_rdv = CURDATE() AND statut != "annulé"');
$stats['rdv_today'] = $stmt->fetch()['count'];

// Nombre d'examens en attente
$stmt = $pdo->query('SELECT COUNT(*) as count FROM examens WHERE statut = "en_attente"');
$stats['examens_pending'] = $stmt->fetch()['count'];

// Derniers patients
$stmt = $pdo->query('SELECT * FROM patients ORDER BY date_inscription DESC LIMIT 5');
$last_patients = $stmt->fetchAll();

// Rendez-vous d'aujourd'hui
$stmt = $pdo->query('
    SELECT r.*, p.nom as patient_nom, p.prenom as patient_prenom, 
           m.nom as medecin_nom, m.prenom as medecin_prenom
    FROM rendezvous r
    JOIN patients p ON r.id_patient = p.id_patient
    JOIN medecins m ON r.id_medecin = m.id_medecin
    WHERE r.date_rdv = CURDATE()
    ORDER BY r.heure_rdv ASC
');
$rdv_today = $stmt->fetchAll();

$user = getUserInfo();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Gestion Patients</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            color: #333;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar h1 {
            font-size: 24px;
        }
        
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .navbar-user a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            transition: background 0.3s;
        }
        
        .navbar-user a:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .welcome {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .welcome h2 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-left: 4px solid #667eea;
        }
        
        .stat-card h3 {
            color: #667eea;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }
        
        .section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .section h3 {
            color: #667eea;
            margin-bottom: 15px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: #f5f5f5;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #667eea;
            border-bottom: 2px solid #ddd;
        }
        
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        tr:hover {
            background: #f9f9f9;
        }
        
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .empty-message {
            text-align: center;
            color: #999;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🏥 Gestion Patients - Tableau de Bord</h1>
        <div class="navbar-user">
            <span><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?> (<?php echo ucfirst($user['role']); ?>)</span>
            <a href="logout.php">Déconnexion</a>
        </div>
    </div>
    
    <div class="container">
        <div class="welcome">
            <h2>Bienvenue, <?php echo htmlspecialchars($user['prenom']); ?>! 👋</h2>
            <p>Vous êtes connecté en tant que <strong><?php echo ucfirst($user['role']); ?></strong> - <?php echo htmlspecialchars($user['email']); ?></p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>👥 Patients</h3>
                <div class="number"><?php echo $stats['patients']; ?></div>
            </div>
            <div class="stat-card">
                <h3>👨‍⚕️ Médecins</h3>
                <div class="number"><?php echo $stats['medecins']; ?></div>
            </div>
            <div class="stat-card">
                <h3>📅 RDV Aujourd'hui</h3>
                <div class="number"><?php echo $stats['rdv_today']; ?></div>
            </div>
            <div class="stat-card">
                <h3>⏳ Examens en Attente</h3>
                <div class="number"><?php echo $stats['examens_pending']; ?></div>
            </div>
        </div>
        
        <div class="section">
            <h3>📅 Rendez-vous d'Aujourd'hui</h3>
            <?php if (count($rdv_today) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Heure</th>
                            <th>Patient</th>
                            <th>Médecin</th>
                            <th>Type d'Examen</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rdv_today as $rdv): ?>
                            <tr>
                                <td><?php echo substr($rdv['heure_rdv'], 0, 5); ?></td>
                                <td><?php echo htmlspecialchars($rdv['patient_nom'] . ' ' . $rdv['patient_prenom']); ?></td>
                                <td><?php echo htmlspecialchars($rdv['medecin_nom'] . ' ' . $rdv['medecin_prenom']); ?></td>
                                <td><?php echo htmlspecialchars($rdv['type_examen'] ?? 'N/A'); ?></td>
                                <td><span class="badge badge-success"><?php echo ucfirst($rdv['statut']); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-message">Aucun rendez-vous d'aujourd'hui</div>
            <?php endif; ?>
        </div>
        
        <div class="section">
            <h3>👥 Derniers Patients Inscrits</h3>
            <?php if (count($last_patients) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>N° Dossier</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Date d'Inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($last_patients as $patient): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($patient['num_dossier']); ?></strong></td>
                                <td><?php echo htmlspecialchars($patient['nom']); ?></td>
                                <td><?php echo htmlspecialchars($patient['prenom']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($patient['date_inscription'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-message">Aucun patient inscrit</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
