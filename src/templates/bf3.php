<!-- TODO this kind of ifs should go to the controller -->
<?php if( $player_stats !== NULL){	?> 
	
	<div class="bf-page">
		<div class="soldier_name">
			<h1 class="soldier_name"><?php echo $player_stats["name"]; ?></h1>
			<span class="soldier_name">BF3 (<?php echo $player_stats["plat"]; ?>) - <?php echo $player_stats["country_name"]; ?></span>
		</div>
		
		<!-- TODO do not use <table> ever again.. -->
		<div style="padding:0px 5px">
		<table class=".table" style="width:100%;">
			<tr>
				<!-- rank -->
				<td width="33%" class="box">
					<div class="box-header">
						rank
					</div>
					<div class="box-content">
							<img src="http://files.bf3stats.com/img/bf3/<?php echo $player_stats["stats"]["rank"]["img_medium"]; ?>"/>
							<br/>
							<span><?php echo $player_stats["stats"]["rank"]["name"]; ?></span>
							<br/><br/>
							<?php if( array_key_exists("0",$player_stats["stats"]["nextranks"])){ ?>
								<span><?php echo $player_stats["stats"]["nextranks"]["0"]["name"]; ?></span>
								<br/>
								left: <span><?php echo $player_stats["stats"]["nextranks"]["0"]["left"]; ?></span>
							<?php } else{ ?>
								<br/><br/>
							<?php } ?>
					</div>
				</td>
				
				<!-- score -->
				<td width="33%" class="box">
					<div class="box-header">
						Score
					</div>
					<div class="box-content" style="padding-top:31px">
						<?php 
						echo "<h1>".$player_stats["stats"]["scores"]["score"]."</h1>";
						echo "<br/>";
						?>
					</div>
					<div class="pull-left width33" style="margin-right:1px">
					<?php 
						$kd = $player_stats["stats"]["global"]["kills"] / $player_stats["stats"]["global"]["deaths"];
						$wl = $player_stats["stats"]["global"]["wins"] / $player_stats["stats"]["global"]["losses"];?>
						<div class="box-content">
							K/D<br/><span class="important-stats"><?php echo sprintf( "%4.2f", $kd); ?></span></div>
						<div class="box-content" style="margin-top:1px">
							W/L<br/><span class="important-stats"><?php echo sprintf( "%4.2f", $wl); ?></span></div>
					</div>
					<div class="pull-left width33" style="margin-right:1px">
						<div class="box-content">
							Kills<br/><span class="important-stats"><?php echo $player_stats["stats"]["global"]["kills"]; ?></span></div>
						<div class="box-content" style="margin-top:1px">
							Wins<br/><span class="important-stats"><?php echo $player_stats["stats"]["global"]["wins"]; ?></span></div>
					</div>
					<div class="pull-left width33">
						<div class="box-content">
							Deaths<br/><span class="important-stats"><?php echo $player_stats["stats"]["global"]["deaths"]; ?></span></div>
						<div class="box-content" style="margin-top:1px">
							Loses<br/><span class="important-stats"><?php echo $player_stats["stats"]["global"]["losses"]; ?></span></div>
					</div>
					<div style="clear:both"/>
				</td>
				
				<!-- stats -->
				<td width="33%" class="box">
					<div class="box-header">
						Stats
					</div>
					
					<div class="box-content width50 pull-left" style="margin-right:1px; padding-bottom:31px">
						<?php 
						$acc = $player_stats["stats"]["global"]["hits"] / $player_stats["stats"]["global"]["shots"];
						echo 'Accuracy<br/><h1 class="important-stats">'.sprintf( "%4.2f",$acc).'</h1>';
						?>
					</div>
					<div class="width50 pull-left">
						<div class="box-content" style="padding: 9px 0px;">
							Hits<br/><span class="important-stats"><?php echo $player_stats["stats"]["global"]["hits"]; ?></span></div>
						<div class="box-content" style="padding-top: 9px; padding-bottom: 8px;">
							Shots<br/><span class="important-stats"><?php echo $player_stats["stats"]["global"]["shots"]; ?></span></div>
					</div>
					<div class="width50 pull-left" >
						<div class="box-content">
							Headshots<br/><span class="important-stats"><?php echo $player_stats["stats"]["global"]["headshots"]; ?></span></div>
						<div class="box-content">
							Time<br/><span class="important-stats"><?php echo $player_stats["stats"]["global"]["time"]; ?></span></div>
					</div>
					<div class="width50 pull-left">
						<div class="box-content" style="margin-left:1px">
							Dogtags<br/><span class="important-stats"><?php echo $player_stats["stats"]["global"]["dogtags"]; ?></span></div>
						<div class="box-content" style="margin-left:1px">
							Joined<br/><span class="important-stats"><?php echo $player_stats["date_insert"]; ?></span></div>
					</div>
				</td>

			</tr>
			
			<tr>
				<?php 
				$kit_array = array(
					array( "assault", "engineer", "support", "recon", "objective"),
					array( "general", "award", "bonus", "squad", "unlock") );
				$titles = array("Kits score", "Other score");
				for( $i=0; $i<2; $i++){?>
					<td width="33%" class="box">
						<div class="box-header">
							<?php echo $titles[$i]; ?>
						</div>
						
						<?php foreach( $kit_array[$i] as $kit){ ?> 
							<div style="padding:11px 0px" class="box-content">
								<div style="width:38%; text-align:left;" class="pull-left">
									<span class="important-stats" style="padding-left:10px"><?php echo $kit; ?></span>
								</div>
								<div style="width:30%" class="pull-left">
									<?php echo $player_stats["stats"]["scores"][$kit]; ?>
								</div>
								<div style="width:32%" class="pull-left">
									<?php $kit_percent = $player_stats["stats"]["scores"][$kit] / $player_stats["stats"]["scores"]["score"]; ?>
									<span class="important-stats"><?php echo sprintf( "%4.2f",$kit_percent*100)."%"; ?></span>
								</div>
								<div style="clear:both"></div>
							</div>
						<?php }?>
					</td>
				<?php }?>
			</tr>
			
		</table>
		</div>
		
	</div>
	
<?php 
}else{
	echo "Player '" . $player_name . "' not found";
}
 ?>