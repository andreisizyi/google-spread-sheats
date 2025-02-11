<?php
if (isset($_POST["name"]) && isset($_POST["phonenumber"]) ) { 

	// Формируем массив для JSON ответа
	$result = 'Спасибо, мы скоро свяжемся с вами.';
    /*$result = array(
    	'name' => $_POST["name"],
    	'phonenumber' => $_POST["phonenumber"]
    );*/
}
    // Переводим массив в JSON
    echo json_encode($result); 

	require  'vendor/autoload.php';
	use Google\Spreadsheet\DefaultServiceRequest;
	use Google\Spreadsheet\ServiceRequestFactory;
	putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/my_secret2.json');
			/*  SEND TO GOOGLE SHEETS */
			 $client = new Google_Client;
				try{
					$client->useApplicationDefaultCredentials();
				  $client->setApplicationName("Something to do with my representatives");
					$client->setScopes(['https://www.googleapis.com/auth/drive','https://spreadsheets.google.com/feeds']);
				   if ($client->isAccessTokenExpired()) {
						$client->refreshTokenWithAssertion();
					}

					$accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
					ServiceRequestFactory::setInstance(
						new DefaultServiceRequest($accessToken)
					);
				   // Get our spreadsheet
					$spreadsheet = (new Google\Spreadsheet\SpreadsheetService)
						->getSpreadsheetFeed()
						->getByTitle('leads_from_kg');

					// Get the first worksheet (tab)
					$worksheets = $spreadsheet->getWorksheetFeed()->getEntries();
					$worksheet = $worksheets[0];


					$listFeed = $worksheet->getListFeed();
					$listFeed->insert([
						'name' => "'". $_POST["name"],
						'phone' => "'". $_POST["phonenumber"],
						'date' => date_create('now')->format('Y-m-d H:i:s')
					]);

				}catch(Exception $e){
				  echo $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile() . ' ' . $e->getCode;
				}
				
				/*  SEND TO GOOGLE SHEETS */