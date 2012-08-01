<?php

namespace My\UiBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;

use Symfony\Component\Form\AbstractType;

class GameType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
    	$builder->add('releasedAt', 'text');
	}


	public function getDefaultOptions(array $options)
	{
		return array(
			'data_class'	=> 'My\UiBundle\Entity\Game',
		);
	}


	public function getName()
	{
		return 'game';
	}
}