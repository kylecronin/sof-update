<?php
class Tracker extends Controller {

	function index()
	{	
		$this->load->view('tracker_index');
	}
	

	function chart($user, $siteid = 1, $low = false, $high = false)
	{
		$this->load->database();
		
		if (!$low)
			$low = "month";
		if (!$high)
			$high = time();
		if (!strcmp($low, "all"))
			$low = 0;
		if (!strcmp($low, "year"))
			$low = time()-31557600;
		if (!strcmp($low, "month"))
			$low = time()-2592000;
		if (!strcmp($low, "week"))
			$low = time()-604800;
		if (!strcmp($low, "day"))
			$low = time()-86400;
		if (!strcmp($low, "hour"))
			$low = time()-3600;
		
		$query = $this->db->query("SELECT rep, questions, answers, date FROM profile WHERE user = '$user' AND site = '$siteid' AND $low < date AND date < $high ORDER BY date ASC");

		$data = "";

		foreach ($query->result() as $row)
		{
			$r = $row->rep;
			$q = $row->questions;
			$a = $row->answers;
			$pd = $row->date;
			$d = $pd."000";
			
			$data .= "[$d, $r, $q, $a],\n";
		}
		
		$data = substr_replace($data, "", -2); // remove last comma
		
		$this->load->view('header');
		$this->load->view('chart', compact('data'));
		$this->load->view('footer');
	}
	
	function _multifetch($urls)
	{
	    // shhsecret = "Welcome to ServerFault"
	
		$mh = curl_multi_init();
		$handles = array();

		foreach (array_keys($urls) as $urlkey)
		{
			$ch = curl_init($urls[$urlkey]);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_COOKIE, 'shhsecret="Welcome to ServerFault"');
			$handles[$urlkey] = $ch;
			curl_multi_add_handle($mh, $ch);
		}

		do {
		    curl_multi_exec($mh, $active);
		} while($active > 0);

		$output = array();

		foreach(array_keys($handles) as $hk)
			$output[$hk] = curl_multi_getcontent($handles[$hk]);

		return $output;
	}
	
	function _readstats($source, $siteid)
	{	
		// extract answers from $page, store in $answers (array)
		// for an $a in $answers:
		//	$a[1] => votes
		//	$a[2] => answer id
		//	$a[3] => answer text
		$areg = '/answer-votes.*?>(-?\d+).*?#(\d+).*?>(.*?)<\/a>( \((\d+)\))?(<\/div>)/s';
		preg_match_all($areg, $source, $answers, PREG_SET_ORDER);
		
		$aids = ""; // that's "answer ids"... but try not to catch it
		
		foreach ($answers as $a)
		{
		    if (strcmp($aids, ""))
		        $aids .= ", ".$a[2];
		    else
		        $aids .= $a[2];
		}
		
		//echo $aids;
		
		$query = $this->db->query("SELECT id, votes, accepted FROM Questions WHERE id IN ($aids) AND site='$siteid'");
		
		
		$dbr = array();
		
		foreach ($query->result() as $row)
            $dbr[$row->id] = $row;
            
        //print_r($dbr);
		
		$ret = array();
		
		$this->db->query("BEGIN");
		
		foreach($answers as $a)
		{
			$id = $a[2];
			$score = $a[1];
			$accepted = preg_match('/answered-accepted" title/s', $a[0]);
			$text = $a[3];
			$qty = $a[5] ? $a[5] : 1;
			
			//$dbitem = $this->db->query("SELECT votes, accepted FROM Questions WHERE id = '$id'")->row();
			if (array_key_exists($id, $dbr))
			    $dbitem = $dbr[$id];
			else
			    $dbitem = NULL;
			
			if ($dbitem)
			{
			    $new 		= false;
				$oldscore	= $dbitem->votes;
				$oldacc		= $dbitem->accepted;
			    
			    if (($score-$oldscore) || ($accepted-$oldacc))
				    $this->db->query("UPDATE Questions SET votes = '$score', accepted = '$accepted' WHERE id = '$id' AND site = '$siteid'");
				    
			}
			else
			{
				$this->db->query("INSERT INTO Questions VALUES('$text', '$score', '$id', '$accepted', '$siteid')");
				$new		= true;
				$oldscore	= 0;
				$oldacc		= 0;
			}
			
			array_push($ret,
				array(	'id' => $id,
						'new' => $new,
						'newscore' => $score,
						'oldscore' => $oldscore,
						'newacc' => $accepted,
						'oldacc' => $oldacc,
						'qty' => $qty,
						'text' => $text));

		}
		
		$this->db->query("END");
		
		//print_r($ret);
		
		return $ret;
	}
	
	function _readapijson($source, $user, $siteid)
	{
	//	echo $source;
	
		$areg = '/{"PostUrl":"(\d+)\/?(\d*)#?\d*","PostTitle":"(.*?)","Rep":(-?\d+)}/s';
		preg_match_all($areg, $source, $answers, PREG_SET_ORDER);
		
		//print_r($answers);
		
		$ret = array();
		
		$this->db->query("BEGIN");
		
		foreach($answers as $a)
		{
			$qid = $a[1];
			$id = $a[2] ? $a[2] : $qid;
			$score = $a[4];
			$text = $a[3];
			
			$dbitem = $this->db->query("SELECT rep FROM posts WHERE id = '$id' AND site = '$siteid'")->row();
			
			//echo "query id $id\n";
			//print_r($dbitem);
			
			if ($dbitem)
			{
			    $new 		= false;
				$oldscore	= $dbitem->rep;
				
				if ($score-$oldscore != 0)
				    $this->db->query("UPDATE posts SET rep = '$score' WHERE id = '$id' AND site = '$siteid'");

			}
			else
			{
				$this->db->query("INSERT INTO posts VALUES('$id', '$qid', '$user', '$score', '$text', '$siteid')");
				$new		= true;
				$oldscore	= 0;
			}
			
			array_push($ret,
				array(	'id' => $id,
						'qid' => $qid,
						'new' => $new,
						'newscore' => $score,
						'oldscore' => $oldscore,
						'text' => $text));

		}
		
		$this->db->query("END");
		
		//print_r($ret);
		
		return $ret;
	}


    function update($user)
    {   
        $this->_update("StackOverflow", "stackoverflow.com", 1, $user);
    }

    function sfupdate($user)
    {
        $this->_update("ServerFault", "serverfault.com", 2, $user);
    }

    
    function _update($sitename, $site, $siteid, $user)
    {    
		$this->load->database();
		
		$this->load->helper('numformat');
		
		//$this->output->enable_profiler(TRUE);
		
		if (!preg_match('/^\d+$/', $user))
		{
			$this->load->view('invalid_user', compact('user'));
			return;
		}
		
		$before = microtime(true);
		$data = $this->_multifetch(array('page' => "http://$site/users/$user/",
										 //'apijson' => "http://stackoverflow.com/users/$user/0/9999999999999"
										 'apijson' => "http://$site/users/rep/$user/2000-01-01/2030-01-01",
										 'questionsapi' => "http://$site/api/userquestions.html?page=1&pagesize=1000000&userId=$user",
										 'answersapi' => "http://$site/api/useranswers.html?page=1&pagesize=1000000&userId=$user"
										));
											
		extract($data);
		
		/*print_r($data);
		exit(0);*/
		
		
		$during = microtime(true);
		
		
		// extract reputation from $page, store in $rep
		preg_match('/summarycount">.*?([,\d]+)<\/div>.*?Reputation/s', $page, $rep);
		$rep = preg_replace("/,/", "", $rep[1]);

		// extract number of badges from $page, store in $badge
		preg_match('/iv class="summarycount".{10,60} (\d+)<\/d.{10,140}Badges/s', $page, $badge);
		$badge = $badge[1];

		// extract questions from $page, store in $questions (array)
		// for a $q in $questions:
		//	$q[1] => votes
		//	$q[2] => question id
		//	$q[3] => question text
		$qreg = '/question-summary narrow.*?>(-?\d+)<.*?\/questions\/(\d*).*?>(.*?)<\/a>/s';
		preg_match_all($qreg, $questionsapi, $questions, PREG_SET_ORDER);
		

		// extract answers from $page, store in $answers (array)
		// for an $a in $answers:
		//	$a[1] => votes
		//	$a[2] => answer id
		//	$a[3] => answer text
		$areg = '/answer-votes.*?>([-\d]*).*?#(\d*)">([^<]*)/';
		preg_match_all($areg, $answersapi, $answers, PREG_SET_ORDER);

		$acreg = '/"answers".*?<div.*?>(\d+)/s';
		preg_match_all($acreg, $page, $ac, PREG_SET_ORDER);
	


		//$answercount = $ac[1];
		//echo($ac[1]);
		//$answercount = count($answers);
		$answercount = $ac[0][1];

		// get existing profile and insert updated one
		$dbitem = $this->db->query("SELECT * FROM profile WHERE user = '$user' AND site = '$siteid' ORDER BY date DESC LIMIT 1")->row();
		$this->db->query("INSERT INTO profile VALUES('$rep', '$badge','".count($questions)."','".$answercount."','".time()."','$user','$siteid')");

		// if we're a new user
		if (!$dbitem)
		{
			$dbitem = (object) array('questions' => 0, 'answers' => 0, 'rep' => 0, 'badges' => 0, 'date' => 0, 'user' => $user);
		}
		
		// get chart data
		// $low = time()-2592000;
		$query = $this->db->query("SELECT rep, questions, answers, date FROM profile WHERE user = '$user' AND site = '$siteid' ORDER BY date ASC");

		$data = "";
		
		if ($query->num_rows() > 1)
		{
			$data = "";
			foreach ($query->result() as $row)
			{
				$r = $row->rep;
				$q = $row->questions;
				$a = $row->answers;
				$pd = $row->date;
				$d = $pd."000";
			
				$data .= "[$d, $r, $q, $a],\n";
			}
			
			$data = substr_replace($data, "", -2); // remove last comma
		}
		else
			$data = false;

		//print_r($profile);
		
		$this->load->view('header', compact('user', 'sitename'));
		$this->load->view('overview', compact('questions', 'answers', 'answercount', 'rep', 'badge', 'dbitem'));
		$this->load->view('reputation', array('site' => $site, 'posts' => $this->_readapijson($apijson, $user, $siteid)));
		
		$this->load->view('questans', array('siteid' => $siteid, 'stuff' => $questions, 'count' => count($questions), 'name' => "questions <font color=\"AAAAAA\"><small><i>(<a href=\"http://$site/questions/ask\"><font color=\"999999\">ask</font></a>)</i></small></font>"));
		////$this->load->view('questans', array('stuff' => $answers, 'count' => $answercount, 'name' => 'answers <font color="AAAAAA"><small><i>(<a href="http://stackoverflow.com/questions"><font color="999999">answer</font></a>)</i></small></font>'));
		$this->load->view('answers', array('site' => $site, 'answers' => $this->_readstats($answersapi, $siteid), 'count' => $answercount));
		
		if ($data)
			$this->load->view('rep', compact('data', 'user', 'siteid'));
		
		
		$after = microtime(true);
		$pageload = number_format($during-$before, 2, '.', '');
		//$page2load = number_format($during-$between, 2, '.', '');
		$dbprocess = number_format($after-$during, 2, '.', '');

		$this->load->view('timer', compact('pageload', 'dbprocess', 'dbitem'));
		$this->load->view('footer');
		
	}
	
	
	function stats($order='top', $num='30', $lt='0')
	{
		$this->load->database();

		if (!strcmp($order, ""))
			$order = "top";
		if (!strcmp($order, "last"))
			$ob = "max(date) DESC";
		if (!strcmp($order, "top"))
			$ob = "count(user) DESC";
		if (!strcmp($order, "first"))
			$ob = "min(date) ASC";
		if (!strcmp($order, "newbies"))
			$ob = "min(date) DESC";

		$query = $this->db->query("SELECT user, site, count(user), max(date), min(date) FROM profile GROUP BY user, site HAVING count(user) >= $lt ORDER BY $ob LIMIT $num");
		//$result = mysql_query($query);
		
		
	    $this->load->view('stats', compact('query'));
		
		//echo "asdf";
		$this->load->view('footer');
	}
	
}
?>