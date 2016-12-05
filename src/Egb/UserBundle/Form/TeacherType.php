<?php

namespace Egb\UserBundle\Form;

use Egb\UserBundle\Entity;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeacherType extends UserType {
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		parent::buildForm($builder, $options);
		$teacher = $builder->getData();
		$builder->add('consultingHours', Type\CollectionType::class, array(
			'by_reference' => false,
			'entry_type' => ConsultingHoursType::class,
			'entry_options' => array(
			),
			'prototype' => true,
			'allow_add' => true,
			'allow_delete' => true
		));
	}
}

class ConsultingHoursType extends AbstractType {
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('weekday', Type\ChoiceType::class, array(
			'choices' => array(
				'weekday.0' => 0,
				'weekday.1' => 1,
				'weekday.2' => 2,
				'weekday.3' => 3,
				'weekday.4' => 4,
				'weekday.5' => 5,
				'weekday.6' => 6,
			),
		))->add('when', Type\TimeType::class)
		->add('length', Type\NumberType::class);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'teacher_consulting_hours';
	}
}
