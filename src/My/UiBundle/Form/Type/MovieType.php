<?php

namespace My\UiBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;

use Symfony\Component\Form\AbstractType;

class MovieType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
    	$builder->add('quality');
    	$builder->add('source');
    	$builder->add('releasedAt', 'text');
    	$builder->add('uncut', null, array('required'=>false));
    	$builder->add('unrated', null, array('required'=>false));
    	$builder->add('extended', null, array('required'=>false));
	}
	
	
	public function getDefaultOptions(array $options)
	{
		return array(
			'data_class'	=> 'My\UiBundle\Entity\Movie',
		);
	}
	
	
	public function getName()
	{
		return 'movie';
	}
}