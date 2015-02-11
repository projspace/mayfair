<?
	//header("Content-type: image/png");
	include("../chart/lib_BarChart.php");
	include("../chart/lib_Canvas.php");
	include("../chart/lib_DefaultSkin.php");
	include("../chart/lib_Key.php");
	$canvas=new Canvas(700,500);
	$canvas->setTitle("Monthly Sales Averages");

	$key=new Key(100,100,$canvas);
	$canvas->setKey(array("Turnover (£)","Profit (£)"));

	$chart=new BarChart();
	$chart->setXaxis("Month",array(
								"Jan"
								,"Feb"
								,"Mar"
								,"Apr"
								,"May"
								,"Jun"
								,"Jul"
								,"Aug"
								,"Sep"
								,"Oct"
								,"Nov"
								,"Dec"));

	$chart->setYaxis("Turnover/Profit per sale (£)");
	$chart->addData(array(
					23
					,53
					,56
					,43
					,47
					,41
					,65
					,76
					,97
					,101
					,131
					,112));
	$chart->addData(array(
					23*0.76
					,53*0.76
					,56*0.66
					,43*0.73
					,47*0.59
					,41*0.78
					,65*0.64
					,76*0.69
					,97*0.66
					,101*0.55
					,131*0.45
					,112*0.34));
	$canvas->setChart($chart);
	$canvas->draw();
?>