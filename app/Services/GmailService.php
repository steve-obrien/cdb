<?php

namespace App\Services;

use Google\Client;
use Google\Service\Gmail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class GmailService
{
	protected $client;

	public function __construct()
	{
		$this->client = new Client();
		$this->client->setAuthConfig(storage_path('app/credentials.json')); // Path to your credentials.json file
		$this->client->addScope(Gmail::GMAIL_READONLY);
		$this->client->setAccessType('offline');
		$this->client->setRedirectUri(route('gmail.callback')); // Your redirect URI
	}

	public function getAuthUrl()
	{
		return $this->client->createAuthUrl();
	}

	public function fetchAccessTokenWithAuthCode($code)
	{
		return $this->client->fetchAccessTokenWithAuthCode($code);
	}

	public function setAccessToken($token)
	{
		$this->client->setAccessToken($token);
	}

	public function isAccessTokenExpired()
	{
		return $this->client->isAccessTokenExpired();
	}

	public function refreshToken()
	{
		$refreshToken = $this->client->getRefreshToken();
		return $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
	}

	public function getEmails()
	{
		// Check if access token exists in the session
		if (!Session::has('access_token')) {
			return null;
		}

		// Set the access token
		$this->setAccessToken(Session::get('access_token'));

		// If the token is expired, refresh it
		if ($this->isAccessTokenExpired()) {
			$newToken = $this->refreshToken();
			Session::put('access_token', $newToken);
		}

		// Retrieve the emails
		$service = new Gmail($this->client);
		$messages = $service->users_messages->listUsersMessages('me', ['maxResults' => 10]);

		$latestEmails = [];
		foreach ($messages->getMessages() as $message) {
			$email = $service->users_messages->get('me', $message->getId());
			$latestEmails[] = $email;
		}

		return $latestEmails;
	}
}
