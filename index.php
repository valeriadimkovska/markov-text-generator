<?php

require 'markov.php';

function process_post() {
    // generate text with markov library
    $order  = $_POST['order'];
    $length = $_POST['length'];
    $input  = $_POST['input'];
   
    

    if (!ctype_digit($order) || !ctype_digit($length)) {
        throw new Exception("Your order or length are not correct");
    }

    $order = (int) $order;
    $length = (int) $length;

    if ($order < 0 || $order > 20) {
        throw new Exception("Invalid order");
    }

    if ($length < 1 || $length > 25000) {
        throw new Exception("Text length is too short or too long");
    }

    if ($input) {
        $text = $input;
    } else if ($ptext) {
        if (!in_array($ptext, ['alice', 'calvin', 'kant'])) {
            throw new Exception("Invalid text");
        } else {
            $text = file_get_contents("./text/$ptext.txt");
        }
    }

    if (empty($text)) {
        throw new Exception("No text given");
    }

    $markov_table = generate_markov_table($text, $order);
    $markov = generate_markov_text($length, $markov_table, $order);
    return htmlentities($markov);
}

if (isset($_POST['submit'])) {
    try {
        $markov = process_post();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <title>PHP-Марков извор</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<div id="wrapper">
    <h1>PHP-текст генератор со Марков Извор</h1>
    <p>
        Едноставен текст генератор кој користи Марков извор. 
        Внесете текст подолу или изберете еден од можните понудени.
        </p>

    <?p if ($markov) ?>
        <h2>Излез</h2>
        <textarea rows="20" cols="60" readonly="readonly"><?= $markov ?></textarea>

    <h2>Внеси текст...</h2>
    <form method="post" action="" name="markov">
        <textarea rows="20" cols="80" name="input"></textarea>
        <br />
        <br />
        <label for="order">Должина на префикс</label>
        <input type="text" name="order" value="" />
        <label for="length">Должина на излез</label>
        <input type="text" name="length" value="2500" />
        <br />
        <input type="submit" name="submit" value="GO" />
    </form>
</div> <!-- /wrapper -->
</body>
</html>
