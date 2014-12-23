<?php namespace Bedard\BlogTags\Widgets;
use Backend\Classes\FormWidgetBase;

class Tagbox extends FormWidgetBase
{
	public function loadAssets()
	{
		$this->addCss('/plugins/bedard/blogtags/widgets/tagbox/assets/css/tagbox.css');
		$this->addJs('/plugins/bedard/blogtags/widgets/tagbox/assets/js/jquery-ui.custom.min.js');
		$this->addJs('/plugins/bedard/blogtags/widgets/tagbox/assets/js/tagbox.js');
	}

	public function render()
	{
		
		$this->prepareVars();
		return $this->makePartial('tagbox');
	}

	public function prepareVars()
	{
		$this->vars['name'] = $this->formField->getName();
		// $this->vars['value'] = $this->getLoadData() ? implode(',', $this->getLoadData()) : '';
	}
}