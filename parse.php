<?php
function writeContentToFile($text, $file = 'content', $mode = 'a')
{
	$fh = fopen($file, $mode) or die('Cannot create file');
	fwrite($fh, $text);
	fclose($fh);
}





// Get content
$content = file_get_contents('meddelser.txt');
$content = explode("\n", $content);





// Check if content are available
if($content)
{
	foreach($content as $value)
	{
		$value = trim($value);
		if($value)
		{
			preg_match('/^(\d+),\s([^,]+),\s(.*)\((.*)\)(,|)(.*)/', $value, $info);
			$output[] =
			array(
				'info' => ucfirst(trim($info['6'])),
				'month' => mb_strtolower(trim($info['4'])),
				'reason' => ucfirst(trim($info['3'])),
				'place' => ucfirst(trim($info['2'])),
				'year' => trim($info['1'])
			);
		}
	}
}





// Write content to file
if(count($output) > 0)
{
	writeContentToFile(json_encode($output), 'meddelser.json', 'w');
}
?>