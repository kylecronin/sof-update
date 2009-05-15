<html>
<head><title><?=$sitename?> Reputation Tracker</title></head>
<body>
    <h3><?=$user;?>: Invalid <?=$sitename?> user ID</h3>
    <p><?php
            if (preg_match('/^\d+$/', $user))
                echo "Note: StackOverflow and ServerFault do not use the same user IDs. Please check to make sure you're using your ID from the correct site.";
            else
                echo "Your $sitename ID is the number in the URL to your user profile. As an example, the link to my profile page on StackOverflow is <pre>http://stackoverflow.com/users/658/nobody</pre> which means that my user ID is <b>658</b>. Please take note that StackOverflow and ServerFault do not share the same user ids - you will have a unique one for each site. "
?>
</body>
</html>