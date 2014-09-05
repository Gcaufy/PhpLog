<script type="text/javascript">
/*<![CDATA[*/
if(typeof(console)=='object')
{
	console.group("Profiling Summary Report");
	console.log(" count   total   average    min      max   ");
<?php
foreach($data as $index=>$entry)
{
	$proc=strtr($entry[0],array('%'=>'%25',"\t"=>'\t',"\n"=>'\n',"\r"=>'\r','"'=>'\"','\''=>'\\\'','\\'=>'\\\\','</'=>'<\/'));
	$count=sprintf('%5d',$entry[1]);
	$min=sprintf('%0.5f',$entry[2]);
	$max=sprintf('%0.5f',$entry[3]);
	$total=sprintf('%0.5f',$entry[4]);
	$average=sprintf('%0.5f',$entry[4]/$entry[1]);
	echo "\tconsole.log(\" $count  $total  $average  $min  $max    {$proc}\");\n";
}
?>
	console.groupEnd();
}
/*]]>*/
</script>
