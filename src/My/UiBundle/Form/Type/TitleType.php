<?php

namespace My\UiBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class TitleType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
    	$builder->add('name');
	}
	
	
	public function getDefaultOptions(array $options)
	{
		return array(
			'data_class'	=> '\My\UiBundle\Entity\Title',
		);
	}
	
	
	public function getName()
	{
		return 'title';
	}
}