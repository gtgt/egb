<?php

namespace Egb\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType {
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$user = $builder->getData();
		$builder->add('username', Type\TextType::class)->add('firstname', Type\TextType::class)->add('lastname', Type\TextType::class)
			->add('userType', Type\ChoiceType::class, array(
				'disabled' => ($user && (null !== $user->getId())),
				'required' => true,
				'placeholder' => 'user.type.placeholder',
				'label' => 'user.type.label',
				'choices' => array(
					'user.type.teacher' => 'teacher',
					'user.type.student' => 'student',
					'user.type.parent' => 'parent',
				),
				'empty_data' => array($user ? $user->getUserType() : 'user'),
			))
			->add('email', Type\EmailType::class)
			->add('plainPassword', Type\RepeatedType::class, array(
				'type' => Type\PasswordType::class,
				'first_options' => array('label' => true),
				'second_options' => array('label' => true),
				'required' => false
			))
			->add('submit', Type\SubmitType::class, array('attr' => array('class' => 'btn-primary pull-right')))/*
			->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
				$user = $event->getData();
				$form = $event->getForm();

				// check if the User object is "new"
				// If no data is passed to the form, the data is "null".
				// This should be considered a new "User"
				if ($user && (null !== $user->getId())) {
						$fieldType = $form->get('type');
						$fieldType->setData($user->getUserType());
				}
		})*/;
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
