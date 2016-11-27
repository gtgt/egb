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
		$builder->add('username', Type\TextType::class)->add('firstname', Type\TextType::class)->add('lastname', Type\TextType::class)
			->add('type', Type\ChoiceType::class, array(
				'required' => true,
				'placeholder' => 'form.user.type.placeholder',
				'choices' => array(
					'form.user.type.teacher' => 'teacher',
					'form.user.type.student' => 'student',
					'form.user.type.parent' => 'parent',
				),
			))
			->add('email', Type\EmailType::class)
			->add('plainPassword', Type\RepeatedType::class, array(
				'type' => Type\PasswordType::class,
				'first_options' => array('label' => true),
				'second_options' => array('label' => true),
				'required' => false,
        'empty_data' => function($form) {
            return $form->get('plainPassword')->getData();
        },
			))
			->add('save', Type\SubmitType::class)
			->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        $user = $event->getData();
        $form = $event->getForm();

        // check if the User object is "new"
        // If no data is passed to the form, the data is "null".
        // This should be considered a new "User"
        if (!$user || null === $user->getId()) {
            $fieldPassword = $form->get('password');
        }
    });
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
