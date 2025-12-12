<?php
/*
bảng cửu chương $n, màu nền $color
- Input: $n là một số nguyên dương (1->10)
		 $color: Tên màu nền.Mặc định là green
- Output: Bảng cửu chương, được xuât trong hàm
*/
function BCC($n, $color="green")
{
	?>
	<table bgcolor="<?php echo $color;?>">
	<tr><td colspan="3">Bảng cửu chương <?php echo $n;?></td></tr>
	<?php
		for($i=1; $i<=10; $i++)
		{
			?>
			<tr><td><?php echo $n;?></td>
				<td><?php echo $i;?></td>
				<td><?php echo $n*$i;?></td>
			</tr>
			<?php
		}
		?>
		</table>
	<?php	
}
/*
Hàm in ra bàn cờ vua với màu các ô thay đổi và được định nghĩa trong css: cellBlack, cellWhite
- Input: $size: kích thước bàn cờ: là 1 số nguyên dương (mặc định là 8)
- Output: bàn cờ HTML 

*/
function BanCo($size =8)
{
	?>
	<div id="banco">
		<?php
		for($i=1; $i<= $size; $i++)
		{
			for($j=1; $j<= $size; $j++)
			{
				$classCss = (($i+$j) %2)==0?"cellWhite":"cellBlack";
				echo "<div class='$classCss'> $i - $j</div>";
				
			}
			echo "<div class='clear' />";
			
		}
	?>
	</div>
	<?php

}
?>