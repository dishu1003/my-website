<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
require_login();

$user = get_current_user();
$pdo = get_pdo_connection();

// Fetch leads assigned to this user
$stmt = $pdo->prepare("
    SELECT * FROM leads 
    WHERE assigned_to = ? 
    ORDER BY created_at DESC
");
$stmt->execute([$user['id']]);
$leads = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <nav class="dashboard-nav">
        <h1><?php echo SITE_NAME; ?></h1>
        <div>
            <span>Welcome, <?php echo htmlspecialchars($user['name']); ?></span>
            <a href="/logout.php">Logout</a>
        </div>
    </nav>
    
    <div class="dashboard">
        <aside class="sidebar">
            <ul>
                <li><a href="/team/">My Leads</a></li>
                <li><a href="/team/scripts.php">Scripts Library</a></li>
            </ul>
            
            <div class="ref-link-box">
                <h3>Your Referral Link</h3>
                <input type="text" value="<?php echo SITE_URL; ?>/?ref=<?php echo $user['unique_ref']; ?>" readonly onclick="this.select()">
                <button onclick="copyRefLink()">Copy Link</button>
            </div>
        </aside>
        
        <main class="main-content">
            <h2>My Leads (<?php echo count($leads); ?>)</h2>
            
            <div class="filters">
                <input type="text" id="search" placeholder="Search leads...">
                <select id="filter-score">
                    <option value="">All Scores</option>
                    <option value="HOT">HOT</option>
                    <option value="WARM">WARM</option>
                    <option value="COLD">COLD</option>
                </select>
                <select id="filter-status">
                    <option value="">All Status</option>
                    <option value="new">New</option>
                    <option value="contacted">Contacted</option>
                    <option value="qualified">Qualified</option>
                    <option value="converted">Converted</option>
                    <option value="lost">Lost</option>
                </select>
            </div>
            
            <table class="leads-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Step</th>
                        <th>Score</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leads as $lead): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($lead['name']); ?></td>
                        <td><?php echo htmlspecialchars($lead['phone']); ?></td>
                        <td><?php echo $lead['current_step']; ?>/4</td>
                        <td><span class="badge badge-<?php echo strtolower($lead['lead_score']); ?>"><?php echo $lead['lead_score']; ?></span></td>
                        <td><?php echo ucfirst($lead['status']); ?></td>
                        <td><?php echo date('d M Y', strtotime($lead['created_at'])); ?></td>
                        <td>
                            <a href="/team/lead-detail.php?id=<?php echo $lead['id']; ?>" class="btn-small">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
    
    <script>
        function copyRefLink() {
            const input = document.querySelector('.ref-link-box input');
            input.select();
            document.execCommand('copy');
            alert('Link copied!');
        }
    </script>
</body>
</html>