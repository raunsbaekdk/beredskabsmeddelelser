<?php
// Set timezone
date_default_timezone_set('Europe/Copenhagen');


// Functions
function dateSince($year, $month)
{
	$date = new DateTime();
	$date->setTimestamp(strtotime(date($year . '-' . $month . '-01')));

	$interval = $date->diff(new DateTime('now'));

	return array('years' => $interval->format('%y'), 'months' => $interval->format('%m'), 'days' => $interval->format('%d'));
}

function outputTimeSince($input)
{
	if($input['years'])
	{
		$output.= $input['years'] . ' år, ';
	}

	if($input['months'])
	{
		$output.= $input['months'] . ' måned' . ($input['months'] > 1 ? 'er' : false) . ', ';
	}

	if($input['days'])
	{
		$output.= $input['days'] . ' dag' . ($input['days'] > 1 ? 'e' : false);
	}

	return preg_replace("/[[:blank:]]+/", ' ', $output);
}



// Variables
$months = array('januar' => '01', 'februar' => '02', 'marts' => '03', 'april' => '04', 'maj' => '05', 'juni' => '06', 'juli' => '07', 'august' => '08', 'september' => '09', 'oktober' => '10', 'november' => '11', 'december' => '12');
?><!DOCTYPE html>
<html lang="da">
	<head>
		<title>beredskabsmeddelelser</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
		<style>
		html{overflow-y:scroll}
		.timeline {
			list-style: none;
			padding: 20px 0;
			position: relative;
		}
		.timeline:before {
			top: 0;
			bottom: 0;
			position: absolute;
			content: " ";
			width: 3px;
			background-color: #eee;
			left: 50%;
			margin-left: -1.5px;
		}
		.timeline>li {
			margin-bottom: 20px;
			position: relative;
		}
		.timeline>li:after,
		.timeline>li:before {
			content: " ";
			display: table;
		}
		.timeline>li:after { clear: both }
		.timeline>li>.timeline-panel {
			width: 46%;
			float: left;
			border: 1px solid #d4d4d4;
			border-radius: 2px;
			padding: 20px;
			position: relative;
			-webkit-box-shadow: 0 1px 6px rgba(0,0,0,.175);
			box-shadow: 0 1px 6px rgba(0,0,0,.175);
		}
		.timeline>li>.timeline-panel:before {
			position: absolute;
			top: 26px;
			right: -15px;
			display: inline-block;
			border-top: 15px solid transparent;
			border-left: 15px solid #ccc;
			border-right: 0 solid #ccc;
			border-bottom: 15px solid transparent;
			content: " ";
		}
		.timeline>li>.timeline-panel:after {
			position: absolute;
			top: 27px;
			right: -14px;
			display: inline-block;
			border-top: 14px solid transparent;
			border-left: 14px solid #fff;
			border-right: 0 solid #fff;
			border-bottom: 14px solid transparent;
			content: " ";
		}
		.timeline>li>.timeline-badge {
			color: #fff;
			width: 50px;
			height: 50px;
			line-height: 50px;
			font-size: 1.4em;
			text-align: center;
			position: absolute;
			top: 16px;
			left: 50%;
			margin-left: -25px;
			background-color: #999;
			z-index: 100;
			border-top-right-radius: 50%;
			border-top-left-radius: 50%;
			border-bottom-right-radius: 50%;
			border-bottom-left-radius: 50%;
		}
		.timeline>li.timeline-inverted>.timeline-panel { float: right }
		.timeline>li.timeline-inverted>.timeline-panel:before {
			border-left-width: 0;
			border-right-width: 15px;
			left: -15px;
			right: auto;
		}
		.timeline>li.timeline-inverted>.timeline-panel:after {
			border-left-width: 0;
			border-right-width: 14px;
			left: -14px;
			right: auto;
		}
		.timeline-badge.primary { background-color: #2e6da4!important }
		.timeline-badge.success { background-color: #3f903f!important }
		.timeline-badge.warning { background-color: #f0ad4e!important }
		.timeline-badge.danger { background-color: #d9534f!important }
		.timeline-badge.info { background-color: #5bc0de!important }
		.timeline-title {
			margin-top: 0;
			color: inherit;
		}
		.timeline-body>p,
		.timeline-body>ul { margin-bottom: 0 }
		.timeline-body>p+p { margin-top: 5px }
		@media (max-width:767px) {
			ul.timeline:before { left: 40px }
		}
		</style>
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="page-header">
					<h1 id="timeline">Timeline over beredskabsmeddelelser</h1>
				</div>
				<ul class="timeline">
					<?php
					// Get contents of JSON
					$content = file_get_contents('meddelser.json');
					$content = json_decode($content, true);

					if($content)
					{
						$content = array_reverse($content);
						foreach($content as $key => $value)
						{
					?>
							<li<?php echo ($key % 2 != 0 ? ' class="timeline-inverted"' : false); ?>>
								<div class="timeline-badge"><?php echo ((strpos($value['info'], 'varsling') !== false) ? '<i class="glyphicon glyphicon-warning-sign"></i>' : ((strpos($value['info'], 'beredskabsmed') !== false) ? '<i class="glyphicon glyphicon-envelope"></i>' : false)); ?></div>
								<div class="timeline-panel">
									<div class="timeline-heading">
										<h4 class="timeline-title"><?php echo $value['place']; ?></h4>
										<p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> <?php echo outputTimeSince(dateSince($value['year'], $months[$value['month']])); ?> siden</small></p>
									</div>
									<div class="timeline-body">
										<p><?php echo $value['reason']; ?>.<?php echo ($value['info'] ? ' <i>' . $value['info'] . '.</i>' : false); ?></p>
									</div>
								</div>
							</li>
					<?php
						}
					}
					?>
				</ul>
			</div>
		</div>
	</body>
</html>