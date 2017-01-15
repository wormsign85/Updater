<?php

foreach (glob("stocks_*.xml") as $filename) {
    echo "$filename size " . filesize($filename) . "\n";
    unlink($filename);
}