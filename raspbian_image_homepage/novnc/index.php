<?php
  // Get host name and IP address
  $ethernetIP = $_SERVER['SERVER_ADDR'];
  $dexhost = $_SERVER['HTTP_HOST'];
  
  $canvas_course_number = '17997';
  $canvas_site = 'https://canvas.tufts.edu/courses/' . $canvas_course_number;
?>
<html>
<head>
    <title>Raspbian for Robots for Tufts</title>
    <link rel="stylesheet" href="css/main.css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
</head>

<body>
  <div class="main-container">
            <div class="main wrapper clearfix">
  <header>
  	<a href="https://engineering.tufts.edu/" target="_blank">
	  	<img src="img/Tufts_Engineering_Logo.jpg" class="retina logo" alt="Tufts Engineering!">
	  	<img src="img/Tufts_Engineering_Logo.jpg" class="standard logo" alt="Tufts Engineering!">
  	</a>
	
  <h1><em>Raspbian for Robots for Tufts</em></h1>
  <h4>
      <p>Compatible with hardware and software developed separately by:<br /><br /></p>
      <table width='100%'><tr>
        <td width='50%' align=center><a href="http://www.dexterindustries.com/" target="_blank">
            <img src="img/dexter-logo-sm.png" class="standard logo" alt="Dexter Industries!" >
            <img src="img/dexter-logo-retina.png" class="retina logo" alt="Dexter Industries!" >
            </a>
        </td>
        <td width='50%' align=center><a href="http://www.legoeducation.com/" target="_blank">
            <img src="img/LEGO_Education_logo.png" class="standard logo" alt="Dexter Industries!" >
            <img src="img/LEGO_Education_logo.png" class="retina logo" alt="Dexter Industries!" >
            </a>
        </td>
      </tr></table>
      <br /><br />
      <font size=-2>
        LEGO(R), the LEGO(R) logo, the Brick, MINDSTORMS(R), SPIKE(TM) Prime, and the Minifigure are trademarks of (C)The LEGO(R) Group.<br />
        Dexter Industries, the Dexter Industries logo, GoPiGo, and the Dexter character are trademakrs of Dexter Industries.<br />
        Neither The LEGO(R) Group nor Dexter Industries authorize or endorse the development or use of this software.<br />
        All other trademarks and copyrights are the property of their respective owners. All rights reserved.<br />
      </font>
  </h4>
  <h3>This Raspberry Pi belongs to:<br /><?php echo file_get_contents('/home/pi/Desktop/Name.txt'); ?></h3>

</header>

<article>
<section>
  <p>
    Welcome to <b>Raspbian for Robots for Tufts</b>!
    We will be using <b><a href="http://<?php echo $dexhost; ?>:8888">Jupyter Notebooks</a></b> for
    course content, programming, and assignment documentation! You can setup your own password
    for Jupyter Notebooks (to protect your work from classmates).
  	Projects, resources, etc will be posted and can be downloaded
    through <a href="<?php echo $canvas_site; ?>" target=_blank>Canvas</a> (Tufts Learning Management System).
  </p>
  <p>
    <em>Other options are:</em>
    <ul>
	  <li>Use Jupyter Notebooks for writing Python Code, controlling your Raspberry Pi and associated robotics hardware, and completing/documenting assignments.</li>
      <li>VNC (virtual network connections), which will show you an interactive desktop in your browser with icons and folders. Use this, for example, to change your name above!</li>
      <li>If you are more advanced and want to work in the command line, choose Terminal below for browser-based terminal.</li>
      <li>Or use a local program to directly SSH into your Raspberry Pi (<code>ssh pi@<?php echo $ethernetIP; ?></code>).</li>
    </ul>
  </p>
</section>
<section>
  <h1>Tools</h1>
</section>
<table width=100% border=0><tr>
  <td>
<section class="jupyter">
  <a href="http://<?php echo $dexhost; ?>:8888">
    <img src="img/jupyter_logo.png" onerror="this.src='jupyter_logo.png'; this.onerror=null;"style="height:128px;">
    <span class="button">Open Jupyter</span>
  </a>
    <em>
      Default Token: <strong>robots1234</strong>
  </em>
</section>
  </td>
  <td>
<section class="canvas">
  <a href="<?php echo $canvas_site; ?>" target=_blank>
    <img src="img/Canvas_Logo.png" onerror="this.src='img/Canvas_Logo.png'; this.onerror=null;"style="height:128px;">
    <span class="button">Open Canvas</span>
  </a>
  <em>&nbsp;</em>
</section>
  </td>
</tr><tr>
  <td>
<section class="vnc">
  <a href=" http://<?php echo $dexhost; ?>:8001/vnc.html?host=<?php echo $dexhost; ?>&port=8001&autoconnect=true&password=robots1234&scaleViewport=true">
    <img src="img/vnc.svg" onerror="this.src='img/vnc.png'; this.onerror=null;"style="height:128px;">
    <span class="button">Launch VNC</span>
  </a>
    <em>Password: <strong>robots1234</strong></em>
    <br/>
    &nbsp;
</section>
  </td>
  <td>
<section class="bash">
  <a href="http://<?php echo $dexhost; ?>:4200">
    <img src="img/bash.svg" onerror="this.src='img/bash.png'; this.onerror=null;"style="height:128px;">
    <span class="button">Launch Terminal</span>
  </a>
    <em>
      Username: <strong>pi</strong>
      <br/>
      Password: <strong>robots1234</strong>
  </em>
</section>
  </td>
</tr>
</table>
<section>
  <h1>Other Links</h1>
  <p><b><a href="http://<?php echo $dexhost; ?>:4200">Terminal:</a></b> for doing command
    line Python (e.g. Raspberry Pi) and MicroPython (e.g. SPIKE Prime REPL) coding.
    <a href='./help/terminal.html' target=_blank>More help here.</a>
    <ul><li>Username: <em>pi</em>, Password: <em>robots1234</em></li></ul>
  </p>
  <p><b><a href="http://<?php echo $dexhost; ?>:8888">Jupyter Notebooks:</a></b> Python test scripts for ensuring
    hardware is working.
    <ul><li>Username: <em>pi</em>, Password: <em>robots1234</em></li></ul>
  </p>
  <p><b><a href="https://docs.python.org/3/" target=_blank>Python 3 Documentation:</a></b> Documentation for the Python 3 language.</p>
  <p><b><a href="https://docs.micropython.org/en/latest/index.html" target=_blank>MicroPython Documentation:</a></b> Documentation for the MicroPython language.</p>
  <p><b><a href="./help/SPIKE_Prime_MicroPython_Commands.html" target=_blank>SPIKE Prime MicroPython Documentation:</a></b> Documentation for the the SPIKE Prime MicroPython language.</p>
  <p><b><a href="./dashboard/index.htm" target=_blank>Dashboard Controllers:</a></b> Web-interfaces for controlling robot hardware.</p>
  <p><b><a href="./IDE/index.php" target=_blank>Web-based Programming IDE:</a></b> Web-interface for programming Blockly and Python.</p>
</section>
<br><br>
<section class="IP">
    <ul>
        <li>The robot hostname is: <?php echo $dexhost; ?> </li>
        <li>The robot IP address is: <?php echo $ethernetIP; ?> </li>
  </ul>
</section>

<footer>
<h3>More help from Tufts?</h3>

	<ul>
		<li>Visit the <a href="<?php echo $canvas_site; ?>" target=_blank>Canvas class site</a> to view help topics, contact Teaching Assistants, or email the instructor.</li>
  </ul>

<h3>More help from Dexter Industries?</h3>

	<ul>
		<li>See more about the <a href="http://www.dexterindustries.com/gopigo-tutorials-documentation/?raspbian_for_robots" target="_blank" class="product gopigo">GoPiGo.</a></li>
    <li>Ask a question on our <a href="http://www.dexterindustries.com/forum?raspbian_for_robots" target="_blank" class="product">forums.</a></li>
	</ul>

<h3><em>Raspbian for Robots for Tufts</em> Version Info</h3>
<blockquote><?php echo str_replace("\n", "<br>", file_get_contents('/home/pi/Dexter/Version')); ?></blockquote>
</footer>
</article>
</div> <!-- #main -->
</div> <!-- #main-container -->

</body>
</html>
