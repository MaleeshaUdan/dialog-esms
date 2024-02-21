<?php
require 'msgfunctions.php';

$apiKey = "";//Replace with your actual URL Message Key
$numberList = [""]; // You can add more numbers as needed
$message = "";//Message
$sourceAddress = ""; // Now passing source address as a parameter (MASK)

$responseMessage = sendMessage($apiKey, $numberList, $message, $sourceAddress);
echo $responseMessage;
?>