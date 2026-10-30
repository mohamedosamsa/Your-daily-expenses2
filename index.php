```php
<?php

session_start();

// إنشاء المصروفات الافتراضية أول مرة فقط
if (!isset($_SESSION["expenses"])) {

    $_SESSION["expenses"] = [

        [
            "name" => "مواصلات",
            "amount" => 50
        ],

        [
            "name" => "طعام",
            "amount" => 100
        ],

        [
            "name" => "إنترنت",
            "amount" => 200
        ]

    ];

}

// استقبال طلب الحذف
if (isset($_POST["delete_expense"])) {

    $index = (int) $_POST["delete_index"];

    if (isset($_SESSION["expenses"][$index])) {

        unset($_SESSION["expenses"][$index]);

    }

}

// استقبال البيانات من الفورم
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["expense_name"])) {

    $expense_name = $_POST["expense_name"];
    $expense_amount = $_POST["expense_amount"];

    // إضافة المصروف الجديد
    if ($expense_name != "" && $expense_amount > 0) {

        $_SESSION["expenses"][] = [
            "name" => $expense_name,
            "amount" => (float) $expense_amount
        ];

    }

}

// استرجاع المصروفات من Session
$expenses = $_SESSION["expenses"];

// حساب إجمالي المصروفات
$total = 0;

foreach ($expenses as $expense) {

    $total += $expense["amount"];

}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>مصروفاتي اليومية</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f4f7;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px #ddd;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        .expense {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding: 15px;
            margin-bottom: 10px;
            background-color: #f7f9fc;
            border-radius: 8px;
        }

        .total {
            margin-top: 20px;
            padding: 15px;
            background-color: #e0f2e9;
            border-radius: 8px;
            font-size: 20px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
        }

        input, button {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            margin-top: 10px;
            border-radius: 6px;
            font-size: 16px;
        }

        input {
            border: 1px solid #ccc;
        }

        button {
            background-color: #2980b9;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #21618c;
        }

        .delete-button {
            background-color: #e74c3c;
            width: auto;
            margin-top: 0;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }

        .expense form {
            margin: 0;
        }

    </style>

</head>

<body>

    <div class="container">

        <h1>مصروفاتي اليومية</h1>

        <h3>إضافة مصروف جديد</h3>

        <form method="POST" action="">

            <input
                type="text"
                name="expense_name"
                placeholder="اسم المصروف"
                required
            >

            <input
                type="number"
                name="expense_amount"
                placeholder="المبلغ بالجنيه"
                min="0.01"
                step="0.01"
                required
            >

            <button type="submit">إضافة المصروف</button>

        </form>

        <h3>قائمة المصروفات</h3>

        <?php foreach ($expenses as $index => $expense) { ?>

            <div class="expense">

                <span>
                    <?php echo htmlspecialchars($expense["name"]); ?>
                </span>

                <span>
                    <?php echo $expense["amount"]; ?> جنيه
                </span>

                <form method="POST" action="">

                    <input
                        type="hidden"
                        name="delete_index"
                        value="<?php echo $index; ?>"
                    >

                    <button
                        type="submit"
                        name="delete_expense"
                        class="delete-button"
                    >
                        حذف
                    </button>

                </form>

            </div>

        <?php } ?>

        <div class="total">

            <span>الإجمالي</span>

            <span>
                <?php echo $total; ?> جنيه
            </span>

        </div>

    </div>

</body>

</html>
```