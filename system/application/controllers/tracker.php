<?php
class Tracker extends Controller {

	function index() {	
		$this->load->view('tracker_index');
	}
	
	function chart($user, $low = false, $high = false) {
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
		
		$query = $this->db->query("SELECT rep, questions, answers, date FROM profile WHERE user = '$user' AND ".
			"$low < date AND date < $high ORDER BY date DESC");

		$data = "";

		foreach ($query->result() as $row)
		{
			$r = $row->rep;
			$q = $row->questions;
			$a = $row->answers;
			$pd = $row->date;
			$d = $pd."000";
			
			$data .= "[$d, $r, $q, $a],";
		}

		$data = "[$data];";
		
		$this->load->view('header');
		$this->load->view('chart', compact('data'));
		$this->load->view('footer');
	}
	
	function reset($user) {
		$this->load->database();
		
		$this->db->query("UPDATE profiles SET reset=1 WHERE user=$user");
		
		header("Location: update/$user/");
		
	}

	function _makeinsert($table, $array) {
		$keys = "";
		$values = "";
		foreach (array_keys($array) as $key) {
			$keys .= ", $key";
			$values .= ", ".$this->db->escape($array[$key]);
		}
		
		return "INSERT INTO $table (".substr($keys, 2).") VALUES (".substr($values, 2).")";
	}
	
	function _except($source, $exceptions) {
		foreach(array_keys($source) as $key)
			if (!in_array($key, $exceptions))
				$ret[$key] = $source[$key];

		return $ret;
	}
	
	function _checkinsert($table, $old, $new) {
		if (!$old || ($this->_except($old, array('time', 'reset'))) != ($this->_except($new, array('time', 'reset'))))
			$this->db->query($this->_makeinsert($table, $new));
	}
	
	function _doprofile($user, $page) {
		$profile = array('user' => $user, 'time' => time(), 'reset' => 0);

		preg_match('/<h1>\s*(.*?)\s*</s', $page, $name);
		$profile['name'] = $name[1];

		preg_match('/summarycount">.*?([,\d]+)<\/div>.*?Reputation/s', $page, $rep);
		$profile['rep'] = preg_replace("/,/", "", $rep[1]);

		preg_match('/>\s*(.*?egistered User|Moderator)<\/h2>/', $page, $registered);
		$profile['type'] = $registered[1];

		preg_match('/\s*(.*?) days\s*<\/td>/', $page, $memberfor);
		//print_r($memberfor);
		$profile['acctage'] = $memberfor[1];

		preg_match('/"(nofollow )?me\">\s*(.*?)</s', $page, $website);
		//print_r($website);
		$profile['website'] = $website[2];

		preg_match('/Location\s*<\/td>\s*<td>\s*(.*?)\s*</s', $page, $location);
		$profile['location'] = $location[1];

		preg_match('/Age\s*<\/td>\s*<td>\s*(.*?)\s*</s', $page, $age);
		$profile['age'] = $age[1];

		preg_match('/<div id="user-about-me">\s*(.*?)\s*<\/div>/s', $page, $bio);
		//print_r($bio);
		$profile['bio'] = $bio[1];

		preg_match('/summarycount" style="text-align: right;">\s*(.*?)<\/div>\s*<\/td>\s*<td style="vertical-align:middle; padding-left:10px;">\s*<h1>\s*Questions/s', $page, $questions);
		$profile['questions'] = $questions[1];

		preg_match('/summarycount" style="text-align: right;">\s*(.*?)\s*<\/div>\s*<\/td>\s*<td style="vertical-align:middle; padding-left:10px;">\s*<h1>\s*Answers/', $page, $answers);
		$profile['answers'] = $answers[1];

		preg_match('/total number of up votes this user has given">(.*?)</', $page, $upvotes);
		if (!$upvotes)
			$profile['upvotes'] = 0;
		else
			$profile['upvotes'] = $upvotes[1];

		preg_match('/total number of down votes this user has given">(.*?)</', $page, $downvotes);
		if (!$downvotes)
			$profile['downvotes'] = 0;
		else
			$profile['downvotes'] = $downvotes[1];

		preg_match('/summarycount" style="text-align: right;">\s*(.*?)\s*<\/div>\s*<\/td>\s*<td style="vertical-align:middle; padding-left:10px;">\s*<h1>\s*Tags/', $page, $tags);
		$profile['tags'] = $tags[1];

		// extract number of badges from $page, store in $badge
		preg_match('/iv class="summarycount".{10,60} (\d+)<\/d.{10,140}Badges/s', $page, $badge);
		$profile['badges'] = $badge[1];
		
		
		$lastdiff  = $this->db->query("SELECT * FROM profiles WHERE time=(select max(time) from profiles where user=$user)")->row_array();
		$lastreset = $this->db->query("SELECT * FROM profiles WHERE time=(select max(time) from profiles where user=$user and reset=1)")->row_array();
		
		$this->_checkinsert('profiles', $lastdiff, $profile);
		
		$this->load->view('overview', compact('profile', 'lastreset'));
	}
	
	function _doposts($user, $page) {
	
		// extract questions from $page, store in $questions (array)
		// for a $q in $questions:
		//	$q[2] => favorites (blank of no favorites, should reset to 0)
		//  $q[3] => votes
		//  $q[4] => accepted (blank not, not blank is)
		//	$q[5] => answers
		//  $q[6] => views
		//  $q[7] => id/qid
		//  $q[8] => title
		$qreg = '/(b>(\d*)<\/b><\/div>\s*)?<div class="question-summary.*?190%">(-?\d*).*?answered(-accepted)?.*?190%">(\d+).*?190%">(\d+k?).*?\/questions\/(\d*).*?>(.*?)<\/a>/s';
		preg_match_all($qreg, $page, $pageqs, PREG_SET_ORDER);
		
		$newqs = array();
		
		foreach ($pageqs as $q)
		{
			if (!$q[2]) $q[2] = 0;
				
			if ($q[4]) $q[4] = 1;
			else $q[4] = 0;
			
			if ($q[6][strlen($q[6])-1] == 'k')
				$q[6] = substr($q[6], 0, strlen($q[6])-1);
				
			array_push($newqs,
				array(	'id'        => $q[7],
						'qid'       => $q[7],
						'user'      => $user,
						'time'      => $time,
						'reset'     => 0,
						'rep'       => $q[3],
						'accepted'  => $q[4],
						'quantity'  => 1,
						'favorites' => $q[2],
						'answers'   => $q[5],
						'views'     => $q[6],
						'title'     => $q[8]));
		}
		
		// do something with $newqs
		

		// extract answers from $page, store in $answers (array)
		// for an $a in $answers:
		//	$a[1] => votes
		//	$a[2] => answer id
		//	$a[3] => answer text
		$areg = '/answer-votes( answered-accepted)?.*?>([-\d]*).*?\/questions\/(\d*)\/.*?#(\d*)">([^<]*)<\/a>( \((\d*)\))?<\/div/';
		preg_match_all($areg, $page, $pageas, PREG_SET_ORDER);
		
		$newas = array();
		
		foreach ($pageas as $a)
		{		
			if ($a[1]) $a[1] = 1;
			else $a[1] = 0;
			
			if (count($a) < 8)
				$a[7] = 1;
				
			array_push($newas,
				array(	'id' => $a[4],
						'qid' => $a[3],
						'user' => $user,
						'time' => $time,
						'reset' => 0,
						'rep' => $a[2],
						'accepted' => $a[1],
						'quantity' => $a[7],
						'favorites' => 0,
						'answers' => 0,
						'views' => 0,
						'title' => $a[5]));
		}	
		
		// do something with 
	}
	
	function update($user) {
		$this->output->enable_profiler(TRUE);
		$this->load->database();
		$this->load->helper('numformat');
		
		if (!preg_match('/^\d+$/', $user))
		{
			$this->load->view('invalid_user', compact('user'));
			return;
		}
		
		$time = time(); // this is the official time
		
		$before = microtime(true);
		//$page = file_get_contents("http://stackoverflow.com/users/$user/");
		$page = file_get_contents("profile.cache");
		$during = microtime(true);
		
			
		
		$this->load->view('header', compact('user'));
		
		$this->_doprofile($user, $page);
		
		$this->_doposts($user, $page);
		
		
		$after = microtime(true);
		$pageload = number_format($during-$before, 2, '.', '');
		$dbprocess = number_format($after-$during, 2, '.', '');
		$reset = $this->db->query("SELECT max(time) as time FROM profiles WHERE user=$user AND reset=1")->row()->time;
		$this->load->view('timer', compact('pageload', 'dbprocess', 'time', 'reset'));
		
		
		$this->load->view('footer');
		return;
		
		
		

		
		


		
		
		

		
		
		// get existing profile and insert updated one
		//$dbitem = $this->db->query("SELECT * FROM profile WHERE user = '$user' AND reset = 1 ORDER BY date DESC LIMIT 1")->row();
		//$this->db->query("INSERT INTO profile VALUES('$rep', '$badge','".count($questions)."','".count($answers)."','".time()."','$user', 0)");
		
		$lastcheck = $this->db->query("SELECT * FROM profiles WHERE user=$user ORDER BY time DESC LIMIT 1")->row();
		$lastreset = $this->db->query("SELECT * FROM profiles WHERE user=$user AND reset=1 ORDER BY time DESC LIMIT 1")->row();
		
		
		

		// if we're a new user
		if (!$dbitem)
		{
			$dbitem = (object) array('questions' => 0, 'answers' => 0, 'rep' => 0, 'badges' => 0, 'date' => 0, 'user' => $user);
		}
		
		// get chart data
		// $low = time()-2592000;
		$query = $this->db->query("SELECT rep, questions, answers, date FROM profile WHERE user = '$user' ORDER BY date DESC");

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
			
				$data .= "[$d, $r, $q, $a],";
			}
		}
		else
			$data = false;

		//print_r($profile);
		

		$this->load->view('overview', compact('questions', 'answers', 'rep', 'badge', 'dbitem'));
		
		$this->load->view('questans', array('stuff' => $questions, 'name' => 'questions <font color="AAAAAA"><small><i>(<a href="http://stackoverflow.com/questions/ask"><font color="999999">ask</font></a>)</i></small></font>'));
		$this->load->view('questans', array('stuff' => $answers, 'name' => 'answers <font color="AAAAAA"><small><i>(<a href="http://stackoverflow.com/questions"><font color="999999">answer</font></a>)</i></small></font>'));
		
		if ($data)
			$this->load->view('rep', compact('data', 'user'));
		
		



		
	}
	
	function stats($order='top', $num='30', $lt='0') {
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

		$query = $this->db->query("SELECT user, count(user), max(date), min(date) FROM profile GROUP BY user HAVING count(user) >= $lt ORDER BY $ob LIMIT $num");
		//$result = mysql_query($query);

		$this->load->view('stats', compact('query'));
		
		$this->load->view('footer');
	}
	
}
?>