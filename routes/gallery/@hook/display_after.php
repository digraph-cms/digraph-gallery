<?php
if (!$package->noun()['gallery.disable_auto']) {
    echo $cms->helper('gallery')->gallery($package->noun());
}