<?php
  // Get host name and IP address
  $ethernetIP = $_SERVER['SERVER_ADDR'];
  $dexhost = $_SERVER['HTTP_HOST'];
  
  $canvas_course_number = '20350';
  $canvas_site = 'https://canvas.tufts.edu/courses/' . $canvas_course_number;
  $canvas_help = $canvas_site . '/pages/help-topics';
  $canvas_faq = $canvas_site . '/pages/frequently-asked-questions';
  
  $zoom_link = 'https://tufts.zoom.us/my/jenncross';

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
  	<a href="https://universitycollege.tufts.edu/high-school/programs/engineering-design-lab" target="_blank">
	  	<img src="img/EDL-gopigo-image.png" class="standard logo" alt="EDL Image">
  	</a>
	
  <h1><em>Raspbian for Robots for Tufts</em> : EDL Edition</h1>
  <h4>
      <p>Compatible with hardware and software developed separately by:<br /></p>
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
        </td
      </tr></table>
      <br />
      <font size=-2>
        LEGO(R), the LEGO(R) logo, the Brick, MINDSTORMS(R), SPIKE(TM) Prime, and the Minifigure are trademarks of (C)The LEGO(R) Group.<br />
        Dexter Industries, the Dexter Industries logo, GoPiGo, and the Dexter character are trademakrs of Dexter Industries.<br />
        Neither The LEGO(R) Group nor Dexter Industries authorize or endorse the development or use of this software.<br />
        All other trademarks and copyrights are the property of their respective owners. All rights reserved.<br />
      </font>
  </h4>
</header>

<article>
<section>
  <h1> Main Tools </h1>
  <p>
  We will be using Jupyter Notebooks to Program our GoPiGo in Python,
  Canvas to stay up to date on course info and meeting times, and
  Zoom to meet virtually as a class. 
  </p>
  <table width='100%'><tr>
    <td width='33%' align=center>
      <b><u>Jupyter Notebooks</u></b><br><br>
      <a href="http://<?php echo $dexhost; ?>:8888">
        <img src="img/jupyter_logo.png" onerror="this.src='jupyter_logo.png'; this.onerror=null;"style="height:128px;">
        <span class="button">Open Jupyter</span>
      </a>
      <em>
        Default Token: <strong>robots1234</strong>
      </em>
    </td>
    <td width='33%' align=center>
      <b><u>Course Homepage</u></b><br><br>
      <a href="<?php echo $canvas_site; ?>" target=_blank>
        <img src="img/Canvas_Logo.png" onerror="this.src='img/Canvas_Logo.png'; this.onerror=null;"style="height:128px;">
        <span class="button">Open Canvas</span>
      </a>
      <em>&nbsp;</em>
    </td>
    <td width='33%' align=center>
      <b><u>Zoom Room</u></b><br><br>
      <a href="<?php echo $zoom_link; ?>" target=_blank>
        <img src="img/zoom.png" onerror="this.src='img/zoom.png'; this.onerror=null;"style="height:128px;">
        <span class="button">Join Zoom</span>
      </a>
      <em>&nbsp;</em>
    </td>
  </tr>
  </table>
</section>
<section>
  <h1>Advanced Tools</h1>
  <p>
  Use these tools if you are asked to by a TA or an instructor,
  or if you are comfortable with Raspbian and the command line.
  </p>
  <table width='100%' border=0><tr>
    <td width='50%' align=center>
      <b><u>Virtual Desktop</u></b><br><br>
      <a href=" http://<?php echo $dexhost; ?>:8001/vnc.html?host=<?php echo $dexhost; ?>&port=8001&autoconnect=true&password=robots1234&scaleViewport=true">
        <img src="img/vnc.svg" onerror="this.src='img/vnc.png'; this.onerror=null;"style="height:128px;">
        <span class="button">Launch VNC</span>
      </a>
      <em>Password: <strong>robots1234</strong></em>
      <br/>
      &nbsp;
    </td>
    <td width='50%' align=center>
      <b><u>Command Line</u></b><br><br>
      <a href="http://<?php echo $dexhost; ?>:4200">
        <img src="img/bash.svg" onerror="this.src='img/bash.png'; this.onerror=null;"style="height:128px;">
        <span class="button">Launch Terminal</span>
      </a>
      <em>
        Username: <strong>pi</strong>
        <br/>
        Password: <strong>robots1234</strong>
      </em>
    </td>
  </tr>
  </table>
</section>
<section>
  <h1>Other Links</h1>
  <ul style='list-style-type:square; line-height:150%'>
  <li>Visit the <a href="<?php echo $canvas_faq; ?>" target=_blank>Canvas FAQ section</a> to view frequently asked questions.</li>
  <li>Visit the <a href="<?php echo $canvas_help; ?>" target=_blank>Canvas help section</a> to view help topics about GoPiGo and Jupyter.</li>
  <li><a href="https://gopigo3.readthedocs.io/en/master/api-basic/easygopigo3.html" target=_blank>
  Documentation for the EasyGoPiGo3 Library: </a> Functions and Methods for your GoPiGo.</li>
  <li><a href="https://docs.python.org/3/" target=_blank>Python 3 Documentation:</a> Documentation for the Python 3 language.</li>
  <li><a href='./help/terminal.html' target=_blank>Terminal Help / Instructions</a></li>
  <li>See more about the <a href="http://www.dexterindustries.com/gopigo-tutorials-documentation/?raspbian_for_robots" target="_blank" class="product gopigo">GoPiGo.</a></li>
  </ul>
  </section>

<section class="IP">
<h3> IP Info: </h3>
    <ul>
        <li>The robot hostname is: <?php echo $dexhost; ?> </li>
        <li>The robot IP address is: <?php echo $ethernetIP; ?> </li>
  </ul>
</section>

<footer>
<h3>Version Info:</h3>
<blockquote><?php echo str_replace("\n", "<br>", file_get_contents('/home/pi/Dexter/Version')); ?></blockquote>
</footer>
</article>
</div> <!-- #main -->
</div> <!-- #main-container -->

</body>
</html>
