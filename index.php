<?php
$result = null;
$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $loanAmount = floatval($_POST["loan_amount"]);
    $months = intval($_POST["loan_term"]);

   
    $MIN_LOAN = 500;
    $MAX_LOAN = 50000;
    $MONTHLY_INTEREST_RATE = 0.02; 

    
    if ($loanAmount < $MIN_LOAN || $loanAmount > $MAX_LOAN) {
        $error = "Loan amount must be between ₱500 and ₱50,000.";
    } elseif (!in_array($months, [1, 3, 6, 9, 12, 24])) {
        $error = "Invalid loan term selected.";
    } else {

        $P = $loanAmount;
        $r = $MONTHLY_INTEREST_RATE;
        $n = $months;

        
        $paymentPerMonth = $P * ($r * pow(1 + $r, $n)) / (pow(1 + $r, $n) - 1);
        $totalAmountToPay = $paymentPerMonth * $n;
        $totalInterest = $totalAmountToPay - $P;
        $monthlyInterest = $P * $r;

        $result = [
            "monthly_interest" => $monthlyInterest,
            "payment_per_month" => $paymentPerMonth,
            "total_interest" => $totalInterest,
            "total_amount" => $totalAmountToPay
        ];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lending System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 30px;
        }
        .container {
            max-width: 400px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        label {
            font-weight: bold;
        }
        input, select, button {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .result {
            background: #e9f5ff;
            padding: 15px;
            border-radius: 5px;
        }
        .error {
            background: #ffe0e0;
            color: #a00;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Lending System</h2>

    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Loan Amount (₱500 – ₱50,000)</label>
        <input type="number" name="loan_amount" min="500" max="50000" required>

        <label>Loan Term</label>
        <select name="loan_term" required>
            <option value="">-- Select Months --</option>
            <option value="1">1 Month</option>
            <option value="3">3 Months</option>
            <option value="6">6 Months</option>
            <option value="9">9 Months</option>
            <option value="12">12 Months</option>
            <option value="24">24 Months</option>
        </select>

        <button type="submit">Calculate</button>
    </form>

    <?php if ($result): ?>
        <div class="result">
            <p><strong>Monthly Interest:</strong> ₱<?= number_format($result["monthly_interest"], 2) ?></p>
            <p><strong>Payment per Month:</strong> ₱<?= number_format($result["payment_per_month"], 2) ?></p>
            <p><strong>Total Interest:</strong> ₱<?= number_format($result["total_interest"], 2) ?></p>
            <p><strong>Total Amount to Pay:</strong> ₱<?= number_format($result["total_amount"], 2) ?></p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>