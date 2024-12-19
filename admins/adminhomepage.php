<script src="https://cdn.tailwindcss.com"></script>
<?php include 'sidebar.php'?>
<div class="w-5/6 h-screen ml-auto p-8">
            <h1 class="text-4xl font-semibold text-center mb-10">Admin Dashboard</h1>

            <!-- Admin Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pending Approvals -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-2xl font-semibold mb-4">Pending Alumni Approvals</h2>
                    <p class="mb-4">View and approve alumni who are pending approval.</p>
                    <a href="approve_alumni.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        View Pending Approvals
                    </a>
                </div>

                <!-- View Alumni Report -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-2xl font-semibold mb-4">Alumni Report</h2>
                    <p class="mb-4">View detailed information of all alumni.</p>
                    <a href="alumni_report.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        View Alumni Report
                    </a>
                </div>

                <!-- Export as CSV -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-2xl font-semibold mb-4">Export Alumni as CSV</h2>
                    <p class="mb-4">Export all alumni data as a CSV file.</p>
                    <a href="export_alumni_csv.php" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                        Export as CSV
                    </a>
                </div>

                <!-- Export as Excel -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-2xl font-semibold mb-4">Export Alumni as Excel</h2>
                    <p class="mb-4">Export all alumni data as an Excel file.</p>
                    <a href="export_alumni_excel.php" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        Export as Excel
                    </a>
                </div>
            </div>
        </div>