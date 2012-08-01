<?php

namespace My\UiBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;

use Symfony\Component\Form\AbstractType;

class ShowType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
    	$builder->add('quality');
    	$builder->add('series');
    	$builder->add('season', null, array('required'=>false));
    	$builder->add('episode', null, array('required'=>false));
	}


	public function getDefaultOptions(array $options)
	{
		return array(
			'data_class'	=> 'My\UiBundle\Entity\Show',
		);
	}


	public function getName()
	{
		return 'show';
	}
}