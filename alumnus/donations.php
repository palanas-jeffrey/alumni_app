
<?php 
  include "../payment/paymongo/payment-link.php";

    if (isset($_POST['donateBtn'])) {
        $amount = intval($_POST['donationAmount'])*100; //counting in paymongo is in cents thus multiplying it to 100
        startPayMongoTransaction(intval($amount), $encodedString);
    }
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

    <?php if (!$checkout_url): ?>
        <div class="max-w-3xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold text-center text-blue-700 mb-6">Alumni Donation</h1>

            <form method="POST" id="donationForm" class="space-y-6">
                <div>
                    <label for="donationType" class="block text-gray-700 font-semibold">What would you like to donate?</label>
                    <select id="donationType" name="donationType" onchange="toggleDonationFields()" class="mt-2 w-full p-3 border border-gray-300 rounded-lg">
                        <option value="money">Money</option>
                        <!-- <option value="items">Items</option> -->
                    </select>
                </div>

                <div id="moneyFields" class="space-y-4">
                    <div>
                        <label for="donationAmount" class="block text-gray-700 font-semibold">Amount</label>
                        <input type="number" id="donationAmount" name="donationAmount" placeholder="Enter Amount" class="mt-2 w-full p-3 border border-gray-300 rounded-lg" required />
                    </div>
                </div>

                <button type="submit" name="donateBtn" class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">Donate</button>
            </form>
        </div>
    <?php endif; ?>

    <?php if ($checkout_url): ?>
        <iframe class="block h-screen w-screen border-none" src="<?php echo $checkout_url; ?>" title="description"></iframe>
    <?php endif; ?>

    <script>
        function toggleDonationFields() {
            const donationType = document.getElementById('donationType').value;
            document.getElementById('moneyFields').classList.toggle('hidden', donationType !== 'money');
        }
    </script>
</body>
</html>
