<?php
include '../database.php';
include 'sidebar.php.php';

// Fetch all responses from the alumni_responses table
$result = $conn->query("SELECT * FROM alumni_responses");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Alumni Tracer Responses</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .container {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            min-height: calc(100vh - 5rem);
            padding-top: 3rem;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container">
        <h1 class="text-2xl font-bold mb-6 text-center">View Alumni Tracer Responses</h1>

        <div class="overflow-x-auto w-full max-w-6xl">
            <table class="min-w-full bg-white rounded shadow">
                <thead class="bg-gray-200 text-gray-600">
                    <tr>
                        <th class="py-3 px-4 text-left">Alumni ID</th>
                        <th class="py-3 px-4 text-left">Degree Program</th>
                        <th class="py-3 px-4 text-left">Batch Year</th>
                        <th class="py-3 px-4 text-left">View Answers</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td class="py-3 px-4"><?= htmlspecialchars($row['id_number']) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($row['degree_program']) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($row['batch']) ?></td>
                            <td class="py-3 px-4">
                                <button onclick="viewAnswers(<?= $row['id'] ?>)" class="bg-blue-500 text-white px-4 py-2 rounded">View</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal to view answers -->
        <div id="answersModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
            <div class="bg-white p-6 rounded shadow-md w-3/4 max-w-3xl">
                <h2 class="text-xl font-semibold mb-4">Alumni Responses</h2>
                <div id="answersContent" class="mb-4"></div>
                <button onclick="closeModal()" class="bg-red-500 text-white px-6 py-2 rounded">Close</button>
            </div>
        </div>
    </div>

    <script>
        // View answers modal logic
        function viewAnswers(responseId) {
            // Fetch the responses for the selected alumni response
            fetch('get_response_details.php?id=' + responseId)
                .then(response => response.json())
                .then(data => {
                    // Display answers in the modal
                    const content = document.getElementById('answersContent');
                    content.innerHTML = `
                        <p><strong>Alumni ID:</strong> ${data.id_number}</p>
                        <p><strong>Degree Program:</strong> ${data.degree_program}</p>
                        <p><strong>Batch Year:</strong> ${data.batch}</p>
                        <p><strong>Answers:</strong></p>
                        <pre>${JSON.stringify(data.responses, null, 2)}</pre>
                    `;
                    document.getElementById('answersModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching response details:', error);
                });
        }

        // Close the modal
        function closeModal() {
            document.getElementById('answersModal').classList.add('hidden');
        }
    </script>
</body>
</html>
