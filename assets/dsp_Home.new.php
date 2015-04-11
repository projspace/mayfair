<script type="text/javascript" src="<?=$config['dir'] ?>lib/mediaplayer/jwplayer.js"></script>
<link href="<?=$config['protocol'] ?>vjs.zencdn.net/c/video-js.css" rel="stylesheet">
<script src="<?=$config['protocol'] ?>vjs.zencdn.net/c/video.js"></script>

<div id="content-wrapper" class="yui3-g">
	<div id="widecontent" class="yui3-u">
		<div id='mediaplayer'></div>
		<script type="text/javascript">
			jwplayer('mediaplayer').setup({
			'flashplayer': '<?=$config['dir'] ?>lib/mediaplayer/player.swf',
			'id': 'playerID',
			'width': '934',
			'height': '527',
			'file': 'http://c1236234.r34.cf3.rackcdn.com/dance_image.mov',
			'image': 'http://c1236234.r34.cf3.rackcdn.com/video-poster.jpg',
			'skin': '<?=$config['dir'] ?>lib/mediaplayer/glow.zip'
			,'autostart': true
			,'plugins': {
				   /*'related-1': {
					   'file': '<?=$config['dir'] ?>video/related.xml',
					   'onclick': 'play',
					   'dimensions': '160x90'
				   }*/
					'sharing-3': {
						link: '<?=$config['dir'] ?>'
						,code: '<embed src="<?=$config['dir'] ?>lib/mediaplayer/player.swf" flashvars="file=http://c1236234.r34.cf3.rackcdn.com/dance_image.mov" width="934" height="527" />'
					}
				}
			});
		</script>
		<ul class="newcats">
			<li> <!-- 170px wide with 20px margin right -->
				<a href="<?=$config['dir'] ?>category/dance/2">
					<img class="bgimg" src="<?=$config['layout_dir'] ?>images/newcats/category_dance_V2.jpg" width="170" height="158" alt="Placeholder" title="Placeholder">
					<img class="topimg" src="<?=$config['layout_dir'] ?>images/newcats/category_dance_CMN_V2.jpg" width="170" height="158" alt="Placeholder" title="Placeholder">
					<h3>Dance</h3>
				</a>
			</li>
			<li>
				<a href="<?=$config['dir'] ?>category/sneakers/25">
					<img class="bgimg" src="<?=$config['layout_dir'] ?>images/newcats/category_dancefitness.jpg" width="170" height="158" alt="Placeholder" title="Placeholder">
					<img class="topimg" src="<?=$config['layout_dir'] ?>images/newcats/category_dancefitness_CMN.jpg" width="170" height="158" alt="Placeholder" title="Placeholder">
					<h3>Dance Fitness</h3>
				</a>
			</li>
			<li>
				<a href="<?=$config['dir'] ?>category/fashion-footwear/4">
					<img class="bgimg" src="<?=$config['layout_dir'] ?>images/newcats/category_fashionfootwear.jpg" width="170" height="158" alt="Placeholder" title="Placeholder">
					<img class="topimg" src="<?=$config['layout_dir'] ?>images/newcats/category_fashionfootwear_CMN.jpg" width="170" height="158" alt="Placeholder" title="Placeholder">
					<h3>Fashion <br /> footwear</h3>
				</a>
			</li>
			<li>
				<a href="<?=$config['dir'] ?>category/dancesport/5">
					<img class="bgimg" src="<?=$config['layout_dir'] ?>images/newcats/category_dancesport.jpg" width="170" height="158" alt="Placeholder" title="Placeholder">
					<img class="topimg" src="<?=$config['layout_dir'] ?>images/newcats/category_dancesport_CMN.jpg" width="170" height="158" alt="Placeholder" title="Placeholder">
					<h3>Dancesport</h3>
				</a>
			</li>
			<li>
				<a href="<?=$config['dir'] ?>all-stars">
					<img class="bgimg" src="<?=$config['layout_dir'] ?>images/newcats/category_artists_.jpg" width="170" height="158" alt="Placeholder" title="Placeholder">
					<img class="topimg" src="<?=$config['layout_dir'] ?>images/newcats/category_artists_CMN.jpg" width="170" height="158" alt="Placeholder" title="Placeholder">
					<h3>Artists</h3>
				</a>
			</li>
		</ul>
	</div>
</div>