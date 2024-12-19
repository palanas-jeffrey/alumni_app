<?php
ob_start(); // Start output buffering
include '../database.php';

// Handle Delete Action
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $referenceNumber = $_GET['delete'];
    
    // Delete donation record
    $query = "DELETE FROM donations WHERE reference_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $referenceNumber);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Donation deleted successfully!";
    } else {
        $_SESSION['message'] = "Failed to delete donation.";
    }
    $stmt->close();
    header("Location: AdminMonitorDonations.php");
    exit();
}

// Handle Complete Action
if (isset($_GET['complete']) && !empty($_GET['complete'])) {
    $referenceNumber = $_GET['complete'];
    
    // Update donation status to 'Complete'
    $query = "UPDATE donations SET status = 'Complete' WHERE reference_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $referenceNumber);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Donation marked as complete!";
    } else {
        $_SESSION['message'] = "Failed to update donation status.";
    }
    $stmt->close();
    header("Location: AdminMonitorDonations.php");
    exit();
}

// Fetch all donations from the database for admin
$donations = [];
$query = "SELECT * FROM donations ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $donations[] = $row;
}

$stmt->close();
$conn->close();
?>
<?php include 'sidebar.php' ?>
<div class="w-full h-screen flex justify-center items-start pt-8"> <!-- Changed to align content at the top -->
  <div class="max-w-screen-xl w-full px-6 md:px-12 py-8 bg-white shadow-lg rounded-lg">
    <h2 class="text-3xl font-semibold text-center text-gray-800 mb-6">Admin Donation Monitoring</h2>

    <!-- Display Flash Messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="bg-green-500 text-white p-4 mb-4 rounded-lg">
            <?= $_SESSION['message']; ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Donations Table -->
    <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
      <table class="min-w-full table-auto">
        <thead class="bg-gray-100">
          <tr>
            <th class="py-3 px-6 text-left text-lg font-semibold text-gray-700">Reference Number</th>
            <th class="py-3 px-6 text-left text-lg font-semibold text-gray-700">Donation Type</th>
            <th class="py-3 px-6 text-left text-lg font-semibold text-gray-700">Details</th>
            <th class="py-3 px-6 text-left text-lg font-semibold text-gray-700">Date</th>
            <th class="py-3 px-6 text-left text-lg font-semibold text-gray-700">Status</th>
            <th class="py-3 px-6 text-left text-lg font-semibold text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php foreach ($donations as $donation): ?>
            <tr class="hover:bg-gray-50">
              <td class="py-3 px-6 text-gray-800"><?= htmlspecialchars($donation['reference_number']) ?></td>
              <td class="py-3 px-6 text-gray-800">
                <?= htmlspecialchars(ucfirst($donation['donation_type'] === 'money' ? 'Money (GCash)' : 'Items')) ?>
              </td>
              <td class="py-3 px-6 text-gray-800">
                <?php if ($donation['donation_type'] === 'money'): ?>
                  Amount: <?= htmlspecialchars($donation['donation_amount']) ?><br>
                  GCash Number: <?= htmlspecialchars($donation['gcash_number']) ?>
                <?php else: ?>
                 <?= htmlspecialchars($donation['item_details'] ?? 'No details provided') ?>
                <?php endif; ?>
              </td>
              <td class="py-3 px-6 text-gray-800"><?= date('F j, Y, g:i a', strtotime($donation['created_at'])) ?></td>
              <td class="py-3 px-6 text-gray-800">
                <?= htmlspecialchars($donation['status'] ?? 'Pending') ?>
              </td>
              <td class="py-3 px-6 space-y-2 flex flex-col sm:space-y-0 sm:space-x-2 sm:flex-row">
                <?php if (($donation['status'] ?? 'Pending') !== 'Complete'): ?>
                  <!-- Complete Button only shows if status is not 'Complete' -->
                  <button type="button" 
                          onclick="window.location.href='?complete=<?= urlencode($donation['reference_number']) ?>';"
                          class="inline-block text-white bg-blue-600 hover:bg-blue-700 py-2 px-4 rounded-lg shadow-md transition duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500">
                          Complete
                  </button>
                <?php endif; ?>

                <!-- Delete Button -->
                <button type="button"
                        onclick="if (confirm('Are you sure you want to delete this donation?')) { window.location.href='?delete=<?= urlencode($donation['reference_number']) ?>'; }"
                        class="inline-block text-white bg-red-600 hover:bg-red-700 py-2 px-4 rounded-lg shadow-md transition duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Delete
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
