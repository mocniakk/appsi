<?php
/**
 * Contact type.
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ContactType.
 */
class ContactType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Form options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => 'label.name',
                'required' => true,
                'attr' => ['max_length' => 64],
            ]
        );
        $builder->add(
            'phoneNumber',
            TextType::class,
            [
                'label' => 'label.phoneNumber',
                'required' => true,
                'attr' => ['max_length' => 255],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                            'pattern' => '/^\d{9}$/',
                            'message' => 'message.invalid_phoneNumber',
            ])]]
        );
        $builder->add(
            'email',
            TextType::class,
            [
                'label' => 'label.email',
                'required' => true,
                'attr' => ['max_length' => 255],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email([
                        'message' => ('messages.invalid_email'),
                            ])]
            ]
        );
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'contact';
    }
}
