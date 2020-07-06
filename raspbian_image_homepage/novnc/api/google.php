<?php

	include './PhpSerial.php'; // code for doing serial connections
	$host = 'RPi'; // vs. 'MacOS'
	$streamtimeout = 1000;
	$this_page = "google.php";
	$device_name = "SPIKE Prime"; // for display purposes
	$error = "";

	// get available devices
	$root_dir = "/dev/";
	$device_prefixes = ["cu.usb", "ttyACM", "COM"]; // list of prefixes to match
	$device_list_all = scandir($root_dir);
	$device_list = [];
	// go through each device in directory
	foreach ($device_list_all as $device_value) {
		// go through each prefix
		foreach ($device_prefixes as $prefix_value) {
			// match the beginning of the $device_value to the $prefix_value
			if (substr($device_value, 0, strlen($prefix_value)) == $prefix_value) {
				// if they match (beginning of device name matches prefix value)
				// then add this device to the overall device list
				$device_list[] = $root_dir . $device_value;
			}
		}
	}

	session_start();
	// variables in the session variable:
	$device = $_SESSION['device']; // device (the device that is currently connected)
	$serial = $_SESSION['serial']; // serial (the serial connection)
	$connected = $_SESSION['connected']; // connected (boolean indicating if connected to device)


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
			echo "error: " . $e->getMessage() . "<br />\n";
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

	
	
	$inputJSON = file_get_contents('php://input');
	$input = json_decode($inputJSON, TRUE);
	print('Payload: ' . $inputJSON . "<br />\n");

	$command = $_REQUEST['command'];
	$num_ports = sizeof($_REQUEST['port']);
	$runtime = $_REQUEST['time'];
	$runspeed = $_REQUEST['speed'];

	$command = $input['command'];
	$num_ports = sizeof($input['port']);
	$runtime = $input['time'];
	$runspeed = $input['speed'];
	
	print('port (#' . $num_ports . '):' . "<br />\n");
	$ports = Array();
	for ($i = 0; $i < $num_ports; $i++) {
		$port_name = $input['port'][$i];
		print('- ' . $port_name . "<br />\n");
		$ports[] = $port_name;
	}

	if ($command == 4) {
		print('command: ' . $command . "<br />\n");	

		$device = $device_list[0];
		print('device: ' . $device . "<br />\n");

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
		
		if ($command == 4) {
			for ($i = 0; $i < count($ports); $i++) {
				$current_port = substr($ports[$i], -1, 1);
				print('Running for ' . $runtime . ' ms at speed ' . $runspeed . ' on port ' . $current_port . "<br />\n");
				serial_send("hub.port." . $current_port . ".motor.run_for_time(" . $runtime . "," . $runspeed . ")\r\n");
			}
		}

		$repl .= serial_read();

		serial_disconnect();
		
		print("\n\n");
		print('OK ' . $command);
		
	} else {
		print('data coming in:' . "<br />\n");
		print('command: ' . $command . "<br />\n");	


		print('time: ' . $runtime . "<br />\n");	
		print('speed: ' . $speed . "<br />\n");	
		print('position: ' . $_REQUEST['position'] . "<br />\n");	
		print('typeId: ' . $_REQUEST['typeId'] . "<br />\n");	
		print('modeId: ' . $_REQUEST['modeId'] . "<br />\n");	
		print('stopmode: ' . $_REQUEST['stopmode'] . "<br />\n");	
	}
	
?>
