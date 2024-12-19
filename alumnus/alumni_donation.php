<?php
// Include database connection
include '../database.php';

// GCash Details
$gcashNumber = "09297682599";
$gcashName = "Renan Importante";

// Path to the generated QR code image
$qrCodePath = "/path/to/your/qrcode/gcash_qr_code.png"; // Update this to the actual server path
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Donation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-3xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-center text-blue-700 mb-6">Alumni Donation</h1>

        <form method="POST" id="donationForm" class="space-y-6">
            <div>
                <label for="donationType" class="block text-gray-700 font-semibold">What would you like to donate?</label>
                <select id="donationType" name="donationType" onchange="toggleDonationFields()" class="mt-2 w-full p-3 border border-gray-300 rounded-lg">
                    <option value="money">Money</option>
                    <option value="items">Items</option>
                </select>
            </div>

            <div id="moneyFields" class="space-y-4">
                <div>
                    <label for="donationAmount" class="block text-gray-700 font-semibold">Amount</label>
                    <input type="number" id="donationAmount" name="donationAmount" placeholder="Enter Amount" class="mt-2 w-full p-3 border border-gray-300 rounded-lg" />
                </div>
                <div>
                    <label for="paymentMethod" class="block text-gray-700 font-semibold">Payment Method</label>
                    <select id="paymentMethod" name="paymentMethod" onchange="toggleGCashFields()" class="mt-2 w-full p-3 border border-gray-300 rounded-lg">
                        <option value="gcash">GCash</option>
                    </select>
                </div>
                <div id="gcashFields" class="hidden">
                    <label for="gcashNumber" class="block text-gray-700 font-semibold">GCash Number</label>
                    <input type="text" id="gcashNumber" name="gcashNumber" value="<?php echo $gcashNumber; ?>" readonly class="mt-2 w-full p-3 border border-gray-300 bg-gray-100 rounded-lg" />
                </div>
            </div>

            <div id="itemFields" class="space-y-4 hidden">
                <label for="itemDetails" class="block text-gray-700 font-semibold">Item Details</label>
                <textarea id="itemDetails" name="itemDetails" placeholder="Enter item details" class="mt-2 w-full p-3 border border-gray-300 rounded-lg"></textarea>
            </div>

            <div>
                <label for="reference_number" class="block text-gray-700 font-semibold">Reference Number</label>
                <input type="text" id="reference_number" name="reference_number" placeholder="Enter Reference Number" class="mt-2 w-full p-3 border border-gray-300 rounded-lg" required />
            </div>

            <!-- QR Code Section -->
            <div class="text-center my-6">
                <h2 class="text-lg font-semibold text-gray-700">Scan to Donate via GCash</h2>
                <p class="text-gray-600">Name: <?php echo $gcashName; ?><br>Number: <?php echo $gcashNumber; ?></p>
                <img src="<?php echo $qrCodePath; ?>" alt="GCash QR Code" class="mx-auto mt-4 w-48 h-48 rounded-lg border border-gray-300" />
            </div>

            <button type="submit" class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">Submit Donation</button>
        </form>
    </div>

    <script>
        function toggleDonationFields() {
            const donationType = document.getElementById('donationType').value;
            document.getElementById('moneyFields').classList.toggle('hidden', donationType !== 'money');
            document.getElementById('itemFields').classList.toggle('hidden', donationType !== 'items');
        }

        function toggleGCashFields() {
            const paymentMethod = document.getElementById('paymentMethod').value;
            document.getElementById('gcashFields').classList.toggle('hidden', paymentMethod !== 'gcash');
        }
    </script>
</body>
</html>
