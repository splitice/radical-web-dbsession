<?php
namespace Radical\Web\Form\Builder;

class FormBuilder extends FormCommon implements IFormControls {
	function form($form){
		return new FormInstance($form);
	}
}