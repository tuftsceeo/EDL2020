<?php
	//error_reporting(-1);
	//ini_set('display_errors', 'On');

	include './PhpSerial.php'; // code for doing serial connections
	include './CodeExamples.php'; // examples of code
	$host = 'RPi'; // vs. 'MacOS'
	$streamtimeout = 1000;
	$this_page = "WebREPL.php";
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
	
	$default_repl = "starting...\n";
	$default_script = "import hub\nhub.display.show('A')\n";

	session_start();
	// variables in the session variable:
	$device = $_SESSION['device']; // device (the device that is currently connected)
	$serial = $_SESSION['serial']; // serial (the serial connection)
	$connected = $_SESSION['connected']; // connected (boolean indicating if connected to device)
	$current_examples = $_SESSION['current_examples']; // current_examples (what help page is being displayed)
	$script = $_SESSION['script'];
	if ($script == '') { $script = $default_script; }
	$repl = $_SESSION['repl'];
	if ($repl == '') { $repl = $default_repl; }

	// what page type to print
	// options:
	// - init
	// - connected
	$page_type = "init"; // default is "init"; overwritten if needed
	if ($connected) { $page_type = "connected"; }

	// PROCESS POST COMMANDS:
	$action = $_POST["action"];

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

	//////////////////////////////////////////
	////// PROCESS FORM ACTION COMMANDS //////
	//////////////////////////////////////////
	if ($action == "disconnect") {
		// DISCONNECT FROM DEVICE
		serial_connect();
		serial_send("import hub\r\n"); // test
		serial_send("hub.display.show(hub.Image.NO)\r\n"); // test
		serial_disconnect();
		$connected = false;
		$device = ""; // no device connected

		// page to show
		$page_type = "init"; // reset page back to original

		// update session:
		$_SESSION['serial'] = $serial;
		$_SESSION['connected'] = $connected;
		$_SESSION['device'] = $device;
	} elseif ($action == "connect") {

		// CONNECT TO THE DEVICE
		$device = $_POST['device']; // read in device selected
		
		// just connecting, so use defaults here:
		$script = $default_script;
		$repl = $default_repl;

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

		$_SESSION['serial'] = $serial; // save the serial connection
		$connected = true; // got this far, must be connected

		// page to show
		$page_type = "connected";

		// update session
		$_SESSION['connected'] = $connected;
		$_SESSION['device'] = $device;
		$_SESSION['script'] = $script;
		$_SESSION['repl'] = $repl;
		
	} elseif ($action == "select_page") {

		// everything stays the same
		// just update which page is selected
		$current_examples = $_POST['current_examples'];
		$_SESSION['current_examples'] = $current_examples;

		// page to show
		$page_type = "connected";

	} elseif ($action == "script") {

		// SUBMIT SCRIPT TO DEVICE
		serial_connect();
		$script = $_POST['script'];

		// split the script into multiple lines:
		$cmd_array = preg_split("/\r\n|\n|\r/", $_POST['script']); // split on new line (regex for all types of new lines)
		foreach ($cmd_array as $cmd) {
			// send each command
			serial_send(trim($cmd) . "\r\n");
		}
		
		$repl .= serial_read();
		
		serial_disconnect();
		
		// page to show
		$page_type = "connected";
		// update session
		$_SESSION['script'] = $script;
		$_SESSION['repl'] = $repl;

	} elseif ($action == "testcode") {

		serial_connect();
		
		// split the script into multiple lines:
		$cmd_array = preg_split("/\r\n|\n|\r/", $_POST['code']); // split on new line (regex for all types of new lines)
		foreach ($cmd_array as $cmd) {
			// send each command
			serial_send(trim($cmd) . "\r\n");
		}
		$repl .= serial_read();
		
		serial_disconnect();
		
		// page to show
		$page_type = "connected";
		// update session
		$_SESSION['repl'] = $repl;

	} elseif ($action == "repl") {

		serial_connect();
		// UPDATE THE REPL
		$repl .= serial_read();
		serial_disconnect();
		// page to show
		$page_type = "connected";
		// update session
		$_SESSION['repl'] = $repl;

	} elseif ($action == "ctrl-c") {

		// SEND A CONTROL-C
		serial_connect();
		// $repl .= serial_read(); // flush
		serial_send("\x03" . "\r\n");
		$repl .= serial_read();
		serial_disconnect();
		// page to show
		$page_type = "connected";

		// update session
		$_SESSION['repl'] = $repl;

	} elseif ($action == "ctrl-d") {

		// SEND A CONTROL-D
		serial_connect();
		// $repl .= serial_read(); // flush
		serial_send("\x04" . "\r\n");
		// $repl .= serial_read();
		serial_disconnect();
		// page to show
		$page_type = "connected";

		// update session
		$_SESSION['repl'] = $repl;

	} elseif ($action == "repl_clear") {

		serial_connect();
		// CLEAR THE REPL
		$repl = ">>> ";
		$repl .= serial_read();
		serial_disconnect();
		// page to show
		$page_type = "connected";
		// update session
		$_SESSION['repl'] = $repl;

	}

?>


<?php

	if ($page_type == "init") {
		$processor_options = "";
		// build out processor options
		foreach ($device_list as $device_next) {
			$processor_options .= "<option value='" . $device_next . "' ";
			if ($device_next == $device) { $processor_options .= "selected"; }
			$processor_options .= ">" . $device_next . "</option>\n";
		}

?>	
	<!-- PAGE TYPE: init -->
    <html>
    <body style="width:1040px; margin: 20px auto;">
	    <h1 align=center><a href='<?=$this_page?>'><?php echo $device_name; ?> Terminal Window</a></h1>
	    <p align=center>This is an experimental <?=$device_name?> interactive window. Currently under
			development.<br />If you would like to contribute to the development, please contact Ethan Danahy
			at <a href="mailto:ethan.danahy@tufts.edu">ethan.danahy@tufts.edu</a>.
	    </p>
	    <aside bgcolor="#FFFFFF" style="float:left; width:500px;">
			<h3>Welcome to <?php echo $device_name; ?></h3>
			<p>
				<em>Some Hints:</em><br />
				First, select the port of your hub and hit the submit button.<br />
				If it does not connect, make sure device is on and plugged in to computer.
			</p>
		</aside>
	    <aside bgcolor="#FFFFFF" style="float:right; width:500px;">
			<p>Select port/device and press Connect to connect to <?php echo $device_name; ?></p>
			<form action="<?=$this_page?>" method="POST">
				<input type="hidden" name="action" value="connect">
				<select name="device">
					<?php echo $processor_options; ?>
				</select>
				<input type="submit" name="Connect" value="Connect">
			</form>
			<?php
				// if error message
				if (strlen($error) > 0) {
					echo "<p style='color: red'>" . $error . "</p>";
				}
			?>
	    </aside>
    </body>
    </html>
		
<?php		
	} // end if $page_type == "init"
	
	if ($page_type == "connected") {
		$script_area = $script;
		$REPL_area = $repl;

?>
	<html>
	<body style="width:1040px; margin: 20px auto;">
		<h1 align=center><a href='<?=$this_page?>'><?php echo $device_name; ?> Terminal Window</a></h1>
	    <p align=center>This is an experimental <?=$device_name?> interactive window. Currently under
			development.<br />If you would like to contribute to the development, please contact Ethan Danahy
			at <a href="mailto:ethan.danahy@tufts.edu">ethan.danahy@tufts.edu</a>.
	    </p>
		<aside bgcolor="#FFFFFF" style="float:left; width:500px;">
			<h3>Welcome to <?php echo $device_name; ?></h3>
			<p align=center>You are connected to <?=$device_name?> on <em><?=$device?></em></p>
			<?php
				// if error message
				if (strlen($error) > 0) {
					echo "<p style='color: red' align=center>" . $error . "</p>";
				}
			?>
			<form action="<?=$this_page?>" method="POST" align=center>
				<input type="hidden" name="action" value="disconnect">
			    <input type="submit" name="Close" value="Close Connection">
			</form>
			<p>
				<em>Some Hints:</em><br />
				Click on these buttons and see what they do on the Hub. You can also try typing your
				own scripts and hit the Send Script button.
			</p>
			<form action="<?=$this_page?>" method="POST">
				<p>Select Page:
					<input type="hidden" name="action" value="select_page">
					<select name="current_examples">
						<?php
							foreach ($code_examples as $key => $example) {
								echo "<option value='" . $key . "' ";
								if ($current_examples == $key) { echo "selected"; }
								echo ">" . $example['section_title'] . "</option>\n";
							}
						?>
					</select>
					<input type="submit" name="page" value="View Material">
				</p>
			</form>
			
			<!-- START OF HELP MATERIALS -->
			<?php

			
				// CUSTOMIZE THE PAGE BASED ON EXAMPLES
				$found_page = false;
				// go through each code example section:
				foreach ($code_examples as $key => $example_group) {
					// if matches the current examples requested:					
					if ($current_examples == $key) {
						$found_page = true;
						// print material:
						echo "<p><b>" . $example_group['section_title'] . "</b></p>\n";
						foreach ($example_group['examples'] as $example) {
						?>
							<p><?=$example['text']?></p>
							<form action="<?=$this_page?>" method="POST">
								<input type="hidden" name="action" value="testcode">
								<textarea rows="<?=substr_count(trim($example['code']),"\n") + 1?>" cols="60" name="code" style="font-family:Courier; background-color:#F5F5F5"><?=$example['code']?></textarea>
								<input type="submit" name="submit" value="Try it!">
							</form>
						<?php
						}
					} // end of example display
				}
				
				if (!$found_page) {
					// no page found...
					?>
					<p><b>Please select a page above.</b></p>
					<?php
				} // matches the "not found page" associated with printing "Getting Started" materials
			?>
		<!-- END OF HELP MATERIALS -->
		</aside>
	    <aside bgcolor="#FFFFFF" style="float:right; width:500px;">
			<h3>Script</h3>
			<form action="<?=$this_page?>" method="POST">
				<input type="hidden" name="action" value="script">
				<textarea rows="10" cols="60" name="script" style="font-family:Courier; background-color:#F5F5F5"><?=$script_area?></textarea>
				<input type="submit" name="SendCommand" value="Send Script">
			</form>
			<h3>REPL <span style="color:red">this part isn't working :(</span></h3>

			<p><textarea rows="21" cols="60" name="TerminalWindow" style="font-family:Courier; background-color:#F5F5F5"><?=$REPL_area?></textarea></p>
			<table width=100%>
				<tr><td align=center>
					<form action="<?=$this_page?>" method="POST"><input type="hidden" name="action" value="repl"><input type="submit" name="SendCommand" value="Update REPL"></form>
				</td><td align=center>
					<form action="<?=$this_page?>" method="POST"><input type="hidden" name="action" value="ctrl-c"><input type="submit" name="SendCommand" value="Send Control-C"></form>
				</td><td align=center>
					<form action="<?=$this_page?>" method="POST"><input type="hidden" name="action" value="ctrl-d"><input type="submit" name="SendCommand" value="Send Control-D"></form>
				</td><td align=center>
					<form action="<?=$this_page?>" method="POST"><input type="hidden" name="action" value="repl_clear"><input type="submit" name="Clear" value="Clear REPL"></form>
				</td></tr>
			</table>

	    </aside> 
    </body>
    </html>
<?php
	} // end if $page_type == "connected"
?>
