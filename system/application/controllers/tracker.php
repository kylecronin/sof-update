<?php
class Tracker extends Controller {

	function index()
	{
		
		
	$url = "";

	       for ($i = 0; $i < func_num_args(); $i++)
	       {
	          $url .= func_get_arg($i) . "/";
	       }

	       // $url is now the url in the address
	
	echo $url;
		
		$this->load->view('tracker_index');
	}
	
	function updatedb()
	{
		echo "what's up?";
	}
	

	function chart($user, $low = false, $high = false)
	{
		$this->load->database();
		
		if (!$low)
			$low = time()-2592000;
			
		if (!$high)
			$high = time();
		
		if (!strcmp($low, "all"))
			$low = 0;
		
		
		$query = $this->db->query("SELECT rep, questions, answers, date FROM profile WHERE user = '$user' AND $low < date AND date < $high ORDER BY date DESC");

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
	
	function update($user)
	{
		$this->load->database();	// load database - we'll need it later
		
		$this->load->helper('numformat');
		
		if (!preg_match('/^\d+$/', $user))
		{
			$this->load->view('invalid_user', compact('user'));
			return;
		}
		
		$before = microtime(true);
		$page = file_get_contents("http://stackoverflow.com/users/$user/");
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
		$qreg = '/question-summary narrow.*?vote-count-post"><strong.*?>(-?\d*).*?\/questions\/(\d*).*?>(.*?)<\/a>/s';
		preg_match_all($qreg, $page, $questions, PREG_SET_ORDER);

		// extract answers from $page, store in $answers (array)
		// for an $a in $answers:
		//	$a[1] => votes
		//	$a[2] => answer id
		//	$a[3] => answer text
		$areg = '/answer-votes.*?>([-\d]*).*?#(\d*)">([^<]*)/';
		preg_match_all($areg, $page, $answers, PREG_SET_ORDER);
		
		// get existing profile and insert updated one
		$dbitem = $this->db->query("SELECT * FROM profile WHERE user = '$user' ORDER BY date DESC LIMIT 1")->row();
		$this->db->query("INSERT INTO profile VALUES('$rep', '$badge','".count($questions)."','".count($answers)."','".time()."','$user')");

		// if we're a new user
		if (!$dbitem)
		{
			$dbitem = (object) array('questions' => 0, 'answers' => 0, 'rep' => 0, 'badges' => 0);
		}

		//print_r($profile);
		
		$this->load->view('header', compact('user'));
		$this->load->view('overview', compact('questions', 'answers', 'rep', 'badge', 'dbitem'));
		$this->load->view('questans', array('stuff' => $questions, 'name' => 'questions'));
		$this->load->view('questans', array('stuff' => $answers, 'name' => 'answers'));
		
		$after = microtime(true);
		$pageload = number_format($during-$before, 2, '.', '');
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

		$query = $this->db->query("SELECT user, count(user), max(date), min(date) FROM profile GROUP BY user HAVING count(user) >= $lt ORDER BY $ob LIMIT $num");
		//$result = mysql_query($query);

		$this->load->view('stats', compact('query'));
		
		$this->load->view('footer');
	}
	
}
?>