<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>JavaScript Automatic Page Redirect</title>
<script>
    function pageRedirect() {
        window.location.pathname = '/index.php';
    }      
    setTimeout("pageRedirect()", 3000);
</script>
</head>
<body>
    <p><strong>Note:</strong> You will be redirected to the homepage in 3 sec.</p>
</body>
</html>