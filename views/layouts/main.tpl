<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="VetCare - Professional veterinary management system for staff and administrators">
    <meta name="keywords" content="veterinary, clinic, pet care, animal hospital, staff portal">
    <title>Veterinary Clinic</title>
    <link rel="stylesheet" href="./css/styles.min.css">
    <link rel="icon" type="image/png" href="./images/Monogram Black HQ.png">
    <link rel="stylesheet" href="css/transitions.css">
</head>

<body id="page-{$view_name}">
    <header class="bg-custom-primary text-white py-4">
        <div class="container">
            <img src="./images/Logo White.webp" alt="VetCare Logo" class="img-fluid" style="max-height: 80px;">
        </div>
    </header>

    {block name="body"}{/block}
    <script src="./js/scripts-vendor.min.js"></script>
    <script src="./js/scripts.min.js"></script>
    <script src="./js/toast-utils.js"></script>
</body>

</html>