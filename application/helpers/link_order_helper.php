<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

function get_link_order($base, $order_url_next, $page_num) {
	$order_link = '<div class="dropdown">';
	$order_link = $order_link . '<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">';
	$order_link = $order_link . implode('_', $base);
	$order_link = $order_link . '<span class="caret"></span>';
	$order_link = $order_link . '</button>';
	$order_link = '<div class="btn-group">';
	$order_link = $order_link . '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . 'mark_order_link'. '<span class="caret"></span></button>';
	$order_link = $order_link . '<ul class="dropdown-menu">';
	foreach ($order_url_next as $url) {
		$base_url_order_next = site_url(array_merge($base, $url, array($page_num)));
		$order_link = $order_link . '<li><a href="' . $base_url_order_next . '">' . implode('_', array_merge($base, $url)) . '</a></li>';
	}
	$order_link = $order_link . '</ul>';
	$order_link = $order_link . '</div>';
	return $order_link;
}
