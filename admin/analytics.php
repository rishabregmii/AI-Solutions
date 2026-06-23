<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'database.php';

$total_inquiries = $connection->query("SELECT COUNT(*) as count FROM contact_inquiries")->fetch_assoc()['count'];
$this_month = $connection->query("SELECT COUNT(*) as count FROM contact_inquiries WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())")->fetch_assoc()['count'];
$top_country = $connection->query("SELECT country, COUNT(*) as count FROM contact_inquiries WHERE country != '' GROUP BY country ORDER BY count DESC LIMIT 1")->fetch_assoc();
$avg_per_week = $connection->query("SELECT COUNT(*) / 4 as avg FROM contact_inquiries WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH)")->fetch_assoc()['avg'];

$monthly_data = $connection->query("SELECT DATE_FORMAT(created_at, '%M') as month, COUNT(*) as count FROM contact_inquiries WHERE created_at > DATE_SUB(NOW(), INTERVAL 6 MONTH) GROUP BY MONTH(created_at) ORDER BY created_at");
$country_data = $connection->query("SELECT country, COUNT(*) as count FROM contact_inquiries WHERE country != '' GROUP BY country ORDER BY count DESC LIMIT 5");
$recent = $connection->query("SELECT * FROM contact_inquiries ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - AI Solutions</title>
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="page-container">
    <!-- Top Bar with Back Button -->
    <div class="top-bar">
        <a href="index.php" class="back-to-dashboard">← Back to Dashboard</a>
        <h1>Analytics Overview</h1>
    </div>

    <main class="page-content">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-info">
                    <h3><?php echo $total_inquiries; ?></h3>
                    <p>Total Inquiries</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-info">
                    <h3><?php echo $this_month; ?></h3>
                    <p>This Month</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🌍</div>
                <div class="stat-info">
                    <h3><?php echo $top_country ? htmlspecialchars($top_country['country']) : 'N/A'; ?></h3>
                    <p>Top Country</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-info">
                    <h3><?php echo round($avg_per_week ?? 0); ?></h3>
                    <p>Avg per week</p>
                </div>
            </div>
        </div>

        <div class="charts-row">
            <div class="chart-card">
                <h3>Inquiries over time (Monthly Trend)</h3>
                <canvas id="monthlyChart" height="200"></canvas>
            </div>
            <div class="chart-card">
                <h3>By Country</h3>
                <canvas id="countryChart" height="200"></canvas>
            </div>
        </div>

        <div class="recent-section">
            <div class="section-header">
                <h2>Recent Inquiries</h2>
                <a href="inquiries.php" class="view-all">View All →</a>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead><tr><th>Name</th><th>Email</th><th>Company</th><th>Country</th><th>Date</th></tr></thead>
                    <tbody>
                        <?php while ($row = $recent->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['company']); ?></td>
                            <td><?php echo htmlspecialchars($row['country']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: [<?php while($d = $monthly_data->fetch_assoc()) echo '"' . $d['month'] . '",'; ?>],
            datasets: [{
                label: 'Inquiries',
                data: [<?php $monthly_data->data_seek(0); while($d = $monthly_data->fetch_assoc()) echo $d['count'] . ','; ?>],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: { responsive: true, maintainAspectRatio: true }
    });

    const countryCtx = document.getElementById('countryChart').getContext('2d');
    new Chart(countryCtx, {
        type: 'bar',
        data: {
            labels: [<?php while($c = $country_data->fetch_assoc()) echo '"' . $c['country'] . '",'; ?>],
            datasets: [{
                label: 'Inquiries',
                data: [<?php $country_data->data_seek(0); while($c = $country_data->fetch_assoc()) echo $c['count'] . ','; ?>],
                backgroundColor: '#007bff',
                borderRadius: 8
            }]
        },
        options: { responsive: true, maintainAspectRatio: true }
    });
</script>
</body>
</html>