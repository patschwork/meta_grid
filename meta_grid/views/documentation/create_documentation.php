<?php
use yii\helpers\VarDumper;

// 	echo "<pre>";
// 	VarDumper::dump($dataCollection);
// 	echo "</pre>";	
	
	
	foreach ($dataCollection as $sortOrderKey => $sortOrder)
	{
		foreach ($sortOrder as $object_nameKey => $object_name)
		{
			echo "<h1>".$object_nameKey."</h1>";
			
			foreach ($object_name as $itemKey => $item)
			{

				if (isset($dataCollection[$sortOrderKey][$object_nameKey][$itemKey][Yii::t('app', 'Name')]))
				{
					echo "<h2>".$dataCollection[$sortOrderKey][$object_nameKey][$itemKey][Yii::t('app', 'Name')]."</h2>";
				}
				echo "<table border=1 width='100%'>";
				
				foreach ($item as $detailKey => $detail)
				{
					if ($detailKey!=Yii::t('app', 'Mapping'))
					{
						$detailElement = $dataCollection[$sortOrderKey][$object_nameKey][$itemKey][$detailKey];
						echo "<tr><td width='20%'><b>".$detailKey."</b></td>"."<td>".$detailElement."</td>";
					}
					else
					{
						echo "<tr><td width='20%'><b>".$detailKey."</b></td>";
						
						echo "<td>";

						
							foreach ($detail as $mapKey => $mapItem)
							{
								foreach ($mapItem as $mapElementKey => $mapElement)
								{
									echo "<b>".$mapElementKey.": "."</b>".$mapElement."<br>";
								}
								echo "<hr>";
							}
							
						echo "</td>";
						echo "</tr>";
					}
				}
				echo "<table>";
			}
		}
	}
	exit;
?>;