<!DOCTYPE html>

<?php
    session_start();
    require_once '../resources/config.php';
    // require_once ENTITIES_PATH . '/Account_User.php';
    // require_once ENTITIES_PATH . '/Appointment_Record.php';
    // require_once ENUMS_PATH . '/User_Type.php';
    // require_once FUNCTIONS_PATH . '/PatientFunctions.php';
    // require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';
    ?>

    <?php
    include TEMPLATES_PATH . '/bootstrap.php';
    include_once TEMPLATES_PATH . '/navbar.php';
    ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health and Information Tips</title>
    <link href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />

    <style>
    <?php include './css/healthlist.css';
    ?>
    </style>

</head>
<body>
<div class="container">

<h1 class="mb-3"> Health Information and Tips</h1>
<div id="wrapper"></div>
</div>



    
</body>


<script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script>
<script>


const grid = new gridjs.Grid({
    columns: ["Title", "Description", "Author", "Posted on"],
    search: true,
    data: [
        ["Why You Should Take Care of Your Body and Health", "Health problems, even minor ones, can interfere with or even ...", "Dr. John Mark", "1 year ago"],
        ["Fast Remedies When Having Headache", "Even relatively minor health issues such as aches, pains, lethargy ...", "Dr. John Mark", "5 days ago"],
        ["Reasons Why Sleep is the Most Important Part of the Day", "Sleep can have a serious impact on your overall health and well-being. Make a ...", "Dr. David Jones", "2 days ago"],
        ["Your Posture is Affecting Your Health", "We've all heard the advice to eat right and exercise, but it can be difficult to fit in ...", "Dr. Sarah Eoin", "7 days ago"],
        ["Eat This Everyday, and You Will Feel Better", "Indigestion take a toll on your happiness and stress ...", "Dr. John Mark", "1 year ago"],
        ["What To do When You are Bloated", "Rather than eating right solely for the promise of...", "Dr. John Mark", "5 days ago"],
        ["Why You Should Take Care of Your Body and Health", ">Health problems, even minor ones, can interfere with or even ...", "Dr. John Mark", "1 year ago"],
        ["Fast Remedies When Having Headache", "Even relatively minor health issues such as aches, pains, lethargy ...", "Dr. John Mark", "5 days ago"],
        ["Reasons Why Sleep is the Most Important Part of the Day", "Sleep can have a serious impact on your overall health and well-being. Make a ...", "Dr. David Jones", "2 days ago"],
        ["Your Posture is Affecting Your Health", "We've all heard the advice to eat right and exercise, but it can be difficult to fit in ...", "Dr. Sarah Eoin", "7 days ago"],
        ["Eat This Everyday, and You Will Feel Better", "Indigestion take a toll on your happiness and stress ...", "Dr. John Mark", "1 year ago"],
        ["What To do When You are Bloated", "Rather than eating right solely for the promise of...", "Dr. John Mark", "5 days ago"],
        ["Why You Should Take Care of Your Body and Health", ">Health problems, even minor ones, can interfere with or even ...", "Dr. John Mark", "1 year ago"],
        ["Fast Remedies When Having Headache", "Even relatively minor health issues such as aches, pains, lethargy ...", "Dr. John Mark", "5 days ago"],
        ["Reasons Why Sleep is the Most Important Part of the Day", "Sleep can have a serious impact on your overall health and well-being. Make a ...", "Dr. David Jones", "2 days ago"],
        ["Your Posture is Affecting Your Health", "We've all heard the advice to eat right and exercise, but it can be difficult to fit in ...", "Dr. Sarah Eoin", "7 days ago"],
        ["Eat This Everyday, and You Will Feel Better", "Indigestion take a toll on your happiness and stress ...", "Dr. John Mark", "1 year ago"],
        ["What To do When You are Bloated", "Rather than eating right solely for the promise of...", "Dr. John Mark", "5 days ago"]
    ],
    pagination: {
        enabled: true,
        limit: 9,
        summary: false
    },
    style: {
    table: {
      border: '3px solid #ccc'
    },
    tr: hover td
    {
      'background-color': 'rgba(0, 0, 0, 0.1)';
    },
    th: {
      'text-align: 'center;
      &:hover {
        'background-color': '#999';
        color: #fff;
    },
    td: {
      color: #999;
      &:hover {
        color: #000;
      }
    }
  }
    
})

grid.render(document.getElementById("wrapper"));

grid.on('rowClick', (...args) => console.log('row: ' + JSON.stringify(args), args));
grid.on('cellClick', (...args) => console.log('cell: ' + JSON.stringify(args), args));
</script>

</html>