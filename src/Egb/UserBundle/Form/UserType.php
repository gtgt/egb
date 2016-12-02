<?php

namespace Egb\UserBundle\Form;

use Egb\UserBundle\Entity;
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
		/** @var Entity\User $user */
		$user = $builder->getData();
		$isModification = ($user && (null !== $user->id));
		$builder
			->add('userType', Type\ChoiceType::class, array(
				'disabled' => $isModification,
				'required' => true,
				'placeholder' => 'user.type.placeholder',
				'label' => 'user.type.label',
				'choices' => array(
					'user.type.user' => 'user',
					'user.type.teacher' => 'teacher',
					'user.type.student' => 'student',
					'user.type.parent' => 'parent',
				),
				'empty_data' => array($user ? $user->userType : 'user'),
			))
			->add('username', Type\TextType::class)->add('firstname', Type\TextType::class)->add('lastname', Type\TextType::class)
			->add('email', Type\EmailType::class, array('required' => true))
			->add('enabled')
			->add('plainPassword', Type\RepeatedType::class, array(
				'type' => Type\PasswordType::class,
				'first_options' => array('label' => true),
				'second_options' => array('label' => true),
				'required' => !$isModification
			))
			->add('submit', Type\SubmitType::class, array('attr' => array('class' => 'btn-primary pull-right')))
			/*->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
				$form = $event->getForm();
				$user = $form->getData();//(object)$event->getData();

				// check if the User object is "new"
				// If no data is passed to the form, the data is "null".
				// This should be considered a new "User"
				if (!$user || (null === $user->getId())) {
						$type = $form->get('userType')->getData();

				}
		})*/;
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
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
