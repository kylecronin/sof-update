<html>
<head><title>StackOverflow Reputation Tracker</title></head>
<body>
	
	<table>
		<tr height="10"></tr>
		<tr>
			<td width="10"></td>
			<td width="600">
				<h2>StackOverflow Reputation Tracker</h2>
				<p align="justify">
					Welcome to the StackOverflow Reputation Tracker! This
					service will check your user profile page on StackOverflow
					upon request and will present you with the differences
					since the tracker last saw the page. This makes it easy
					and convenient to identify which posts have been up- or
					down-voted in that time and which answers have been
					accepted.
				</p>
				<h3>Getting Started</h3>
				<p align="justify">
					First, you will need to identify your StackOverflow user
					ID number. In the top-center of every page is a menu with
					your name, your reputation, your metal count, and links
					to logout and visit the about and FAQ pages. Click your
					name to bring you to your user profile page. Your address
					bar should be in the following format:
					
					<pre>http://stackoverflow.com/users/[user-id-number]/[user-name]</pre>
					
					Take your user ID number and go to the following page:
					
					<pre>http://sof.modos.org/tracker/update/[user-id-number]</pre>
					
					If this is your first time, you will see a page that lists
					all your questions and answers with their score in green.
					This indicates that all these questions and answers are
					new to the service. If you refresh the page, you will notice
					that they all disappear, since they are no longer new to
					the service. From now on, the service will only show
					what's new or different since the last visit.
				</p>
				<h3>Interpreting the Information</h3>
				<p align="justify">
					The page has three headings: <b>Overview, Questions, and
					Answers.</b><br><br>
					
					<b>Overview</b> displays the current number of questions,
					answers, and badges, as well as your current reputation
					score on the right. To the left of that is an indicator
					of how much it has changed since the last visit. A "+0"
					indicates no change and is black text on the white page.
					If the number of questions, answers, or badges or your
					reputation score went up since the last check, the
					corresponding number will be white on a green rectangle.
					Similarly, the color will be red if the number decreased
					since the last check.<br><br>
					
					<b>Questions</b> and <b>Answers</b> display lists of
					questions and answers respectively that are either new,
					have a different score than the last check, or have had
					an answer accepted or unaccepted. Similarly to the overview,
					an increase in score results in a green rectangle behind
					the change and a decrease results in a red rectangle.
					If an answer is accepted, the "+A" also has a green
					rectangle, and an unaccepted answer has a red rectangle
					behind "-A". If the question or answer is new, the title
					of the question also appears behind a green rectangle.
					Currently the service does not track when a question or
					answer is deleted, but this feature is planned for sometime
					in the future.<br><br>
					
					Finally, at the bottom of the page are two numbers labeled
					scrape and process. These numbers indicate the number of
					seconds it took to retrieve the user page from StackOverflow
					and how long it took to process the page and produce the
					information.
				</p>
				<h3>Frequently Asked Questions (FAQ)</h3>
				<p>
					<ul>
						<li><b>None of my questions or answers changed score,
							so why did my reputation increase/decrease?</b><br>
							There are a few reasons why this may have occurred:
							<br>
							<ul>
								<li>
									The most common is when a post has been up- and
									down-voted equally since the last check. Since
									an up-vote causes a ten-point gain and a down-vote
									causes a two-point loss, the combination leads to
									a net reputation increase of +8. If your reputation
									increase is a multiple of 8, this is likely what
									happened.
								</li>
								<li>
									Another possibility is that every time a down-vote
									is given your reputation decreases by one point.
								</li>
								<li>
									Though it seldom happens, it's possible to gain
									reputation on an answer to a question that's
									been deleted either by a moderator or by the
									asker.
								</li>
							</ul>
						</li>
						<li><b>My question/answer score(s) changed, so why has my
							reputation score remained the same?</b><br>
							If your post is in "Community Mode", you do not
							receive any reputation change, positive or negative,
							from a change in score.
						</li>
					
				</p>
				
			</td>
		</tr>
	</table>
	
</body>
</html>	