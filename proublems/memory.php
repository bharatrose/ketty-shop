<?php
class Foo
{
    public $var = '3.14159265359';
}

for ( $i = 0; $i <= 1000000; $i++ )
{
    $a = new Foo;
    $a->self = $a;
}

echo memory_get_peak_usage()/1024, "\n";
if(gc_enabled()) 
{echo gc_collect_cycles();
} else {
	echo "Not unabled";
}
?>