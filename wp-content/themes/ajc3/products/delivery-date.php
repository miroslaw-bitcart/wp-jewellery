<div class="delivery-dates">

	<?php

		$now = new DateTime('', new DateTimeZone('Europe/London'));
		$fivePm = new DateTime("16:00:00");
		$tomorrow = new DateTime('tomorrow');
		$dayafter = new DateTime('tomorrow + 1day');
		$dayafterafter = new DateTime('tomorrow + 2days');

		if(	date("w") == 1
			or date("w") == 2
			or date("w") == 3
			or date("w") == 4
			or date("w") == 5
		)
			{
				if ( $now < $fivePm ){ 
				    echo "Order before 4pm and receive for FREE <strong>tomorrow</strong>, ";
				    echo "<strong>";
				    echo $tomorrow->format('D jS F');
					echo "</strong>, before <strong>1pm</strong>";
				}
				elseif ( $now > $fivePm ) {
					echo "Est FREE UK Delivery: <strong>";
					echo $dayafter->format('D jS F');
					echo "</strong>, before <strong>1pm</strong>";
				}
			}
		elseif(date("w") == 6){
			echo "Est FREE UK Delivery: <strong>";
			echo $dayafterafter->format('D jS F');
			echo "</strong>, before <strong>1pm</strong>";
		} 
		elseif(date("w") == 7){
			echo "Est FREE UK Delivery: <strong>";
			echo $dayafter->format('D jS F');
			echo "</strong>, before <strong>1pm</strong>";
		} 
	?>

</div>