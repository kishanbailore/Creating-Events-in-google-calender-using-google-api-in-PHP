<?php    
    require_once 'Google/autoload.php';
    session_start(); 

    // ********************************************************  //
    // Get these values from https://console.developers.google.com
    // Be sure to enable the Analytics API
    // ********************************************************    //
    $client_id = 'YOUR_API_CLIENT_ID';
    $client_secret = 'YOUR_CLIENT_SECTRET';
    $redirect_uri = 'http://localhost/google_calendar/';

    $client = new Google_Client();
    $client->setApplicationName("Add Event to Calender");
    $client->setClientId($client_id);
    $client->setClientSecret($client_secret);
    $client->setRedirectUri($redirect_uri);
   // $client->setAccessType('offline');   // Gets us our refreshtoken

    $client->setScopes(array('https://www.googleapis.com/auth/calendar'));


   


    // Step 2: The user accepted your access now you need to exchange it.
    if (isset($_GET['code'])) {
	
	$client->authenticate($_GET['code']);  
	$_SESSION['token'] = $client->getAccessToken();
	$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
    }

    // Step 1:  The user has not authenticated we give them a link to login    
    if (!isset($_SESSION['token'])) {

	$authUrl = $client->createAuthUrl();

	print "<a class='login' href='$authUrl'>Connect Me!</a>";
    }    
	// Below code will add events to google calender.
	if (isset($_SESSION['token']))
	{
		$client->setAccessToken($_SESSION['token']);
		//unset($_SESSION['token']);
		$client->setAccessToken($_SESSION['token']);
		print "<a class='logout' href=''>LogOut</a><br>";
		
		
		$service = new Google_Service_Calendar($client);
	
		$event = new Google_Service_Calendar_Event(
		array(
				'summary' => 'booked ticket on book my trip',
				'location' => 'dubai',
				'description' => 'going on vacation.',
				'start' => array(
						'dateTime' => '2015-11-04T09:00:00-07:00',
						'timeZone' => 'Asia/Kolkata',
					),
				'end' => array(
						'dateTime' => '2015-11-04T17:00:00-07:00',
						'timeZone' => 'Asia/Kolkata',
					),
				'recurrence' => array(
						'RRULE:FREQ=DAILY;COUNT=2'
					),
				
				'reminders' => array(
						'useDefault' => FALSE,
						'overrides' => array(
							array('method' => 'email', 'minutes' => 24 * 60),
							array('method' => 'popup', 'minutes' => 10),
						),
					),
			)
			);

			$calendarId = 'primary';
			$event = $service->events->insert($calendarId, $event);
		printf('Event created: %s\n', $event->htmlLink);
				
	}
	// Below Code will display all the list of your google calender events.
	/* if (isset($_SESSION['token'])) {
	$client->setAccessToken($_SESSION['token']);
	print "<a class='logout' href='http://www.daimto.com/Tutorials/PHP/GCOAuth.php?logout=1'>LogOut</a><br>";	
	
	$service = new Google_Service_Calendar($client);    
	
	$calendarList  = $service->calendarList->listCalendarList();;

	
	
	while(true) {
		foreach ($calendarList->getItems() as $calendarListEntry) {

			echo $calendarListEntry->getSummary()."<br>\n";


			// get events 
			$events = $service->events->listEvents($calendarListEntry->id);
				foreach ($events->getItems() as $event) {
			    echo "-----".$event->getSummary()."<br>";
			}
		}
		$pageToken = $calendarList->getNextPageToken();
		if ($pageToken) {
			$optParams = array('pageToken' => $pageToken);
			$calendarList = $service->calendarList->listCalendarList($optParams);
		} else {
			break;
		}
	}
    }*/
?>
