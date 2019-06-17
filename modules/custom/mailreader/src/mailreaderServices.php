<?php

namespace Drupal\mailreader;

use Drupal\Component\Utility\Html;
use Exception;
use Google_Client;
use Google_Service_Gmail;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\ClientInterface;

class mailreaderServices {
  public function getClient()
  {
    $client = new Google_Client();
    $client->setApplicationName('Gmail API PHP Quickstart');
    $client->setScopes(Google_Service_Gmail::GMAIL_READONLY);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
      $accessToken = json_decode(file_get_contents($tokenPath), true);
      $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
      // Refresh the token if possible, else fetch a new one.
      if ($client->getRefreshToken()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
      } else {
        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();
        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        $authCode = trim(fgets(STDIN));

        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
        $client->setAccessToken($accessToken);

        // Check to see if there was an error.
        if (array_key_exists('error', $accessToken)) {
          throw new Exception(join(', ', $accessToken));
        }
      }
      // Save the token to a file.
      if (!file_exists(dirname($tokenPath))) {
        mkdir(dirname($tokenPath), 0700, true);
      }
      file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
  }

//  public function getMessage($service, $userId, $messageId) {
//    try {
////      $message = $service->users_messages->get($userId, $messageId);
//      $messages = $service->users_threads->get($userId, $messageId)->getMessages();
////      kint($messages);
//        foreach ($messages as $message) {
//          $payload = $message->getPayload();
//          kint(Snippet);
//          $parts = $payload->getParts();
//          //print_r($parts); PRINTS SOMETHING ONLY IF I HAVE ATTACHMENTS...
//          $body = $parts[0]['body'];
//          $rawData = $body->data;
//          $sanitizedData = strtr($rawData, '-_', '+/');
////          $decodedMessage[] = base64_decode($sanitizedData);
//          $decodedMessage[] = preg_replace('#<head(.*?)>(.*?)</head>#is', '', base64_decode($sanitizedData));
////      print_r($decodedMessage);
////    print 'Message with ID: ' . $message->getId() . ' retrieved.';
//        }
////        kint($decodedMessage);
//        die();
//      return $decodedMessage;
//
//    } catch (Exception $e) {
//      print 'An error occurred: ' . $e->getMessage();
//    }
//  }

  public function getMessage($service, $userId, $messageId) {
    try {
      kint(hello);
//      $message = $service->users_messages->get($userId, $messageId);
      $messages = $service->users_threads->get($userId, $messageId)->getMessages();
      $label = $service->users_labels->get($userId, $messageId)->getLabelIds();

//      kint($messages);
//      foreach ($messages as $index => $message) {
        $payload = $messages->getPayload();
//        $parts = $payload->getParts();
        $parts = $label->getLabelIds();
        kint($label);
        kint($parts);
        die();
        //print_r($parts); PRINTS SOMETHING ONLY IF I HAVE ATTACHMENTS...
        $body = $parts[0.0]['body'];
        $rawData = $body->data;
        $sanitizedData = strtr($rawData, '-_', '+/');
//          $decodedMessage[] = base64_decode($sanitizedData);
        $decodedMessage[] = preg_replace('#<head(.*?)>(.*?)</head>#is', '', base64_decode($sanitizedData));
//      print_r($decodedMessage);
//    print 'Message with ID: ' . $message->getId() . ' retrieved.';
//      }
//        kint($decodedMessage);
//      die();
      return $decodedMessage;

    } catch (Exception $e) {
      print 'An error occurred: ' . $e->getMessage();
    }
  }
}

