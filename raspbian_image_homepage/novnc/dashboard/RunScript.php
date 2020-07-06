<html><body>
<!-- BEGINNING OF PAGE -->

<?php
	///// UNCOMMENT NEXT TWO LINES FOR DEBUGGING
	//error_reporting(-1);
	//ini_set('display_errors', 'On');

	include './PhpSerial.php'; // code for doing serial connections

	$host = 'RPi'; // vs. 'MacOS'
	$streamtimeout = 1000;
	$this_page = "RunScript.php";
	$device_name = "SPIKE Prime"; // for display purposes
	$error = "";

	print("<p>Loading devices...</p>");

	// get available devices
	$root_dir = "/dev/";
	$device_prefixes = ["cu.usb", "ttyACM", "COM"]; // list of prefixes to match
	$device_list_all = scandir($root_dir);
	$device_list = [];
	$num_devices = 0;
	// go through each device in directory
	print("<p><b>Devices:</b></p><ul>");
	foreach ($device_list_all as $device_value) {
		// go through each prefix
		foreach ($device_prefixes as $prefix_value) {
			// match the beginning of the $device_value to the $prefix_value
			if (substr($device_value, 0, strlen($prefix_value)) == $prefix_value) {
				// if they match (beginning of device name matches prefix value)
				// then add this device to the overall device list
				$device_list[] = $root_dir . $device_value;
				print("<b>Device #" . $num_devices . ":</b> " . $root_dir . $device_value . "</ul>");
				$num_devices += 1;
			}
		}
	}
	print("</ul><p><b>End devices.</b> (Found " . $num_devices . ")</p>");
	
	$default_script = "import hub\nhub.display.show('B')\n";

	// variables in the session variable:
	$device = $_POST['device']; // device (the device that is currently connected)
	$serial = $_POST['serial']; // serial (the serial connection)
	$connected = $_POST['connected']; // connected (boolean indicating if connected to device)
	$script = $_POST['script'];

	print("<p><textarea rows=6 cols=80>" . $script . "</textarea></p>");

	// PROCESS POST COMMANDS:
	$action = $_POST["action"];
	$repl = "";

	//////////////////////////////////////////////
	/////// SERIAL COMMUNICATION FUNCTIONS ///////
	//////////////////////////////////////////////
	function serial_connect() {
		global $host, $device, $serial;
		try {
			if ($host == 'RPi') {
				exec("mode " . $device . " BAUD=115200 PARITY=N data=8 stop=1 xon=off");
				$serial = fopen($device, 'w+');
			} else {
				$serial = new PhpSerial();
				$serial->deviceSet($device);
				$serial->confBaudRate(115200);
				$serial->confParity("none");
				$serial->confCharacterLength(8);
				$serial->confStopBits(1);
				$serial->confFlowControl("none");
				// READY TO OPEN IT
				$serial->deviceOpen();
			}	
		} catch (Exception $e) {
			echo "connect error: " . $e->getMessage() . "<br />\n";
		}
	}
	function serial_disconnect() {
		global $host, $serial;
		try {
			if ($host == 'RPi') {
				fclose($serial);
			} else {
				$serial->serialflush();
				$serial->deviceClose();
			}
		} catch (Exception $e) {
			$error = "error closing connection (" . $e->getMessage() . ")";
		}
	}
	// serial send function
	function serial_send($send_text) {
		global $host, $serial;
		if ($host == 'RPi') { fwrite($serial, $send_text); }
		else { $serial->sendMessage($send_text); }
	}
	// serial read function
	function serial_read() {
		global $host, $serial, $streamtimeout;
		$return_text = "";

		if ($host == 'RPi') {
			$reply = "";
			$doneReading = false;
			while (!$doneReading) {
				//stream_set_timeout($serial, 2); // set timeout on serial
				
				// NOT WORKING!!! $stream_text = stream_get_contents($serial, 1);
				$stream_text = false; // put this in here to bypass reading
				
				// if there is text (stream_get_contents didn't return false)
				if (!($stream_text === false)) {
					$reply .= $stream_text;
					$doneReading = (intval(strpos(" " . $reply, ">>>")) > 0);
				} else { $doneReading = true; }
			}
			$return_text .= $reply;
		} else {
			$return_text .= $serial->readPort();
			$serial->serialflush();
		}
		return $return_text;
	}

	//////////////////////////////////////////
	////// PROCESS FORM ACTION COMMANDS //////
	//////////////////////////////////////////


	if ($action == "run") {
		print("<p><b>Running on " . $device . ":</b></p>");
		
		serial_connect();
		// once connected:
		// - do a read (to flush buffer)
		// - send "control-c" to enter REPL mode
		// - (debugging: change the screen so know it's working!)
		// - do another read (to update the REPL)
		$repl .= serial_read();

		serial_send("\x03" . "\r\n");
		serial_send("import hub\r\n"); // test
		serial_send("hub.display.show(hub.Image.YES)\r\n"); // test
		$repl .= serial_read();

		serial_disconnect();

		
		// SUBMIT SCRIPT TO DEVICE
		serial_connect();
		//$script = $_POST['script'];

		// split the script into multiple lines:
		$cmd_array = preg_split("/\r\n|\n|\r/", $script); // split on new line (regex for all types of new lines)
		foreach ($cmd_array as $cmd) {
			// send each command
			print("Serial send: " . trim($cmd) . "<br />");
			serial_send(trim($cmd) . "\r\n");
		}
		
		$repl .= serial_read();
		
		serial_disconnect();
		
	} else {
		print("<p><b>Action:</b> No action sent.</p>");
	}

?>

<!-- END OF PAGE -->
</body></html>
