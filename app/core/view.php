<?php
class View
{
	//public $template_view; // общий вид по умолчанию.
	
	function generate($content_view, $template_view, $data = null)
	{

		if(is_array($data)) {
			extract($data);
		}
		
		include 'app/views/'.$template_view;
	}
}
