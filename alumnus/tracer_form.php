<?php
include '../database.php';
include 'navbar.php';

// Handle form submission for alumni
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_number = $_POST['id_number'];
    $degree_program = $_POST['degree_program'];
    $batch = $_POST['batch'];
    $answers = json_encode($_POST['answers']);

    $stmt = $conn->prepare("INSERT INTO alumni_responses (id_number, degree_program, batch, responses) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $id_number, $degree_program, $batch, $answers);
    $stmt->execute();
    echo "Thank you for your response!";
    exit;
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
    <title>Alumni Tracer and Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
            padding: 2rem;
        }
        .chart-container {
            flex: 1;
            max-width: 50%;
        }
        .form-container {
            flex: 1;
            max-width: 50%;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container">
        <!-- Chart Section -->
        <div class="chart-container bg-white p-6 rounded shadow-md">
            <h1 class="text-2xl font-bold mb-4 text-center">Alumni Responses Chart</h1>
            <canvas id="responsesChart"></canvas>
        </div>

        <!-- Alumni Tracer Form -->
        <div class="form-container bg-white p-6 rounded shadow-md">
            <h1 class="text-2xl font-bold mb-6 text-center">Alumni Tracer Form</h1>
            <form action="" method="POST" class="space-y-4">
                <!-- ID Number Input -->
                <label class="block">
                    Alumni ID Number:
                    <input type="text" name="id_number" class="border p-2 w-full" required>
                </label>

                <!-- Degree Program Selection -->
                <label class="block">
                    Degree Program:
                    <select name="degree_program" id="degree_program" class="border p-2 w-full" required>
                        <option value="">Select Degree Program</option>
                        <option value="BSIT">BSIT</option>
                        <option value="BSCS">BSCS</option>
                        <option value="BSIS">BSIS</option>
                        <option value="BSSE">BSSE</option>
                    </select>
                </label>

                <!-- Batch Input -->
                <label class="block">
                    Batch Year:
                    <input type="text" name="batch" class="border p-2 w-full" required>
                </label>

                <!-- Dynamic Questions Table -->
                <div id="question_table" class="hidden">
                    <h2 class="text-lg font-semibold mb-4">Answer the Questions</h2>
                    <table class="min-w-full bg-white rounded shadow">
                        <thead class="bg-gray-200 text-gray-600">
                            <tr>
                                <th class="py-3 px-4 text-left">Question</th>
                                <th class="py-3 px-4 text-left">Answer</th>
                            </tr>
                        </thead>
                        <tbody id="questions_body">
                            <!-- Questions will load dynamically here -->
                        </tbody>
                    </table>
                </div>

                <!-- Submit Button -->
                <button id="submit_button" type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 hidden">Submit</button>
            </form>
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

        // Fetch and populate questions dynamically
        document.getElementById('degree_program').addEventListener('change', function () {
            const degreeProgram = this.value;
            const questionTable = document.getElementById('question_table');
            const submitButton = document.getElementById('submit_button');
            const questionsBody = document.getElementById('questions_body');

            if (degreeProgram) {
                questionTable.classList.remove('hidden');
                submitButton.classList.remove('hidden');
                questionsBody.innerHTML = '';

                fetch('get_questions.php?degree_program=' + degreeProgram)
                    .then(response => response.json())
                    .then(data => {
                        data.questions.forEach(question => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="py-3 px-4">${question.question_text}</td>
                                <td class="py-3 px-4">
                                    <input type="${question.question_type}" name="answers[${question.id}]" class="border p-2 w-full" required>
                                </td>
                            `;
                            questionsBody.appendChild(row);
                        });
                    })
                    .catch(error => console.error('Error loading questions:', error));
            } else {
                questionTable.classList.add('hidden');
                submitButton.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
