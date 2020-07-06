<?php
	$code_examples = [
		"getting_started" => [
			"section_title" => "Getting Started",
			"examples" => [
				["text" => "Most common Python commands can be executed, like simple math, that is executed and evaluated in the REPL.",
				"code" => "2+2"],
				["text" => "You can print things to the REPL.",
				"code" => "print('Hello World')"],
				["text" => "You can import time and use the time.sleep function for waiting.",
				"code" => "import time\nprint('Starting...')\ntime.sleep(1)print('Ending...')"]
			]
		],
		"display" => [
			"section_title" => "Display Functions",
			"examples" => [
				["text" => "After importing hub, you can display text on the SPIKE Prime screen.",
				"code" => "import hub\nhub.display.show('B')"],
				["text" => "If you want to scroll longer text, use fade=4.  (Note that import hub from above needs to have already been imported for this to work!)",
				"code" => "hub.display.show('Hello World',fade=4)"],
				["text" => "You can put the display.show function into a loop:",
				"code" => "import hub\nimport time\nfor i in range(4):\n   hub.display.show(str(i))\n   time.sleep(1)\n\n\n"]
			]
		],
		"sensors" => [
			"section_title" => "Using Sensors",
			"examples" => [
				["text" => "You can read the sensors, e.g. a force sensor on Port D.",
				"code" => "import hub\nhub.port.D.device.get()"],
				["text" => "This code will display, for 10 seconds, the value of the force sensor on the screen.",
				"code" => "import hub\nimport time\nfor i in range(100):\n   val = hub.port.D.device.get()\n   val = str(val)[1:2] # format\n   hub.display.show(val)\n   time.sleep(0.1)\n\n\n"]
			]
		],
		"motors" => [
			"section_title" => "Using Motors",
			"examples" => [
				["text" => "You can move the motors.  This turns on a motor attached to Port A.",
				"code" => "import hub\nimport time\nhub.port.A.motor.pwm(100)\ntime.sleep(1)\nhub.port.A.motor.brake()\n\n\n"]
			]
		],
		"sound" => [
			"section_title" => "Making Sounds",
			"examples" => [
				["text" => "Make a beep!",
				"code" => "import hub\nhub.sound.beep()"]
			]
		]

	];
?>
