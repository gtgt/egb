<?php

namespace Egb\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType {
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('username', Type\TextType::class)->add('firstname', Type\TextType::class)->add('lastname', Type\TextType::class)->add('type')->add('email', Type\EmailType::class)
			->add('plainPassword', Type\RepeatedType::class, array(
				'type' => Type\PasswordType::class,
				'first_options' => array('label' => true),
				'second_options' => array('label' => true),
			))
			->add('save', Type\SubmitType::class);
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'Egb\UserBundle\Entity\User',
			'intention' => 'user',
			'translation_domain' => 'UserBundle'
		));
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'user';
	}
}
