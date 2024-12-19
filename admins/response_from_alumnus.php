<?php
include '../database.php'; // Include the database connection
include 'sidebar.php';

// Initialize search variables
$searchId = isset($_GET['id_number']) ? trim($_GET['id_number']) : '';
$searchDegree = isset($_GET['degree_program']) ? trim($_GET['degree_program']) : '';

// Fetch filtered alumni responses
$query = "SELECT * FROM alumni_responses WHERE 1=1";
$params = [];

if ($searchId) {
    $query .= " AND id_number LIKE ?";
    $params[] = "%$searchId%";
}

if ($searchDegree) {
    $query .= " AND degree_program LIKE ?";
    $params[] = "%$searchDegree%";
}

$stmt = $conn->prepare($query);
if ($params) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$responses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['responses'] = json_decode($row['responses'], true); // Decode JSON responses
        $responses[] = $row;
    }
}

// Fetch degree program counts for the chart
$query = "
    SELECT degree_program, COUNT(*) AS count 
    FROM alumni_responses 
    WHERE degree_program IN ('BSIT', 'BSIS', 'BSCS', 'BSSE') 
    GROUP BY degree_program";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$chartData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chartData[$row['degree_program']] = (int)$row['count'];
    }
}

// Ensure all degree programs are represented
$degreePrograms = ['BSIT', 'BSIS', 'BSCS', 'BSSE'];
foreach ($degreePrograms as $program) {
    if (!isset($chartData[$program])) {
        $chartData[$program] = 0;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alumni Responses and Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body, html {
            min-height: 100vh;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 flex items-center justify-center">

<div class="container flex flex-col items-center justify-center space-y-10 p-6">
    <!-- Search Form -->
    <div class="bg-white p-6 rounded shadow-md w-full max-w-6xl">
        <h1 class="text-2xl font-bold mb-4 text-center">Search Alumni Responses</h1>
        <form action="" method="GET" class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0">
            <input type="text" name="id_number" value="<?= htmlspecialchars($searchId) ?>" 
                   placeholder="Search by ID Number" 
                   class="border border-gray-300 rounded px-4 py-2 w-full md:w-1/3">
            <input type="text" name="degree_program" value="<?= htmlspecialchars($searchDegree) ?>" 
                   placeholder="Search by Degree Program" 
                   class="border border-gray-300 rounded px-4 py-2 w-full md:w-1/3">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600">Search</button>
        </form>
    </div>

    <!-- Alumni Responses Table -->
    <div class="bg-white p-6 rounded shadow-md w-full max-w-6xl">
        <h1 class="text-2xl font-bold mb-4 text-center">Alumni Responses</h1>
        <table class="min-w-full bg-white rounded shadow">
            <thead class="bg-gray-200 text-gray-600">
                <tr>
                    <th class="py-3 px-4 text-left">ID Number</th>
                    <th class="py-3 px-4 text-left">Degree Program</th>
                    <th class="py-3 px-4 text-left">Batch Year</th>
                    <th class="py-3 px-4 text-left">Responses</th>
                    <th class="py-3 px-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($responses): ?>
                    <?php foreach ($responses as $response): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4"><?= htmlspecialchars($response['id_number']) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($response['degree_program']) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($response['batch']) ?></td>
                            <td class="py-3 px-4">
                                <ul class="list-disc list-inside">
                                    <?php foreach ($response['responses'] as $question_id => $answer): ?>
                                        <li><strong>Q<?= $question_id ?>:</strong> <?= htmlspecialchars($answer) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                            <td class="py-3 px-4">
                                <a href="view_response.php?id=<?= urlencode($response['id_number']) ?>" 
                                   class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">No results found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pie Chart Section -->
    <div class="bg-white p-6 rounded shadow-md w-full max-w-4xl">
        <h1 class="text-2xl font-bold mb-4 text-center">Alumni Responses Chart</h1>
        <canvas id="responsesChart"></canvas>
    </div>
</div>

<script>
    // Data for the pie chart
    const chartLabels = ['BSIT', 'BSIS', 'BSCS', 'BSSE'];
    const chartData = <?= json_encode(array_values($chartData)) ?>;

    const data = {
        labels: chartLabels,
        datasets: [{
            label: 'Alumni Responses by Degree Program',
            data: chartData,
            backgroundColor: [
                'rgba(75, 192, 192, 0.6)',
                'rgba(54, 162, 235, 0.6)',
                'rgba(255, 206, 86, 0.6)',
                'rgba(255, 99, 132, 0.6)'
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        }]
    };

    const config = {
        type: 'pie',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = context.chart._metasets[0].total;
                            const percentage = ((value / total) * 100).toFixed(2);
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    };

    // Render the chart
    const ctx = document.getElementById('responsesChart').getContext('2d');
    new Chart(ctx, config);
</script>

</body>
</html>
