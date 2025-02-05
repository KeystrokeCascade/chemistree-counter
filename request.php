<?php
error_reporting(E_ALL ^ E_WARNING);
$ch = curl_init();

# Set params
curl_setopt($ch, CURLOPT_URL, "https://archiveofourown.org/users/Chemistree/works");
#curl_setopt($ch, CURLOPT_URL, "file://" . getcwd() . "/works.html"); # Local file for testing
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0"); # Fake user agent bc I'm evil and it won't work otherwise.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); # Disable SSL
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);

# Get error
if(curl_errno($ch)) {
	echo "curl error: " . curl_error($ch);
}
curl_close($ch);

# Parse and search document
$doc = new DOMDocument();
$doc->loadHTML($response);
$xpath = new DOMXPath($doc);
$worksContainer = $xpath->query('//ol[@class="work index group"]')[0];
$works = $worksContainer->childNodes;

class WorkInfo {
	public $title;
	public $url;
	public $summary;
	public $chapter;
}
$worksArray = array();

# Get info from each work
foreach ($works as $work) {
	if (trim($work->textContent)) {
		$title = $xpath->query('./div/h4/a', $work)[0];
		$url = $title->attributes->getNamedItem("href")->value;
		$chapters = $xpath->query('./dl[@class="stats"]/dd[@class="chapters"]', $work)[0];
		$summary = $xpath->query('./blockquote/p', $work);

		# Stop at Calling Names
		if ($title->textContent == "Calling Names") {
			break;
		}

		$thisWork = new WorkInfo();
		$thisWork->title = $title->textContent;
		$thisWork->url = $url;
		$thisWork->chapter = explode("/", $chapters->textContent)[0];
		$pars = array();
		foreach ($summary as $par) {
			$pars[] = $par->textContent;
		}
		$thisWork->summary = $pars;

		$worksArray[] = $thisWork;
	}
}
?>
