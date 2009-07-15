<?php
class Datadump extends Controller {

    function mainfeed()
    {
        $sofeed = file_get_contents("http://blog.stackoverflow.com/category/cc-wiki-dump/feed/");
        
        echo $sofeed;
    }

}
?>