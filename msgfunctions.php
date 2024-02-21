<?php

/**
 * DIALOG eSMS SERVICE INTEGRATION
 * 
 * This PHP script is designed to facilitate sending SMS messages via GET requests
 * to the Dialog eSMS service. It encapsulates the necessary functionality for
 * crafting and executing requests to the Dialog eSMS API, handling response data,
 * and managing errors.
 * 
 * Usage:
 * - Ensure you have a valid URL Message Key from Dialog.
 * - Configure the necessary parameters (URL Message Key, recipient numbers, message, etc.)
 *   before invoking the sendMessage function.
 * 
 * Requirements:
 * - PHP 5.6 or higher.
 * - cURL must be enabled in your PHP installation.
 * 
 * @package DialogESMSIntegration
 * @version 1.0
 * @author Maleesha Udan Aththanayaka
 */

/**
 * Function for message sending
 */
function sendMessage($apiKey, $numberList, $message, $sourceAddress)
{
    $ch = curl_init();
    $list = implode(",", $numberList);
    $pushNotificationUrl = "https://xx/xx";
    $url = "https://e-sms.dialog.lk/api/v1/message-via-url/create/url-campaign?esmsqk={$apiKey}&list={$list}&source_address={$sourceAddress}&message=" . urlencode($message) . "&push_notification_url=" . urlencode($pushNotificationUrl);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $result = 'Error:' . curl_error($ch);
    } else {
        switch (trim($response)) {
            case "1":
                $result = "Success";
                break;
            case "2001":
                $result = "Error occurred during campaign creation";
                break;
            case "2002":
                $result = "Bad request";
                break;
            case "2003":
                $result = "Empty number list";
                break;
            case "2004":
                $result = "Empty message body";
                break;
            case "2005":
                $result = "Invalid number list format";
                break;
            case "2006":
                $result = "Not eligible to send messages via GET requests (Admin hasn’t provided the access level)";
                break;
            case "2007":
                $result = "Invalid key (esmsqk parameter is invalid)";
                break;
            case "2008":
                $result = "Not enough money in the user's wallet or not enough messages left in the package for the user. (When consuming package payments)";
                break;
            case "2009":
                $result = "No valid numbers found after the removal of mask blocked numbers.";
                break;
            case "2010":
                $result = "Not eligible to consume packaging";
                break;
            case "2011":
                $result = "Transactional error";
                break;
            default:
                $result = "Unknown response: " . $response;
                break;
        }
    }
    curl_close($ch);
    return $result;
}

/**
 * function for balance check
 */
function checkBalance($apiKey)
{
    $ch = curl_init();
    $url = "https://e-sms.dialog.lk/api/v1/message-via-url/check/balance?esmsqk={$apiKey}";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $result = 'Error:' . curl_error($ch);
    } else {
        list($status, $balance) = explode("|", $response, 2);
        switch (trim($status)) {
            case "1":
                $result = "Success - Balance: " . $balance;
                break;
            case "2001":
                $result = "Error occurred during campaign creation";
                break;
            case "2002":
                $result = "Bad request";
                break;
            case "2006":
                $result = "Not eligible to send messages via GET requests (Admin hasn’t provided the access level)";
                break;
            case "2007":
                $result = "Invalid key (esmsqk parameter is invalid)";
                break;
            default:
                $result = "Unknown response or error";
                break;
        }
    }
    curl_close($ch);
    return $result;
}
