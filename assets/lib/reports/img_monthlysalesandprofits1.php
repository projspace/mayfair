<?
	//header("Content-type: image/png");
	include("../chart/lib_BarChart.php");
	include("../chart/lib_Canvas.php");
	include("../chart/lib_DefaultSkin.php");
	include("../chart/lib_Key.php");
	$canvas=new Canvas(800,500);
	$canvas->setTitle("Monthly Sales and Profit");

	$key=new Key(100,100,$canvas);
	$canvas->setKey(array("Sales (kunits)","Turnover (£k)","Profit (£k)"));

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

	$chart->setYaxis("Sales/Turnover/Profit");
	$chart->addData(array(
					131
					,157
					,189
					,221
					,195
					,154
					,234
					,265
					,243
					,286
					,315
					,395));
	$chart->addData(array(
					131*2.31
					,157*2.12
					,189*2.98
					,221*2.43
					,195*2.32
					,154*2.76
					,234*2.26
					,265*2.29
					,243*2.76
					,286*2.36
					,315*2.12
					,395*2.0));
	$chart->addData(array(
					131*2.0
					,157*1.9
					,189*1.8
					,221*1.7
					,195*1.6
					,154*1.5
					,234*1.4
					,265*1.3
					,243*1.2
					,286*1.1
					,315*1.0
					,395*0.9));
	$canvas->setChart($chart);
	$canvas->draw();
?>